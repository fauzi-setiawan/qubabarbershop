<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PesananPerBulanSheet implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $bulanData;

    public function __construct($bulanData)
    {
        // simpan data bulan
        $this->bulanData = $bulanData;
    }

    public function title(): string
    {
        return $this->bulanData['bulan']; // nama sheet
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama Hari', 'Total Orderan', 'Total Pendapatan']; // header nye
    }

    public function array(): array
    {
        $hariIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $rows = [];

        foreach ($this->bulanData['hari'] as $item) {
            $namaHariIndo = $hariIndo[$item['nama_hari']] ?? $item['nama_hari'];

            $rows[] = [
                $item['tanggal'],
                $namaHariIndo,
                $item['total_orderan'],
                $item['total_pendapatan']
            ];
        }

        // kosong sebelum total
        $rows[] = ['', '', '', ''];

        // total
        $rows[] = ['', '', 'TOTAL ORDERAN', $this->bulanData['total_orderan']];
        $rows[] = ['', '', 'TOTAL PENDAPATAN', $this->bulanData['total_pendapatan']];

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // header style
        $sheet->getStyle("A1:D1")->getFont()->setBold(true);
        $sheet->getStyle("A1:D1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('C19A6B');

        // border data
        $sheet->getStyle("A1:D" . ($highestRow - 3))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // rata tengah dan wrap text
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        // auto size kolom
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // format angka
        $sheet->getStyle("D2:D" . ($highestRow - 3))->getNumberFormat()->setFormatCode('"Rp" #,##0');
        $sheet->getStyle("C2:C" . ($highestRow - 3))->getNumberFormat()->setFormatCode('#,##0');

        // total style
        $sheet->getStyle("C" . ($highestRow - 1) . ":D{$highestRow}")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("C" . ($highestRow - 1) . ":C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("D" . ($highestRow - 1))->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("D{$highestRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
    }
}
