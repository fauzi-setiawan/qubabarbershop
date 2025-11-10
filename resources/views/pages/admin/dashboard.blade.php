@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<style>
    body {
        background-color: #121212;
        color: #FFFFFF;
    }

    .card {
        background-color: #1E1E1E;
        border: none;
        border-radius: 12px;
    }

    .card-box {
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
        transition: all 0.2s ease-in-out;
    }

    .card-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.6);
    }

    .bg-gold {
        background-color: #C19A6B; /* Warna barbershop yang sama */
    }

    .bg-blue {
        background-color: #2D89EF;
    }

    .bg-gray {
        background-color: #3C3C3C;
    }

    h3, h5 {
        color: #C19A6B; /* Header Dashboard sama warna data customer */
    }

    p {
        color: #E0E0E0;
        margin-bottom: 0;
    }

    canvas {
        background-color: #1E1E1E;
        border-radius: 8px;
        padding: 10px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </h3>
    <nav aria-label="breadcrumb">
    </nav>
</div>

{{-- Card Atas --}}
<div class="row g-3">
    <div class="col-md-4">
        <a href="{{ route('admin.transaksi.index') }}" class="text-decoration-none">
            <div class="card-box bg-gold text-white p-3 text-center">
                <h2>{{ $totalPesanan }}</h2>
                <p>Total Pesanan <b>Hari Ini</b></p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.layanan.index') }}" class="text-decoration-none">
            <div class="card-box bg-info text-white p-3 text-center">
                <h2>{{ $totalLayanan }}</h2>
                <p>Layanan</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.customer.index') }}" class="text-decoration-none">
            <div class="card-box bg-gray text-white p-3 text-center">
                <h2>{{ $totalCustomer }}</h2>
                <p>Customer</p>
            </div>
        </a>
    </div>
</div>

{{-- Grafik Booking --}}
<div class="mt-4">
    <div class="card shadow-sm p-3">
        <h5 class="fw-bold mb-2">Jumlah Pesanan 7 Hari Terakhir</h5>
        <h3 class="fw-bold text-warning">{{ array_sum($data) }} Pesanan</h3>

        {{-- canvas grafik --}}
        <canvas id="chartPesanan" height="250"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($labels);
const dataPesanan = @json($data);

const data = {
    labels: labels,
    datasets: [{
        label: 'Jumlah Pesanan',
        backgroundColor: '#C19A6B', // warna header sama
        borderRadius: 8,
        data: dataPesanan
    }]
};

const config = {
    type: 'bar',
    data: data,
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                ticks: { color: '#FFFFFF' },
                grid: { color: '#333' }
            },
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, color: '#FFFFFF' },
                grid: { color: '#333' }
            }
        }
    }
};

new Chart(
    document.getElementById('chartPesanan'),
    config
);
</script>
@endpush
