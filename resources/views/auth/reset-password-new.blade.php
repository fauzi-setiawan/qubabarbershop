@extends('layouts.user.app')
@section('title', 'Reset Password Baru')

@section('content')
<div class="container py-5" style="max-width: 450px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4 text-dark">
                <i class="bi bi-arrow-repeat text-warning me-2"></i> Reset Password Baru
            </h3>

            @if($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.password.reset') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-warning text-dark fw-semibold w-100 rounded-pill">
                    <i class="bi bi-check-circle me-1"></i> Simpan Password
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Style tambahan --}}
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

    .card {
        background-color: #fff;
        border-radius: 1rem;
    }
</style>
@endsection
