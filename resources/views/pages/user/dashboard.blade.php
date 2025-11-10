@extends('layouts.user.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-5">

    {{-- Hero Section --}}
    <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
            <!-- Judul hero dengan animasi halus -->
            <h1 class="fw-bold mb-3 slide-fade">
                Tampil Keren di <span class="text-warning">Quba Barbershop</span>
            </h1>

            <!-- Paragraf hero, animasi halus dengan delay -->
            <p class="text-secondary mb-4 slide-fade delay-1">
                Kami siap kasih pelayanan terbaik buat gaya rambutmu. 
                Nikmati pengalaman barbershop modern bareng barber profesional.
            </p>

            @auth
                <!-- Tombol booking kalau user sudah login -->
                <a href="{{ route('user.booking.create') }}" class="btn btn-warning btn-lg rounded-pill px-4 shadow-lg hover-zoom">
                    <i class="bi bi-scissors"></i> Booking Sekarang
                </a>
            @else
                <!-- Tombol login kalau user belum login -->
                <a href="{{ route('user.login.form') }}" class="btn btn-warning btn-lg rounded-pill px-4 shadow-lg hover-zoom">
                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Booking
                </a>
            @endauth
        </div>

        {{-- Robot Animasi --}}
        <div class="col-md-6 text-center fade-in-rotate robot-shift">
            <lottie-player
                src="https://lottie.host/ba6425f6-398c-460f-adbd-98766bcf8180/kiRh7yt7AI.json"
                background="transparent"
                speed="1"
                style="width: 350px; height: 350px;"
                loop
                autoplay>
            </lottie-player>
        </div>
    </div>

    {{-- Info Singkat --}}
    <div class="row mt-5 text-center g-4">
<div class="col-md-6 fade-in-up">
    <!-- Card tempat nyaman -->
    <a href="{{ route('user.layanan') }}" class="text-decoration-none">
        <div class="p-4 bg-white text-dark rounded-4 shadow-sm h-100 border-top border-3 border-warning card-hover">
            <i class="bi bi-house fs-1 text-warning mb-3"></i> <!-- ganti ikon bi-geo-alt jadi bi-house -->
            <h5 class="fw-bold">Tempat Nyaman & Bersih</h5> <!-- ganti judul -->
            <p class="text-muted">Lokasi Quba Barbershop nyaman, bersih, dan strategis buat kamu mampir.</p> <!-- ganti deskripsi -->
        </div>
    </a>
</div>

        <div class="col-md-6 fade-in-up">
            <!-- Card layanan terbaik -->
            <a href="{{ route('user.layanan') }}" class="text-decoration-none">
                <div class="p-4 bg-white text-dark rounded-4 shadow-sm h-100 border-top border-3 border-warning card-hover">
                    <i class="bi bi-stars fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold">Layanan Terbaik</h5>
                    <p class="text-muted">Ramah, cepat, dan hasilnya pasti memuaskan.</p>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Script Lottie --}}
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

{{-- Style tambahan --}}
<style>
    body {
        background-color: #f4f4f4;
        overflow-x: hidden;
    }

    .btn-warning {
        background-color: #f4b400;
        border: none;
        transition: 0.3s;
    }
    .btn-warning:hover {
        background-color: #d99a00;
        transform: scale(1.05);
    }

    .card-hover {
        transition: 0.3s;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    /* Animasi teks slide + fade halus */
    .slide-fade {
        opacity: 0;
        transform: translateY(10px);
        animation: slideFadeIn 0.8s ease-out forwards;
    }

    .slide-fade.delay-1 {
        animation-delay: 0.3s;
    }

    @keyframes slideFadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animasi robot */
    .fade-in-rotate.robot-shift {
        opacity: 0;
        transform: translateX(100px) rotate(-10deg) scale(0.95);
        animation: fadeInRotate 1.2s ease-out forwards;
    }

    @keyframes fadeInRotate {
        to {
            opacity: 1;
            transform: translateX(100px) rotate(0deg) scale(1);
        }
    }

    /* Card info animasi fade-in */
    .fade-in-up {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 1s ease-out forwards;
    }

    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
