@extends('layouts.user.app')

@section('title', 'Register')

@section('content')
<div class="d-flex justify-content-center align-items-start py-5" 
     style="min-height: calc(138px);">

    <div class="card shadow-lg border-0" 
         style="max-width: 420px; width: 100%; border-radius: 1rem; overflow: hidden;">

        {{-- Header --}}
        <div class="text-center px-3 py-2" style="background-color: #d4af37;">
            <h5 class="fw-bold m-0 text-white">QUBA BARBERSHOP</h5>
        </div>

        {{-- Form Section --}}
        <div class="p-4" style="background-color: #ffffff;">
            <h4 class="text-center fw-bold mb-4" style="color:#d4af37;">REGISTER</h4>

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

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                {{-- Nama --}}
                <div class="mb-3">
                    <input type="text" name="nama" value="{{ old('nama') }}" 
                           placeholder="Nama Lengkap" 
                           class="form-control @error('nama') is-invalid @enderror"
                           style="border-radius:0.5rem; border:1px solid #d4af37; background-color:#fff; color:#333;" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="mb-3">
                    <input type="text" name="username" value="{{ old('username') }}" 
                           placeholder="Username" 
                           class="form-control @error('username') is-invalid @enderror"
                           style="border-radius:0.5rem; border:1px solid #d4af37; background-color:#fff; color:#333;" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <input type="email" name="email" value="{{ old('email') }}" 
                           placeholder="Email" 
                           class="form-control @error('email') is-invalid @enderror"
                           style="border-radius:0.5rem; border:1px solid #d4af37; background-color:#fff; color:#333;" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div class="mb-3">
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" 
                           placeholder="Nomor HP" 
                           class="form-control @error('no_hp') is-invalid @enderror"
                           style="border-radius:0.5rem; border:1px solid #d4af37; background-color:#fff; color:#333;" required>
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3 position-relative">
                    <input type="password" name="password" id="password" placeholder="Password"
                           class="form-control @error('password') is-invalid @enderror"
                           style="border-radius:0.5rem; border:1px solid #d4af37; background-color:#fff; color:#333;" required>

                    <i class="bi bi-eye position-absolute"
                       style="top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer; color:#d4af37;"
                       onclick="togglePassword('password', this)"></i>

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-4 position-relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           style="border-radius:0.5rem; border:1px solid #d4af37; background-color:#fff; color:#333;" required>

                    <i class="bi bi-eye position-absolute"
                       style="top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer; color:#d4af37;"
                       onclick="togglePassword('password_confirmation', this)"></i>

                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Daftar --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn fw-bold text-white"
                            style="background-color: #d4af37; border-radius:0.5rem; box-shadow:0 4px 10px rgba(0,0,0,0.3);">
                        DAFTAR
                    </button>
                </div>

                {{-- Link Login --}}
                <div class="text-center">
                    <a href="{{ route('user.login.form') }}" class="text-decoration-none small text-dark">
                        Sudah punya akun? <span style="color: #d4af37; font-weight: 600;">Login</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script toggle password --}}
<script>
function togglePassword(inputId, iconElem) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        iconElem.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        iconElem.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endsection
