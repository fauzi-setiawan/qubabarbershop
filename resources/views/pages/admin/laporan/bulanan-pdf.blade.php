<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            margin: 20px; 
        }
        h2, h3 { 
            text-align: center; 
            margin: 0; 
            padding: 5px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 6px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        .summary { 
            margin-top: 10px; 
            font-weight: bold; 
        }
        .page-break { 
            page-break-after: always; 
        }
        .footer { 
            margin-top: 40px; 
        }
        .footer p { 
            text-align: center; 
            font-size: 12px; 
            color: #555; 
        }
    </style>
</head>
<body>

    <h2>Laporan Bulanan</h2>
    <h3>Tahun {{ $tahun ?? now()->year }}</h3>

    @php
        // Array nama bulan bahasa Indonesia
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei',
            6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober',
            11 => 'November', 12 => 'Desember'
        ];
    @endphp

    @foreach($dataBulanan as $bulan)
        @php
            $bulanNumber = $bulan['bulan_number'] ?? 1;
        @endphp

        {{-- Judul Bulan --}}
        <h3 style="margin-top:20px;">{{ strtoupper($bulanIndo[$bulanNumber] ?? 'Bulan') }}</h3>

        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Tanggal</th>
                    <th style="width: 35%;">Total Orderan</th>
                    <th style="width: 35%;">Total Pendapatan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bulan['hari'] as $hari)
                    <tr>
                        <td>
                            {{-- Format tanggal dd/mm/yyyy --}}
                            {{ str_pad($hari['tanggal'], 2, '0', STR_PAD_LEFT) }}/
                            {{ str_pad($bulanNumber, 2, '0', STR_PAD_LEFT) }}/
                            {{ $tahun ?? now()->year }}
                        </td>
                        <td>{{ $hari['total_orderan'] }}</td>
                        <td>Rp {{ number_format($hari['total_pendapatan'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Ringkasan Bulanan --}}
        <div class="summary">
            Total Orderan Bulan {{ $bulanIndo[$bulanNumber] ?? 'Bulan' }}: {{ $bulan['total_orderan'] }}<br>
            Total Pendapatan Bulan {{ $bulanIndo[$bulanNumber] ?? 'Bulan' }}: Rp {{ number_format($bulan['total_pendapatan'], 0, ',', '.') }}
        </div>

        {{-- Page break --}}
        @if(!($loop->last))
            <div class="page-break"></div>
        @endif
    @endforeach

    {{-- Footer --}}
    <div class="footer">
        <hr>
        <p>
            Dicetak pada: {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB
        </p>
    </div>

</body>
</html>
