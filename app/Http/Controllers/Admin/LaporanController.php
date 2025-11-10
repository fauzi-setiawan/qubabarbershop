<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

// Export
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PesananExport;
use App\Exports\PesananHarian;

class LaporanController extends Controller
{
    // Tampilkan halaman laporan utama
    public function index()
    {
        return view('pages.admin.laporan.index');
    }

    // Ambil data pesanan sesuai mode: daily, weekly, monthly
    public function pesanan(Request $request)
    {
        $mode = $request->get('mode', 'daily');
        $date = $request->get('date');
        $labels = [];
        $data = [];
        $pesanans = collect();

        if ($mode === 'daily') {
            // Laporan harian: ambil pesanan hari ini
            $day = Carbon::today();
            $labels[] = $day->format('d-m-Y');
            $data[] = Pesanan::whereDate('waktu_kunjungan', $day)
                ->where('status', 'selesai')
                ->sum('total');

            $pesanans = Pesanan::with('layanan', 'layananTambahan', 'user', 'petugas')
                ->whereDate('waktu_kunjungan', $day)
                ->where('status', 'selesai')
                ->get();
        } elseif ($mode === 'weekly') {
            // Laporan mingguan: 7 hari terakhir
            $start = now()->subDays(6)->startOfDay();
            $period = CarbonPeriod::create($start, now());
            $data = [];

            foreach ($period as $d) {
                $pesananHari = Pesanan::with('layanan', 'layananTambahan', 'user', 'petugas')
                    ->whereDate('waktu_kunjungan', $d)
                    ->where('status', 'selesai')
                    ->get();

                // Kelompokkan per hari
                $itemsByDay = [];
                $dayMap = [1,2,3,4,5,6,0];
                foreach ($dayMap as $dayIndex) {
                    $itemsByDay[] = $pesananHari->filter(fn($p) => Carbon::parse($p->waktu_kunjungan)->dayOfWeek === $dayIndex)->values();
                }

                $data[] = [
                    'label' => $d->translatedFormat('l, d M Y'),
                    'jumlah_pesanan' => $pesananHari->count(),
                    'total_pendapatan' => $pesananHari->sum('total'),
                    'items' => $itemsByDay
                ];
            }

            $pesanans = collect($data)->flatMap(fn($h) => collect($h['items'])->flatten(1));
        } elseif ($mode === 'monthly') {
            // Laporan bulanan: semua bulan dalam tahun ini
            $year = now()->year;
            $data = [];
            $allPesanans = collect();

            for ($m = 1; $m <= 12; $m++) {
                $startMonth = Carbon::create($year, $m, 1)->startOfMonth();
                $endMonth = $startMonth->copy()->endOfMonth();

                // Bagi bulan jadi minggu-minggu
                $weekRanges = [
                    [$startMonth->copy()->day(1), $startMonth->copy()->day(7)->min($endMonth)],
                    [$startMonth->copy()->day(8), $startMonth->copy()->day(14)->min($endMonth)],
                    [$startMonth->copy()->day(15), $startMonth->copy()->day(21)->min($endMonth)],
                    [$startMonth->copy()->day(22), $startMonth->copy()->day(28)->min($endMonth)],
                ];

                if ($endMonth->day > 28) {
                    $weekRanges[] = [$startMonth->copy()->day(29), $endMonth->copy()];
                }

                $mingguData = [];

                foreach ($weekRanges as $wIndex => [$weekStart, $weekEnd]) {
                    // Ambil pesanan per minggu
                    $pesananMinggu = Pesanan::with('layanan', 'layananTambahan', 'user', 'petugas')
                        ->whereBetween('waktu_kunjungan', [$weekStart->startOfDay(), $weekEnd->endOfDay()])
                        ->where('status', 'selesai')
                        ->get();

                    $allPesanans = $allPesanans->merge($pesananMinggu);

                    // Pisahkan per hari di minggu ini
                    $itemsByDay = [];
                    $dayMap = [1,2,3,4,5,6,0];
                    foreach ($dayMap as $dayIndex) {
                        $itemsByDay[] = $pesananMinggu->filter(fn($p) => Carbon::parse($p->waktu_kunjungan)->dayOfWeek === $dayIndex)->values();
                    }

                    $mingguData[] = [
                        'label' => 'Minggu ' . ($wIndex + 1) . ' (' . $weekStart->format('d') . '–' . $weekEnd->format('d M') . ')',
                        'jumlah_pesanan' => $pesananMinggu->count(),
                        'total_pendapatan' => $pesananMinggu->sum('total'),
                        'items' => $itemsByDay
                    ];
                }

                $data[] = [
                    'bulan' => $startMonth->translatedFormat('F Y'),
                    'jumlah_pesanan' => collect($mingguData)->sum('jumlah_pesanan'),
                    'total_pendapatan' => collect($mingguData)->sum('total_pendapatan'),
                    'minggu' => $mingguData
                ];
            }

            $pesanans = $allPesanans;
        }

        // Hitung total pesanan dan total pendapatan
        $totalPesanan = $pesanans->count();
        $totalPendapatan = $pesanans->sum('total');

        return view('pages.admin.laporan.pesanan', compact(
            'mode',
            'date',
            'labels',
            'data',
            'pesanans',
            'totalPesanan',
            'totalPendapatan'
        ));
    }

