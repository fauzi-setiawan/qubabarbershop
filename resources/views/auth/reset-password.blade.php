@extends('layouts.user.app')
@section('title', 'Ganti Password')

@section('content')
<div class="container py-5" style="max-width: 500px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4 text-dark">
                <i class="bi bi-lock-fill text-warning me-2"></i> Ganti Password
            </h3>

            {{-- Validasi error --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Lama</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                {{-- Password Baru --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Baru</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('user.profile') }}" class="btn btn-coolgray rounded-pill px-4">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Simpan Password
                    </button>
                </div>

                <div class="mt-3 text-center">
                    <a href="{{ route('user.password.request') }}" class="text-muted small">Lupa Password?</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Style Tambahan --}}
<style>
    body {
        background-color: #f4f4f4;
    }
    /* Tombol emas */
    .btn-warning {
        background-color: #f4b400;
        border: none;
        transition: 0.3s;
    }
    .btn-warning:hover {
        background-color: #d99a00;
        color: #fff !important;
    }
    /* Tombol abu-abu dingin */
    .btn-coolgray {
        background-color: #adb5bd;
        color: #fff;
        border: none;
        transition: 0.3s;
    }
    .btn-coolgray:hover {
        background-color: #6c757d;
        color: #fff;
    }
</style>
@endsection
