@extends('layouts.user.app')

@section('title', 'Login User')

@section('content')
<div class="d-flex justify-content-center align-items-start py-5" style="min-height: calc(138px);">

    <div class="card shadow-lg border-0" 
         style="max-width: 420px; width: 100%; border-radius: 1rem; overflow: hidden;">

        {{-- Header Logo + Title --}}
        <div class="d-flex justify-content-between align-items-center px-3 py-2" 
             style="background-color: #d4af37;">
            <img src="{{ asset('image/logoo.png') }}" alt="Logo" style="width:50px; height:auto;">
            <h5 class="fw-bold m-0 text-white">QUBA BARBERSHOP</h5>
            <img src="{{ asset('image/logoo.png') }}" alt="Logo" style="width:50px; height:auto;">
        </div>

        {{-- Form Section --}}
        <div class="p-4" style="background-color: #ffffff;">
            <h4 class="text-center fw-bold mb-4" style="color:#d4af37;">LOGIN</h4>

{{-- Error Validation --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show py-2 shadow-sm" role="alert">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li style="font-size: 0.9rem;">{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Success Message --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 shadow-sm" role="alert">
        <p class="mb-0" style="font-size: 0.9rem;">{{ session('success') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

            <form method="POST" action="{{ route('user.login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <input id="email" type="text" name="email" value="{{ old('email') }}" 
                           placeholder="Email" 
                           class="form-control @error('email') is-invalid @enderror"
                           style="border-radius:0.5rem; border:1px solid #d4af37; background-color:#fff; color:#333;" 
                           required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3 position-relative">
                    <input id="password" type="password" name="password" placeholder="Password" 
                           class="form-control @error('password') is-invalid @enderror"
                           style="border-radius:0.5rem; border:1px solid #d4af37; background-color:#fff; color:#333;" 
                           required>
                    <span class="position-absolute" 
                          style="top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;" 
                          onclick="togglePassword('password', 'eye-icon')">
                        {{-- Hanya 1 ikon --}}
                        <i id="eye-icon" class="bi bi-eye" style="color:#000;"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-dark" for="remember">Ingat saya</label>
                </div>

                {{-- Button Login --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn fw-bold text-white"
                            style="background-color: #d4af37; border-radius:0.5rem; box-shadow:0 4px 10px rgba(0,0,0,0.3);">
                        LOGIN
                    </button>
                </div>

                {{-- Register + Forgot Password --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('register') }}" class="text-decoration-none small text-dark">
                        Belum punya akun? <span class="text-gold fw-bold" style="color:#d4af37;">Daftar</span>
                    </a>
                    <a href="{{ route('user.password.request') }}" class="small text-danger">
                        Forgot password
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script toggle password --}}
<script>
function togglePassword(inputId, iconId) {
    const password = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (password.type === "password") {
        password.type = "text";
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        password.type = "password";
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endsection
