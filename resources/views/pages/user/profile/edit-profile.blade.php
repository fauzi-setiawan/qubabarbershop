@extends('layouts.user.app')

@section('title', 'Edit Profil Saya')

@section('content')
<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4 text-dark">
                <i class="bi bi-person-gear text-warning me-2"></i> Edit Profil
            </h3>

            {{-- Validasi Error --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Foto Profil --}}
                <div class="mb-4 text-center position-relative">
                    @if(Auth::user()->foto)
                        <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto Profil" 
                             class="rounded-circle mb-2 shadow-sm" 
                             style="width:120px; height:120px; object-fit:cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&size=120&background=0D8ABC&color=fff&rounded=true" 
                             class="rounded-circle mb-2 shadow-sm" alt="Foto Profil">
                    @endif

                    <div class="mt-2">
                        <input type="file" class="form-control form-control-sm" name="foto" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
                    </div>
                </div>

                {{-- Username --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ Auth::user()->username }}" required>
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ Auth::user()->nama }}" required>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                </div>

                {{-- No HP --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ Auth::user()->no_hp }}">
                </div>

                {{-- Alamat --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ Auth::user()->alamat }}</textarea>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('user.edit-password') }}" class="btn btn-outline-warning rounded-pill fw-semibold">
                    <i class="bi bi-lock-fill me-1"></i> Ganti Password
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Style --}}
<style>
    body {
        background-color: #f4f4f4;
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
    .btn-outline-warning {
        border-color: #f4b400;
        color: #f4b400;
        transition: 0.25s;
    }
    .btn-outline-warning:hover {
        background-color: #f4b400;
        color: #fff !important;
    }
</style>
@endsection
