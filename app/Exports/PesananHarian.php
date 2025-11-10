<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class PesananHarian implements FromArray, WithHeadings, WithStyles
{
    protected $pesanans;

    public function __construct($pesanans)
    {
        $this->pesanans = $pesanans;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Booking',
            'Nama Customer',
            'Layanan',
            'Total',
            'Metode Pembayaran',
            'Waktu Kunjungan',
            'Petugas',
            'Total Bayar'
        ];
    }

    public function array(): array
    {
        $rows = [];
        $totalOrderan = 0;
        $totalPendapatan = 0;

        foreach ($this->pesanans as $i => $p) {
            // Gabungkan layanan utama + layanan tambahan jika ada
            $layananUtama = $p->layanan->nama_layanan ?? '-';
            $layananTambahan = ($p->layananTambahan && $p->layananTambahan->count() > 0)
                ? ' + ' . $p->layananTambahan->pluck('nama_layanan')->join(', ')
                : '';

            $rows[] = [
                $i + 1,
                $p->kode_booking ?? '-',
                $p->user->nama ?? '-',
                $layananUtama . $layananTambahan,
                $p->total ?? 0,
                $p->metode_pembayaran ?? '-',
                Carbon::parse($p->waktu_kunjungan)->format('d-m-Y H:i'),
                $p->petugas ? ucfirst($p->petugas->nama_petugas) : '-',
                $p->total_bayar ?? $p->total ?? 0,
            ];

            $totalOrderan++;
            $totalPendapatan += $p->total_bayar ?? $p->total ?? 0;
        }

        // Tambahkan 2 baris kosong
        $rows[] = ['', '', '', '', '', '', '', '', ''];
        $rows[] = ['', '', '', '', '', '', '', '', ''];

        // Total mulai di kolom G (kolom ke-7)
        $rows[] = ['', '', '', '', '', '', 'TOTAL ORDERAN', $totalOrderan, ''];
        $rows[] = ['', '', '', '', '', '', 'TOTAL PENDAPATAN', $totalPendapatan, ''];

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // ====== HEADER ======
        $sheet->getStyle("A1:{$highestColumn}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$highestColumn}1")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('C19A6B');

        // Tambah border pada header
        $sheet->getStyle("A1:{$highestColumn}1")
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);

        // ====== BORDER DATA ======
        $sheet->getStyle("A2:{$highestColumn}" . ($highestRow - 4))
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // ====== AUTO SIZE ======
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ====== FORMAT RUPIAH ======
        $sheet->getStyle("E2:E" . ($highestRow - 4))
            ->getNumberFormat()->setFormatCode('"Rp" #,##0');
        $sheet->getStyle("I2:I" . ($highestRow - 4))
            ->getNumberFormat()->setFormatCode('"Rp" #,##0');

        // ====== BARIS TOTAL (mulai dari G) ======
        $totalStartRow = $highestRow - 1;

        $sheet->getStyle("G{$totalStartRow}:H{$highestRow}")
            ->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFFF00');
        $sheet->getStyle("G{$totalStartRow}:H{$highestRow}")
            ->getFont()->setBold(true);
        $sheet->getStyle("G{$totalStartRow}:H{$highestRow}")
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);

        // Format baris pendapatan jadi "Rp"
        $sheet->getStyle("H{$highestRow}")
            ->getNumberFormat()->setFormatCode('"Rp" #,##0');

        // ====== RATA TENGAH SEMUA ======
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);
    }
}
