@extends('layouts.admin.app')

@section('title', 'Layanan')

@section('content')
<style>
    body {
        background-color: #1E1E1E;
        color: #FFF;
        font-family: 'Poppins', sans-serif;
    }

    /* Tampilan card utama */
    .card {
        background-color: #2B2B2B;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }

    /* Judul halaman */
    h3.fw-bold {
        color: #C19A6B;
        letter-spacing: 0.5px;
    }

    /* Tabel data */
    .table {
        color: #F5F5F5;
        border-color: #3A3A3A;
        background-color: #1E1E1E;
    }

    .table thead {
        background-color: #C19A6B;
        color: #1E1E1E;
        font-weight: bold;
    }

    /* Efek hover pada baris tabel */
    .table tbody tr:hover {
        background-color: rgba(193,154,107,0.15);
        transition: 0.3s;
    }

    /* Tombol utama (Tambah) */
    .btn-success {
        background-color: #C19A6B;
        border: none;
        color: #1E1E1E;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.35rem 0.7rem;
    }

    .btn-success:hover {
        background-color: #B8860B;
        color: #FFF;
    }

    /* Tombol edit */
    .btn-warning {
        background-color: #F4B942;
        border: none;
        color: #1E1E1E;
        font-weight: 600;
    }

    .btn-warning:hover {
        background-color: #DAA520;
        color: #FFF;
    }

    /* Tombol hapus */
    .btn-danger {
        background-color: #E74C3C;
        border: none;
    }

    .btn-danger:hover {
        background-color: #C0392B;
    }

    /* Alert notifikasi sukses */
    .alert-success {
        background-color: #27AE60;
        border: none;
        color: #fff;
    }

    /* Navigasi pagination */
    .page-link {
        background-color: #2B2B2B;
        color: #C19A6B;
        border: 1px solid #3A3A3A;
    }

    .page-link:hover, 
    .page-item.active .page-link {
        background-color: #C19A6B;
        color: #1E1E1E;
        border-color: #C19A6B;
    }
</style>

{{-- ===== HEADER & TOMBOL TAMBAH ===== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><i class="bi bi-scissors me-2"></i>Data Layanan</h3>
    {{-- Tombol menuju halaman tambah layanan --}}
    <a href="{{ route('admin.layanan.create') }}" class="btn btn-success btn-sm">
if        <i class="bi bi-plus-circle me-1"></i> Tambah Layanan
    </a>
</div>

{{-- Notifikasi sukses saat CRUD berhasil --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


{{-- ===================== TABEL LAYANAN UTAMA ===================== --}}
<h5 class="fw-bold text-warning mb-2"><i class="bi bi-star-fill me-1"></i> Layanan Utama</h5>
<div class="card mb-4">
    <div class="card-body table-responsive">
        {{-- Tabel daftar layanan kategori "utama" --}}
        <table class="table table-bordered table-hover align-middle text-center">
            <thead>
                <tr>
                    <th style="width: 25%">Nama Layanan</th>
                    <th style="width: 10%">Harga</th>
                    <th style="width: 40%">Deskripsi</th>
                    <th style="width: 15%">Foto</th>
                    <th style="width: 10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Looping data layanan utama --}}
                @forelse($layanans->where('kategori', 'utama') as $l)
                <tr>
                    {{-- Nama layanan --}}
                    <td>{{ $l->nama_layanan }}</td>

                    {{-- Harga (format ribuan) --}}
                    <td>Rp {{ $l->harga ? number_format($l->harga, 0, ',', '.') : '-' }}</td>

                    {{-- Deskripsi layanan --}}
                    <td style="white-space: pre-wrap;">{{ $l->deskripsi ?? '-' }}</td>

                    {{-- Foto layanan (fallback jika kosong) --}}
                    <td>
                        @if($l->foto)
                            <img src="{{ asset('storage/' . $l->foto) }}" alt="{{ $l->nama_layanan }}" width="70" class="rounded shadow-sm border border-secondary">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- Tombol aksi edit dan hapus --}}
                    <td>
                        {{-- Edit layanan --}}
                        <a href="{{ route('admin.layanan.edit', $l->id_layanan) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        {{-- Form hapus layanan --}}
                        <form action="{{ route('admin.layanan.destroy', $l->id_layanan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus layanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>

                {{-- Jika data kosong --}}
                @empty
                <tr><td colspan="5" class="text-muted">Belum ada layanan tersedia</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===================== TABEL LAYANAN TAMBAHAN ===================== --}}
<h5 class="fw-bold text-warning mb-2"><i class="bi bi-star me-1"></i> Layanan Tambahan</h5>
<div class="card mb-4">
    <div class="card-body table-responsive">
        {{-- Tabel daftar layanan kategori "tambahan" --}}
        <table class="table table-bordered table-hover align-middle text-center">
            <thead>
                <tr>
                    <th style="width: 25%">Nama Layanan</th>
                    <th style="width: 10%">Harga</th>
                    <th style="width: 40%">Deskripsi</th>
                    <th style="width: 15%">Foto</th>
                    <th style="width: 10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Looping data layanan tambahan --}}
                @forelse($layanans->where('kategori', 'tambahan') as $l)
                <tr>
                    {{-- Nama layanan --}}
                    <td>{{ $l->nama_layanan }}</td>

                    {{-- Harga (format ribuan) --}}
                    <td>Rp {{ $l->harga ? number_format($l->harga, 0, ',', '.') : '-' }}</td>

                    {{-- Deskripsi layanan --}}
                    <td style="white-space: pre-wrap;">{{ $l->deskripsi ?? '-' }}</td>

                    {{-- Foto layanan --}}
                    <td>
                        @if($l->foto)
                            <img src="{{ asset('storage/' . $l->foto) }}" alt="{{ $l->nama_layanan }}" width="70" class="rounded shadow-sm border border-secondary">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- Tombol edit dan hapus --}}
                    <td>
                        <a href="{{ route('admin.layanan.edit', $l->id_layanan) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('admin.layanan.destroy', $l->id_layanan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus layanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>

                {{-- Jika tidak ada data --}}
                @empty
                <tr><td colspan="5" class="text-muted">Belum ada layanan tersedia</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
