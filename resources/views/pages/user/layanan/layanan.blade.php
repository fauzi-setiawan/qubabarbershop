@extends('layouts.user.app')

@section('title', 'Layanan Quba Barbershop')

@section('content')
<div class="container py-5">
    {{-- Judul halaman --}}
    <h2 class="mb-5 text-center fw-bold text-dark">Layanan Kami</h2>

    {{-- ===================== LAYANAN UTAMA ===================== --}}
    <h4 class="fw-bold mb-3 text-warning" style="text-shadow: 1px 1px 1px gold;">
        <i class="bi bi-scissors"></i> Layanan Utama
    </h4>

    <div class="row g-4 mb-5">
        {{-- Loop data layanan utama --}}
        @forelse($layananUtama as $layanan)
            <div class="col-12 col-md-6 col-lg-4">
                {{-- Card tampilan layanan utama --}}
                <div class="card h-100 shadow-sm border-0 rounded-4 card-hover d-flex flex-column">
                    {{-- Gambar layanan (fallback ke logo jika kosong) --}}
                    <div class="overflow-hidden rounded-top-4" style="height:350px;">
                        <img src="{{ $layanan->foto ? asset('storage/' . $layanan->foto) : asset('image/logo.png') }}"
                             class="w-100 h-100" style="object-fit:cover;">
                    </div>

                    {{-- Bagian isi card --}}
                    <div class="card-body d-flex flex-column p-4">
                        {{-- Nama layanan --}}
                        <h5 class="card-title fw-bold">{{ $layanan->nama_layanan }}</h5>

                        {{-- Deskripsi singkat --}}
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($layanan->deskripsi, 80) }}</p>

                        {{-- Harga layanan --}}
                        <p class="fw-semibold text-warning fs-6 mb-3">
                            Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                        </p>

                        {{-- Tombol booking --}}
                        <a href="{{ route('user.booking.create', ['layanan_utama' => $layanan->id_layanan]) }}" 
                           class="btn btn-warning fw-semibold mt-auto text-dark rounded-pill">
                            <i class="bi bi-scissors"></i> Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
        {{-- Jika data kosong --}}
        @empty
            <div class="col-12 text-center text-muted">
                <p>Belum ada layanan tersedia.</p>
            </div>
        @endforelse
    </div>

    {{-- ===================== LAYANAN TAMBAHAN ===================== --}}
    <h4 class="fw-bold mb-3 text-warning" style="text-shadow: 1px 1px 1px gold;">
        <i class="bi bi-scissors"></i> Layanan Tambahan
    </h4>

    <div class="row g-4">
        {{-- Loop data layanan tambahan --}}
        @forelse($layananTambahan as $layanan)
            <div class="col-6 col-md-4 col-lg-2">
                {{-- Card tampilan layanan tambahan --}}
                <div class="card h-100 shadow-sm border-0 rounded-4 card-hover d-flex flex-column">
                    {{-- Gambar layanan (fallback ke logo jika kosong) --}}
                    <div class="overflow-hidden rounded-top-4" style="height:180px;">
                        <img src="{{ $layanan->foto ? asset('storage/' . $layanan->foto) : asset('image/logo.png') }}"
                             class="w-100 h-100" style="object-fit:cover;">
                    </div>

                    {{-- Isi card --}}
                    <div class="card-body d-flex flex-column p-2">
                        {{-- Nama layanan --}}
                        <h6 class="card-title fw-bold mb-1" style="font-size:0.85rem;">{{ $layanan->nama_layanan }}</h6>

                        {{-- Deskripsi singkat --}}
                        <p class="card-text text-muted small flex-grow-1 mb-1" style="font-size:0.7rem;">
                            {{ Str::limit($layanan->deskripsi, 50) }}
                        </p>

                        {{-- Harga layanan --}}
                        <p class="fw-semibold text-warning fs-7 mb-1">
                            Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                        </p>

                        {{-- Tombol booking layanan tambahan --}}
                        <a href="{{ route('user.booking.create', ['layanan_tambahan' => $layanan->id_layanan]) }}" 
                           class="btn btn-warning fw-semibold mt-auto text-dark btn-sm rounded-pill" style="font-size:0.7rem;">
                            <i class="bi bi-bag-plus"></i> Booking
                        </a>
                    </div>
                </div>
            </div>
        {{-- Jika tidak ada data --}}
        @empty
            <div class="col-12 text-center text-muted">
                <p>Belum ada layanan tersedia.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- ===================== STYLE TAMBAHAN ===================== --}}
<style>
    /* Warna dasar background */
    body { background-color: #f4f4f4; }

    /* Efek hover card */
    .card-hover { transition: 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); }

    /* Warna tombol booking */
    .btn-warning { background-color: #f4b400; border: none; transition: 0.3s; }
    .btn-warning:hover { background-color: #d99a00; color: #fff; }
</style>
@endsection
