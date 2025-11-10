<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tiket Booking {{ $booking->kode_booking }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Helvetica', Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 20px;
    }

    .ticket-card {
      max-width: 500px;
      margin: 0 auto;
      background: #fff;
      border-radius: 15px;
      padding: 25px 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border: 2px dashed #ddd;
    }

    .ticket-card h2 {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
      color: #333;
    }

    .ticket-card .code {
      text-align: center;
      color: #888;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .ticket-card table {
      width: 100%;
      font-size: 15px;
      border-collapse: collapse;
    }

    .ticket-card th {
      width: 40%;
      text-align: left;
      padding: 6px 0;
      color: #555;
    }

    .ticket-card td {
      padding: 6px 0;
      color: #333;
      font-weight: 500;
    }

    .ticket-footer {
      text-align: center;
      font-size: 13px;
      padding: 12px;
      background: #fafafa;
      border-top: 1px dashed #ccc;
      color: #555;
      margin-top: 20px;
      border-radius: 0 0 15px 15px;
    }

    @media print {
      body {
        padding: 0;
        background: #fff;
      }
      .ticket-card {
        box-shadow: none;
        border: 2px dashed #000;
      }
      .ticket-footer {
        border-top: 1px dashed #000;
      }
    }
  </style>
</head>
<body>
  <div class="ticket-card">
    <h2>TIKET BOOKING</h2>
    <div class="code">Kode: {{ $booking->kode_booking }}</div>

    <table>
      <tr>
        <th>Layanan</th>
        <td>{{ $booking->layanan->nama_layanan ?? '-' }}</td>
      </tr>
      <tr>
        <th>Layanan Tambahan</th>
        <td>
          @if($booking->layananTambahan && $booking->layananTambahan->isNotEmpty())
            @foreach($booking->layananTambahan as $layanan)
              {{ $layanan->nama_layanan }}@if(!$loop->last), @endif
            @endforeach
          @else
            -
          @endif
        </td>
      </tr>
      <tr>
        <th>Tanggal</th>
        <td>{{ \Carbon\Carbon::parse($booking->waktu_kunjungan)->format('d-m-Y') }}</td>
      </tr>
      <tr>
        <th>Jam</th>
        <td>{{ \Carbon\Carbon::parse($booking->waktu_kunjungan)->format('H:i') }}</td>
      </tr>
      <tr>
        <th>Total Bayar</th>
        <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <th>Metode Pembayaran</th>
        <td>{{ ucfirst($booking->metode_pembayaran) }}</td>
      </tr>
      <tr>
        <th>Capster</th>
        <td>{{ $booking->petugas->nama_petugas ?? '-' }}</td>
      </tr>
    </table>

<div class="ticket-footer">
  Terima kasih telah melakukan booking di <strong>Quba Barbershop</strong>.<br>
  Mohon tunjukkan tiket ini saat datang.<br>
  <strong>Catatan:</strong> Toleransi keterlambatan maksimal <strong>2 jam</strong> dari jam yang ditentukan.  
  Jika melewati batas ini, booking akan otomatis dibatalkan dan uang tidak dapat direfund.
</div>

  </div>
</body>
</html>
