@extends('layouts.admin.app')

@section('content')
<style>
    /* Tema konsisten dengan Laporan Customer/Barang */
    body {
        background-color: #1E1E1E;
        color: #FFF;
    }

    .card {
        background-color: #2B2B2B;
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }

    label.form-label {
        color: #C19A6B; /* warna label agar kontras */
        font-weight: 500;
    }

    input.form-control {
        background-color: #2B2B2B;
        color: #F5F5F5; /* teks input lebih terang */
        border: 1px solid #C19A6B;
        padding: 8px;
    }

    input.form-control:focus {
        background-color: #252525;
        border-color: #FFD700;
        box-shadow: 0 0 6px rgba(193,154,107,0.6);
        color: #FFD700; /* fokus input lebih kontras */
    }

    ::placeholder {
        color: #d3c5b0 !important; 
        opacity: 1;
    }

    .btn-primary {
        background-color: #C19A6B;
        border: none;
        color: #1E1E1E;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #B8860B;
        color: #FFF;
    }

    .btn-secondary {
        background-color: #3A3A3A;
        border: none;
        color: #C19A6B;
    }

    .btn-secondary:hover {
        background-color: #C19A6B;
        color: #1E1E1E;
    }
</style>

<div class="container py-5">
    <h3 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Tambah Barang</h3>

    <div class="card shadow-sm p-4">
        <form action="{{ route('admin.barang.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" 
                       class="form-control @error('nama_barang') is-invalid @enderror"
                       value="{{ old('nama_barang') }}" placeholder="Masukkan nama barang" required>
                @error('nama_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" name="brand" id="brand" 
                       class="form-control @error('brand') is-invalid @enderror"
                       value="{{ old('brand') }}" placeholder="Masukkan brand" required>
                @error('brand')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" name="stok" id="stok" 
                       class="form-control @error('stok') is-invalid @enderror"
                       value="{{ old('stok') }}" min="0" placeholder="Masukkan jumlah stok" required>
                @error('stok')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
