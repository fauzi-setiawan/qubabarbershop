@extends('layouts.user.app')

@section('title', 'History Booking')

@section('content')
<div class="container py-5">
    {{-- Header Halaman --}}
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark">
            <i class="bi bi-clock-history text-warning me-2"></i> History Booking
        </h3>
        <p class="text-muted mb-0">
            Riwayat booking kamu di <span class="fw-semibold text-warning">Quba Barbershop</span>
        </p>
    </div>

    {{-- Card Tabel History Booking --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead style="background-color: #f4b400; color: #fff;">
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Layanan</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jam Kunjungan</th>
                        <th>Total Bayar</th>
                        <th>Metode Pembayaran</th>
                        <th>Petugas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop data history booking --}}
                    @forelse ($bookings as $booking)
                        @php
                            $isExpired = \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($booking->waktu_kunjungan));
                            // Ambil semua layanan tambahan
                            $layananTambahan = $booking->layananTambahan->pluck('nama_layanan')->toArray();
                            $layananText = $booking->layanan->nama_layanan;
                            if(count($layananTambahan) > 0){
                                $layananText .= ' + ' . implode(' + ', $layananTambahan);
                            }
                        @endphp

                        {{-- Tampilkan hanya jika sudah selesai atau lewat waktu kunjungan --}}
                        @if($isExpired || $booking->status == 'selesai')
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-dark">{{ $booking->kode_booking }}</td>
                                <td>{{ $layananText }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->waktu_kunjungan)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->waktu_kunjungan)->format('H:i') }}</td>
                                <td class="fw-semibold text-success">
                                    Rp {{ number_format($booking->total, 0, ',', '.') }}
                                </td>
                                <td>{{ ucfirst($booking->metode_pembayaran) }}</td>
                                <td>{{ $booking->petugas->nama_petugas ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">Selesai</span>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted py-4">Belum ada history booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Informasi Tambahan --}}
    <div class="text-center mt-3">
        <small class="text-muted fst-italic">
            ⚠️ Catatan: Riwayat booking akan otomatis terhapus setelah <span class="fw-semibold">30 hari</span> 
            dari tanggal kunjungan.
        </small>
    </div>
</div>

{{-- Style Tambahan (Tema Emas) --}}
<style>
    body {
        background-color: #f4f4f4;
    }

    .card {
        background-color: #fff;
        border-radius: 1rem;
    }

    table {
        border: 1px solid #ddd;
    }

    thead tr th {
        font-weight: 600;
        border: 1px solid #e0c671 !important;
    }

    tbody td {
        border: 1px solid #e5e5e5 !important;
    }

    tbody tr:hover {
        background-color: #fff6e0 !important;
        transition: 0.2s;
    }

    .badge.bg-secondary {
        background-color: #d99a00 !important;
        color: #fff !important;
    }

    .btn-warning {
        background-color: #f4b400;
        border: none;
        transition: 0.3s;
    }

    .btn-warning:hover {
        background-color: #d99a00;
        color: #fff !important;
    }
</style>
@endsection
