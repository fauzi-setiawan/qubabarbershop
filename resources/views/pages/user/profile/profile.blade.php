@extends('layouts.user.app')
@section('title', 'Profil Saya')

@section('content')
<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body text-center p-4">

            {{-- Foto Profil --}}
            <div class="position-relative d-inline-block mb-3">
                @if($user->foto)
                    <img src="{{ Storage::url($user->foto) }}" 
                         alt="Foto Profil" 
                         class="rounded-circle shadow-sm" 
                         style="width:130px;height:130px;object-fit:cover;">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&size=130&background=f4b400&color=fff&rounded=true" 
                         class="rounded-circle shadow-sm" 
                         alt="Avatar">
                @endif

                {{-- Tombol Ganti Foto --}}
                <a href="{{ route('user.edit-profile') }}" 
                   class="btn btn-warning btn-sm rounded-circle position-absolute" 
                   style="bottom: 0; right: 0; border: 2px solid #fff;">
                    <i class="bi bi-pencil-fill text-white"></i>
                </a>
            </div>

            {{-- Username --}}
            <h4 class="fw-bold text-dark mb-4">{{ $user->username }}</h4>

            {{-- Info Profil --}}
            <ul class="list-group list-group-flush text-start mb-4">
                <li class="list-group-item d-flex justify-content-between px-3">
                    <span class="fw-semibold text-secondary">Nama Lengkap</span>
                    <span class="text-dark">{{ $user->nama }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-3">
                    <span class="fw-semibold text-secondary">Email</span>
                    <span class="text-dark">{{ $user->email }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-3">
                    <span class="fw-semibold text-secondary">No HP</span>
                    <span class="text-dark">{{ $user->no_hp ?? '-' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between px-3">
                    <span class="fw-semibold text-secondary">Alamat</span>
                    <span class="text-dark text-end" style="max-width: 60%;">{{ $user->alamat ?? '-' }}</span>
                </li>
            </ul>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between flex-wrap gap-2 mt-4">
                <a href="{{ route('user.edit-profile') }}" class="btn btn-outline-gold fw-semibold rounded-pill px-4">
                    <i class="bi bi-person-lines-fill me-1"></i> Edit Profil
                </a>
                <a href="{{ route('user.edit-password') }}" class="btn btn-warning fw-semibold rounded-pill px-4 text-dark">
                    <i class="bi bi-lock-fill me-1"></i> Ganti Password
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger fw-semibold rounded-pill px-4">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Style --}}
<style>
    body {
        background-color: #f4f4f4;
    }

    .card {
        background-color: #fff;
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

    .btn-outline-gold {
        border: 2px solid #f4b400;
        color: #f4b400;
        background: #fff;
        transition: 0.3s;
    }
    .btn-outline-gold:hover {
        background-color: #f4b400;
        color: #fff !important;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #f1f1f1;
        background: transparent;
    }

    .bi-pencil-fill {
        font-size: 0.9rem;
    }
</style>
@endsection
