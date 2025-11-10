@extends('layouts.user.app')

@section('title', 'Booking Saya')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="bi bi-calendar-check text-warning me-2"></i>Booking Saya
        </h3>
        <a href="{{ route('user.booking.create') }}" class="btn btn-warning fw-semibold text-dark">
            <i class="bi bi-plus-circle me-1"></i> Tambah Booking
        </a>
    </div>

    {{-- Card Tabel Booking --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead style="background-color: #f4b400; color: #fff;">
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Layanan</th>
                        <th>Tambahan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Capster</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        @php
                            $isExpired = \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($booking->waktu_kunjungan));
                            $canPrint = $booking->status === 'disetujui';
                            $canEdit = $booking->status === 'pending';
                            $canCancel = in_array($booking->status, ['pending', 'disetujui']);
                        @endphp

                        @if(!$isExpired && $booking->status != 'selesai')
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-dark">{{ $booking->kode_booking }}</td>
                                <td class="text-start">{{ $booking->layanan->nama_layanan ?? '-' }}</td>
                                <td class="text-start">
                                    @if($booking->layananTambahan->count() > 0)
                                        {{ $booking->layananTambahan->pluck('nama_layanan')->join(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($booking->waktu_kunjungan)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->waktu_kunjungan)->format('H:i') }}</td>
                                <td class="fw-semibold text-success">Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-secondary text-dark">
                                        {{ strtoupper($booking->metode_pembayaran ?? '-') }}
                                    </span>
                                </td>
                                <td>{{ $booking->petugas->nama_petugas ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = match($booking->status) {
                                            'pending' => 'secondary',
                                            'disetujui' => 'success',
                                            'ditolak' => 'danger',
                                            'selesai' => 'success',
                                            default => 'dark',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} px-3 py-2 rounded-pill">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">

                                        {{-- Tombol Print --}}
                                        @if ($canPrint)
                                            <a href="{{ route('user.booking.print', $booking->id_pesanan) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-success shadow-sm"
                                               data-bs-toggle="tooltip"
                                               title="Cetak tiket booking">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary shadow-sm" disabled
                                                data-bs-toggle="tooltip"
                                                title="Booking belum disetujui oleh admin">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                        @endif

                                        {{-- Tombol Edit --}}
                                        @if ($canEdit)
                                            <a href="{{ route('user.booking.edit', $booking->id_pesanan) }}" 
                                               class="btn btn-sm btn-warning text-dark shadow-sm"
                                               data-bs-toggle="tooltip"
                                               title="Ubah jadwal atau layanan">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary shadow-sm" disabled
                                                data-bs-toggle="tooltip"
                                                title="Booking sudah disetujui / diproses, tidak bisa diedit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        @endif

                                        {{-- Tombol Batalkan --}}
                                        @if ($canCancel)
                                            <form action="{{ route('user.booking.destroy', $booking->id_pesanan) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Yakin ingin membatalkan booking ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger shadow-sm"
                                                        data-bs-toggle="tooltip"
                                                        title="Batalkan booking ini">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary shadow-sm" disabled
                                                data-bs-toggle="tooltip"
                                                title="Booking sudah selesai / ditolak, tidak bisa dibatalkan">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="11" class="py-4 text-muted">
                                <i class="bi bi-calendar-x me-1"></i> Belum ada booking aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Style --}}
<style>
    body { background-color: #f4f4f4; }

    .card { 
        background-color: #fff; 
        border-radius: 1rem; 
    }

    table { border: 1px solid #ddd; }
    thead tr th { font-weight: 600; border: 1px solid #e0c671 !important; }
    tbody td { border: 1px solid #e5e5e5 !important; }
    tbody tr:hover { background-color: #fff6e0 !important; transition: 0.2s; }

    .badge.bg-success { background-color: #27AE60 !important; }
    .badge.bg-secondary { background-color: #d99a00 !important; color: #fff !important; }
    .badge.bg-danger { background-color: #E74C3C !important; }
    .badge.rounded-pill { border-radius: 50rem !important; }

    .btn-warning { background-color: #f4b400; border: none; transition: 0.3s; }
    .btn-warning:hover { background-color: #d99a00; color: #fff !important; }

    .shadow-sm { box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important; }
</style>

{{-- Tooltip --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltips.map(el => new bootstrap.Tooltip(el));
    });
</script>
@endpush

@endsection