    // Export laporan ke PDF
    public function exportPdf(Request $request)
    {
        $mode = $request->get('mode', 'daily');

        // Mingguan
        if ($mode === 'weekly') {
            $tahun = now()->year;
            $bulan = now()->month;
            $namaBulan = now()->translatedFormat('F');

            $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $mingguan = [];
            $currentStart = $startOfMonth->copy();

            // Loop tiap minggu di bulan ini
            while ($currentStart->lte($endOfMonth)) {
                $currentEnd = $currentStart->copy()->endOfWeek();
                if ($currentEnd->gt($endOfMonth)) {
                    $currentEnd = $endOfMonth->copy();
                }

                $pesananMinggu = Pesanan::whereBetween('waktu_kunjungan', [$currentStart, $currentEnd])
                    ->where('status', 'selesai')
                    ->get();

                $mingguan[] = [
                    'mulai' => $currentStart->copy(),
                    'selesai' => $currentEnd->copy(),
                    'total_orderan' => $pesananMinggu->count(),
                    'total_pendapatan' => $pesananMinggu->sum('total'),
                ];

                $currentStart = $currentEnd->addDay(); // lanjut minggu berikutnya
            }

            $totalOrderan = collect($mingguan)->sum('total_orderan');
            $totalPendapatan = collect($mingguan)->sum('total_pendapatan');

            $pdf = Pdf::loadView('pages.admin.laporan.mingguan-pdf', compact(
                'mingguan', 'namaBulan', 'tahun', 'totalOrderan', 'totalPendapatan'
            ))->setPaper('a4', 'portrait');

            return $pdf->download("laporan_mingguan_" . now()->format('Ymd_His') . ".pdf");
        }

        // Bulanan
        if ($mode === 'monthly') {
            $tahun = now()->year;
            $dataBulanan = [];

            for ($m = 1; $m <= 12; $m++) {
                $start = Carbon::create($tahun, $m, 1)->startOfMonth();
                $end = $start->copy()->endOfMonth();

                $tanggalData = [];
                for ($d = 1; $d <= $start->daysInMonth; $d++) {
                    $tanggal = Carbon::create($tahun, $m, $d);

                    $pesananHari = Pesanan::whereDate('waktu_kunjungan', $tanggal)
                        ->where('status', 'selesai')
                        ->get();

                    $tanggalData[] = [
                        'tanggal' => $tanggal->format('d/m/Y'),
                        'total_orderan' => $pesananHari->count(),
                        'total_pendapatan' => $pesananHari->sum('total'),
                    ];
                }

                $dataBulanan[] = [
                    'bulan_number' => $m,
                    'bulan' => $start->translatedFormat('F Y'),
                    'hari' => $tanggalData,
                    'total_orderan' => collect($tanggalData)->sum('total_orderan'),
                    'total_pendapatan' => collect($tanggalData)->sum('total_pendapatan'),
                ];
            }

            $pdf = Pdf::loadView('pages.admin.laporan.bulanan-pdf', compact('dataBulanan', 'tahun'))
                ->setPaper('a4', 'portrait');

            return $pdf->download("laporan_bulanan_" . now()->format('Ymd_His') . ".pdf");
        }

        // Harian (default)
        $view = $this->pesanan($request);
        $viewData = $view->getData();

        $pdf = Pdf::loadView('pages.admin.laporan.harian-pdf', [
            'pesanans' => $viewData['pesanans'],
            'totalPesanan' => $viewData['totalPesanan'],
            'totalPendapatan' => $viewData['totalPendapatan']
        ]);

        return $pdf->download("laporan_harian_" . now()->format('Ymd_His') . ".pdf");
    }

    // Export bulanan ke Excel
    public function exportExcel(Request $request)
    {
        $year = $request->get('year', now()->year);

        $dataBulanan = [];

        for ($m = 1; $m <= 12; $m++) {
            $start = Carbon::create($year, $m, 1)->startOfMonth();
            $daysInMonth = $start->daysInMonth;

            $tanggalData = [];

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $tanggal = Carbon::create($year, $m, $d);

                $pesananHari = Pesanan::whereDate('waktu_kunjungan', $tanggal)
                    ->where('status', 'selesai')
                    ->get();

                $tanggalData[] = [
                    'tanggal' => $d,
                    'nama_hari' => $tanggal->translatedFormat('l'),
                    'total_orderan' => $pesananHari->count(),
                    'total_pendapatan' => $pesananHari->sum('total'),
                ];
            }

            $dataBulanan[] = [
                'bulan' => $start->translatedFormat('F Y'),
                'hari' => $tanggalData,
                'total_orderan' => collect($tanggalData)->sum('total_orderan'),
                'total_pendapatan' => collect($tanggalData)->sum('total_pendapatan'),
            ];
        }

        return Excel::download(new PesananExport($dataBulanan, 'monthly'), "laporan_lengkap_{$year}.xlsx");
    }

    // Export harian ke Excel
    public function exportExcelHarian(Request $request)
    {
        $tanggal = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::today();

        // Ambil semua pesanan hari ini
        $pesananHari = Pesanan::with(['user', 'layanan', 'petugas'])
            ->whereDate('waktu_kunjungan', $tanggal)
            ->get();

        if ($pesananHari->isEmpty()) {
            return back()->with('error', 'Tidak ada data pada tanggal tersebut.');
        }

        return Excel::download(
            new PesananHarian($pesananHari),
            "laporan_harian_" . $tanggal->format('Ymd') . ".xlsx"
        );
    }
}
