@extends('layouts.admin.app')

@section('title', 'Edit Petugas')

@section('content')

<div class="container py-5">
    {{-- Judul halaman --}}
    <h3 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Petugas</h3>

    {{-- Card utama untuk form edit --}}
    <div class="card shadow-sm p-4">
        {{-- Form edit data petugas --}}
        <form action="{{ route('admin.petugas.update', $petugas->id_petugas) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Input nama petugas --}}
            <div class="mb-3">
                <label for="nama_petugas" class="form-label">Nama Petugas</label>
                <input type="text" name="nama_petugas" id="nama_petugas"
                       class="form-control @error('nama_petugas') is-invalid @enderror"
                       value="{{ $petugas->nama_petugas }}" placeholder="Masukkan nama petugas..." required>
                @error('nama_petugas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input alamat petugas --}}
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat"
                          class="form-control @error('alamat') is-invalid @enderror"
                          rows="3" placeholder="Masukkan alamat lengkap..." required>{{ $petugas->alamat }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol aksi (kembali & update) --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Styling dark gold biar seragam sama halaman lain --}}
<style>
body {
    background-color: #1E1E1E;
    color: #FFF;
    font-family: 'Poppins', sans-serif;
}

/* Card utama form */
.card {
    background-color: #2B2B2B;
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.4);
}

/* Label warna emas */
label.form-label {
    color: #C19A6B;
    font-weight: 500;
}

/* Input dan textarea custom style */
input.form-control,
textarea.form-control {
    background-color: #2B2B2B;
    color: #F5F5F5;
    border: 1px solid #C19A6B;
    border-radius: 8px;
    padding: 8px;
}

/* Efek fokus biar lebih elegan */
input.form-control:focus,
textarea.form-control:focus {
    background-color: #252525;
    border-color: #FFD700;
    box-shadow: 0 0 6px rgba(193,154,107,0.6);
    color: #FFD700;
}

/* Placeholder warna kalem */
::placeholder {
    color: #d3c5b0 !important;
    opacity: 1;
}

/* Tombol utama (update) */
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

/* Tombol sekunder (kembali) */
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
