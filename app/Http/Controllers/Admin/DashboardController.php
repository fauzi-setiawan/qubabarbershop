<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil tanggal hari ini
        $today = Carbon::today();

        // Hitung total (pesanan hanya hari ini)
        $totalPesanan  = Pesanan::whereDate('waktu_kunjungan', $today)->count();
        $totalLayanan  = Layanan::count();
        $totalCustomer = User::where('role', 'user')->count();

        // Rentang 7 hari terakhir
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        // Ambil data jumlah pesanan per hari berdasarkan waktu_kunjungan
        $kunjunganHarian = Pesanan::select(
                DB::raw('DATE(waktu_kunjungan) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('waktu_kunjungan', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->keyBy('tanggal');

        // Siapin label & data (pastikan 7 hari full ada, walau 0 pesanan)
        $labels = [];
        $data   = [];

        for ($i = 0; $i < 7; $i++) {
            $date   = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->translatedFormat('D, d M'); 
            // contoh: Sen, 06 Okt

            $data[] = $kunjunganHarian[$date]->total ?? 0;
        }

        return view('pages.admin.dashboard', compact(
            'totalPesanan',
            'totalLayanan',
            'totalCustomer',
            'labels',
            'data'
        ));
    }
}
