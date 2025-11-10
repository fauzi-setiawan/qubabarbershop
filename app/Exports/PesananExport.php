<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PesananExport implements WithMultipleSheets
{
    protected $pesanans; // data pesanan per bulan

    public function __construct($pesanans)
    {
        $this->pesanans = $pesanans; // simpan data untuk dibuat sheet
    }

    // buat sheet per bulan
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->pesanans as $bulanData) {
            $sheets[] = new PesananPerBulanSheet($bulanData); // sheet untuk tiap bulan
        }

        return $sheets;
    }
}
