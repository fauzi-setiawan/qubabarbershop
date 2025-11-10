@extends('layouts.admin.app')

@section('title', 'Tambah Layanan')

@section('content')
<div class="container py-5">
    <h3 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Tambah Layanan</h3>

    <div class="card shadow-sm p-4">
        <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama Layanan --}}
            <div class="mb-3">
                <label for="nama_layanan" class="form-label">Nama Layanan</label>
                <input type="text" name="nama_layanan" id="nama_layanan"
                       class="form-control @error('nama_layanan') is-invalid @enderror"
                       value="{{ old('nama_layanan') }}" placeholder="Contoh: Potong Rambut Dewasa" required>
                @error('nama_layanan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kategori --}}
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select name="kategori" id="kategori" 
                        class="form-select @error('kategori') is-invalid @enderror" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="utama" {{ old('kategori') == 'utama' ? 'selected' : '' }}>Layanan Utama</option>
                    <option value="tambahan" {{ old('kategori') == 'tambahan' ? 'selected' : '' }}>Layanan Tambahan</option>
                </select>
                @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Harga --}}
            <div class="mb-3">
                <label for="hargaInput" class="form-label">Harga</label>
                <input type="text" name="harga" id="hargaInput"
                       class="form-control @error('harga') is-invalid @enderror"
                       value="{{ old('harga') }}" placeholder="Contoh: 50.000" required>
                @error('harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi"
                          class="form-control @error('deskripsi') is-invalid @enderror"
                          placeholder="Tulis deskripsi layanan...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" id="foto"
                       class="form-control @error('foto') is-invalid @enderror">
                @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script auto-format harga --}}
<script>
document.getElementById('hargaInput').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    if(value) {
        e.target.value = new Intl.NumberFormat('id-ID').format(value);
    } else {
        e.target.value = '';
    }
});
</script>

{{-- Styling --}}
<style>
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
        color: #C19A6B;
        font-weight: 500;
    }

    input.form-control, textarea.form-control, select.form-select {
        background-color: #2B2B2B;
        color: #F5F5F5;
        border: 1px solid #C19A6B;
        padding: 8px;
    }

    input.form-control:focus, textarea.form-control:focus, select.form-select:focus {
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
