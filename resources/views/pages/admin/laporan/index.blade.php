@extends('layouts.admin.app')

@section('content')
<style>
    body {
        background-color: #121212;
        color: #FFF;
    }

    .card {
        background-color: #1E1E1E;
        border: none;
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.6);
    }

    .card i {
        color: #C19A6B;
    }

    .card h5 {
        color: #C19A6B;
        margin-top: 12px;
        font-weight: 600;
    }

    a.text-decoration-none {
        text-decoration: none;
    }
</style>

<div class="container py-5">
    <h3 class="mb-4 fw-bold" style="color:#C19A6B;">
        <i class="bi bi-file-earmark-text me-2"></i>Laporan
    </h3>

    <div class="row g-4">
        <div class="col-md-6">
            <a href="{{ route('admin.laporan.pesanan') }}" class="text-decoration-none">
                <div class="card shadow-sm text-center p-5">
                    <i class="bi bi-bookmark-check" style="font-size: 3rem;"></i>
                    <h5>Laporan Pesanan</h5>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('admin.barang.index') }}" class="text-decoration-none">
                <div class="card shadow-sm text-center p-5">
                    <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                    <h5>Laporan Barang</h5>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
