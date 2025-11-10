@extends('layouts.user.app')
@section('title', 'Lupa Password')

@section('content')
<div class="container py-5" style="max-width: 450px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold text-center mb-4 text-dark">
                <i class="bi bi-question-circle text-warning me-2"></i> Lupa Password
            </h3>

            @if(session('success'))
                <div class="alert alert-success rounded-3">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.password.sendOtp') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Email Anda</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-warning text-dark fw-semibold w-100 rounded-pill">
                    <i class="bi bi-envelope me-1"></i> Kirim OTP
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
