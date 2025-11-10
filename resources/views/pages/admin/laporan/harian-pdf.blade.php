<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
        h2, h4 { text-align: center; margin: 0; padding: 5px; }
        .summary { margin-top: 20px; font-weight: bold; }
        .footer { margin-top: 40px; }
        small { color: #555; font-size: 11px; }
    </style>
</head>
<body>
    <h2>Laporan Harian</h2>

    {{-- Tanggal laporan ditampilkan dalam format bulan teks --}}
    <h4>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</h4>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">Kode Booking</th>
                <th style="width: 15%;">Nama Customer</th>
                <th style="width: 20%;">Layanan</th>
                <th style="width: 12%;">Petugas</th>
                <th style="width: 10%;">Metode Bayar</th>
                <th style="width: 12%;">Waktu Kunjungan</th>
                <th style="width: 15%;">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            {{-- Looping data pesanan harian --}}
            @forelse($pesanans as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->kode_booking ?? '-' }}</td>
                    <td>{{ $p->user->nama ?? '-' }}</td>

                    {{-- Menampilkan layanan utama dan tambahan jika ada --}}
                    <td>
                        {{ $p->layanan->nama_layanan ?? '-' }}
                        @if($p->layananTambahan && $p->layananTambahan->count() > 0)
                            + {{ $p->layananTambahan->pluck('nama_layanan')->join(', ') }}
                        @endif
                    </td>

                    {{-- Menampilkan nama petugas yang melayani --}}
                    <td>{{ $p->petugas->nama_petugas ?? '-' }}</td>

                    {{-- Metode pembayaran pesanan --}}
                    <td>{{ ucfirst($p->metode_pembayaran ?? '-') }}</td>

                    {{-- Format waktu kunjungan dengan tanggal dan jam --}}
                    <td>
                        {{ \Carbon\Carbon::parse($p->waktu_kunjungan)->format('d-m-Y') }}<br>
                        <small>{{ \Carbon\Carbon::parse($p->waktu_kunjungan)->format('H:i') }} WIB</small>
                    </td>

                    {{-- Total pembayaran pesanan --}}
                    <td>Rp {{ number_format($p->total_bayar ?? $p->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                {{-- Jika tidak ada data pesanan --}}
                <tr>
                    <td colspan="8" style="text-align:center;">Tidak ada data hari ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Ringkasan jumlah pesanan dan total pendapatan --}}
    <div class="summary">
        <p>Total Pesanan: {{ $totalPesanan }}</p>
        <p>Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>

    {{-- Bagian footer dengan waktu cetak laporan --}}
    <div class="footer">
        <hr>
        <p style="text-align: center; font-size: 12px; color: #555;">
            Dicetak pada: {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB
        </p>
    </div>
</body>
</html>
