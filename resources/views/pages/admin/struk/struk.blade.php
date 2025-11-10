<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $pesanan->kode_booking }}</title>
    <style>
        body { font-family: monospace, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .container { width: 260px; margin: auto; padding: 10px; border: 1px dashed #000; }
        h3, h4 { text-align: center; margin: 0; padding: 0; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 5px; }
        td { padding: 4px 2px; vertical-align: top; }
        .right { text-align: right; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .text-danger { color: red; }
    </style>
    @php use Carbon\Carbon; @endphp
</head>
<body>
    <div class="container">
        <h3>QUBA BARBERSHOP</h3>
        <h4>Jl. Rasuna Said No.10, Kec. Pinang</h4>
        <h4>Kota Tangerang, Banten</h4>
        <div class="line"></div>

        @php
            $waktuKunjungan = Carbon::parse($pesanan->waktu_kunjungan);
            $total = is_numeric($pesanan->total) ? $pesanan->total : 0;
            $uangBayar = isset($uangBayar) && is_numeric($uangBayar) ? $uangBayar : 0;
            $kembalian = isset($kembalian) && is_numeric($kembalian) ? $kembalian : null;
        @endphp

        <table>
            <tr>
                <td>Kode Booking</td>
                <td class="right">{{ $pesanan->kode_booking }}</td>
            </tr>
            <tr>
                <td>Customer</td>
                <td class="right">{{ $pesanan->user->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Layanan</td>
                <td class="right">
                    {{ $pesanan->layanan->nama_layanan ?? '-' }}
                    @if($pesanan->layananTambahan && $pesanan->layananTambahan->count() > 0)
                        <br>
                        @foreach($pesanan->layananTambahan as $lt)
                            + {{ $lt->nama_layanan }}<br>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td>Waktu Kunjungan</td>
                <td class="right">
                    {{ $waktuKunjungan->format('d/m/Y') }} <br>
                    {{ $waktuKunjungan->format('H:i') }} WIB
                </td>
            </tr>
            <tr>
                <td>Metode</td>
                <td class="right">{{ strtoupper($pesanan->metode_pembayaran) }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <!-- Bagian Total, Tunai, Kembalian -->
        <table style="margin-top:10px;">
            <tr>
                <td class="bold">TOTAL BAYAR</td>
                <td class="right bold">Rp {{ number_format($total,0,',','.') }}</td>
            </tr>
            <tr>
                <td>Tunai</td>
                <td class="right">
                    @if($uangBayar > 0)
                        Rp {{ number_format($uangBayar,0,',','.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="right">
                    @if($kembalian !== null && $kembalian >= 0)
                        Rp {{ number_format($kembalian,0,',','.') }}
                    @elseif($kembalian < 0)
                        <span class="text-danger">Uang kurang!</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>

        <div class="line"></div>
        <p class="center">--- TERIMA KASIH ---</p>
        <p class="center" style="font-size: 11px;">
            Dicetak: {{ Carbon::now('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
        </p>

        <!-- Layanan Konsumen WA langsung di bawah TERIMA KASIH, tanpa garis -->
        <p class="center" style="margin-top: 5px; font-size: 12px;">
            Layanan Konsumen: +62 812-3456-7890
        </p>
    </div>
</body>
</html>
