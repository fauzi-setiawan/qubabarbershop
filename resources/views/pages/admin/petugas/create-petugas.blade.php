@extends('layouts.admin.app')

@section('title', 'Tambah Petugas')

@section('content')
<div class="container py-5">
    <h3 class="mb-4"><i class="bi bi-person-plus me-2"></i>Tambah Petugas</h3>

    <div class="card shadow-sm p-4">
        <form action="{{ route('admin.petugas.store') }}" method="POST">
            @csrf

            {{-- Nama Petugas --}}
            <div class="mb-3">
                <label for="nama_petugas" class="form-label">
                    <i class="bi bi-person me-1"></i>Nama Petugas
                </label>
                <input type="text" name="nama_petugas" id="nama_petugas"
                       class="form-control @error('nama_petugas') is-invalid @enderror"
                       value="{{ old('nama_petugas') }}" placeholder="Masukkan nama petugas..." required>
                @error('nama_petugas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Alamat --}}
            <div class="mb-3">
                <label for="alamat" class="form-label">
                    <i class="bi bi-geo-alt me-1"></i>Alamat
                </label>
                <textarea name="alamat" id="alamat"
                          class="form-control @error('alamat') is-invalid @enderror"
                          rows="3" placeholder="Masukkan alamat lengkap..." required>{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Styling (Sama Seperti Halaman Tambah Layanan) --}}
<style>
    body {
        background-color: #1E1E1E;
        color: #FFF;
        font-family: 'Poppins', sans-serif;
    }

    .card {
        background-color: #2B2B2B;
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }

    label.form-label {
        color: #C19A6B;
        font-weight: 500;
    }

    input.form-control, textarea.form-control {
        background-color: #2B2B2B;
        color: #F5F5F5;
        border: 1px solid #C19A6B;
        padding: 8px;
    }

    input.form-control:focus, textarea.form-control:focus {
        background-color: #252525;
        border-color: #FFD700;
        box-shadow: 0 0 6px rgba(193,154,107,0.6);
        color: #FFD700;
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
@endsection
