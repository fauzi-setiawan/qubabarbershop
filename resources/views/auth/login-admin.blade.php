@extends('layouts.admin.app')

@section('title', 'Login Admin')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">

    <div class="card shadow-sm border-0" style="max-width: 500px; width: 100%; border-radius: 0.8rem; overflow: hidden;">

        {{-- Header --}}
        <div class="text-center px-3 py-3" style="background-color: #ffb347;">
            <h4 class="fw-bold m-0">ADMIN LOGIN</h4>
        </div>

        {{-- Form --}}
        <div class="p-4" style="background-color: #ffe2c6;">
            <h4 class="text-center fw-bold mb-4 text-dark">Masuk Sebagai Admin</h4>

            {{-- Error --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li style="font-size: 0.9rem;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           placeholder="Email" 
                           class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3 position-relative">
                    <input id="password" type="password" name="password" placeholder="Password" 
                           class="form-control @error('password') is-invalid @enderror" required>
                    <span class="position-absolute" 
                          style="top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;" 
                          onclick="togglePassword()">
                        <i id="eye-icon" class="bi bi-eye"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>

                {{-- Button --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn fw-bold text-dark" style="background-color: #ffb347;">
                        LOGIN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script toggle password --}}
@push('scripts')
<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        password.type = "password";
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endpush
@endsection
