<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mingguan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; }
        h2, h3 { text-align: center; margin: 0; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary { margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Mingguan</h2>
    <h3>Bulan {{ $namaBulan }} {{ $tahun }}</h3>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Minggu Ke-</th>
                <th style="width: 35%;">Rentang Tanggal</th>
                <th style="width: 20%;">Total Orderan</th>
                <th style="width: 25%;">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mingguan as $index => $minggu)
                <tr>
                    <td>Minggu {{ $index + 1 }}</td>
                    <td>{{ $minggu['mulai']->format('d/m/Y') }} - {{ $minggu['selesai']->format('d/m/Y') }}</td>
                    <td>{{ $minggu['total_orderan'] }}</td>
                    <td>Rp {{ number_format($minggu['total_pendapatan'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        Total Orderan Bulan Ini: {{ $totalOrderan }}<br>
        Total Pendapatan Bulan Ini: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
    </div>
    
    <div class="footer">
<hr style="margin-top: 40px;">

<p style="text-align: center; font-size: 12px; color: #555;">
    Dicetak pada: {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB
</p>
    </div>

</body>
</html>
