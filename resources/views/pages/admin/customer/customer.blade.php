@extends('layouts.admin.app')

@section('title', 'Customer')

@section('content')
<style>
    /* ============================
       TEMA ADMIN BARBERSHOP ELEGAN
       ============================ */

    body {
        background-color: #1E1E1E; /* Latar belakang utama (gelap elegan) */
        color: #FFF; /* Warna teks utama */
        font-family: 'Poppins', sans-serif;
    }

    /* Kartu utama (pembungkus konten) */
    .card {
        background-color: #2B2B2B;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }

    /* Judul Halaman */
    h3.fw-bold {
        color: #C19A6B; /* Warna emas khas barbershop */
        letter-spacing: 0.5px;
    }

    /* ============================
       TABEL CUSTOMER
       ============================ */
    .table {
        color: #F5F5F5; /* Teks putih lembut */
        border-color: #3A3A3A; /* Garis antar sel */
        background-color: #1E1E1E;
    }

    .table thead {
        background-color: #C19A6B; /* Header emas */
        color: #1E1E1E; /* Tulisan kontras di header */
        font-weight: bold;
    }

    .table tbody tr:hover {
        background-color: rgba(193,154,107,0.15); /* Efek hover keemasan */
        transition: 0.3s;
    }

    /* ============================
       TOMBOL
       ============================ */
    .btn-primary {
        background-color: #C19A6B;
        border: none;
        color: #1E1E1E;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #B8860B;
        color: #FFF;
    }

    .btn-outline-secondary {
        border-color: #C19A6B;
        color: #C19A6B;
    }

    .btn-outline-secondary:hover {
        background-color: #C19A6B;
        color: #1E1E1E;
    }

    .btn-danger {
        background-color: #E74C3C;
        border: none;
    }

    .btn-danger:hover {
        background-color: #C0392B;
    }

    /* ============================
       ALERT & PAGINATION
       ============================ */
    .alert-success {
        background-color: #27AE60;
        border: none;
        color: #fff;
    }

    .page-link {
        background-color: #2B2B2B;
        color: #C19A6B;
        border: 1px solid #3A3A3A;
    }

    .page-link:hover, 
    .page-item.active .page-link {
        background-color: #C19A6B;
        color: #1E1E1E;
        border-color: #C19A6B;
    }

    /* ============================
       FORM INPUT
       ============================ */
    input.form-control {
        background-color: #2B2B2B;
        color: #FFF;
        border: 1px solid #C19A6B;
        padding: 10px;
    }

    input.form-control:focus {
        background-color: #252525;
        border-color: #FFD700;
        box-shadow: 0 0 6px rgba(193,154,107,0.6);
        color: #fff;
    }

    ::placeholder {
        color: #d3c5b0 !important; /* Warna placeholder keemasan lembut */
        opacity: 1;
    }
</style>

<!-- ============================
     HEADER DAN ALERT SUKSES
     ============================ -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">
        <i class="bi bi-people-fill me-2"></i>Data Customer
    </h3>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- ============================
     KONTEN UTAMA: CARD CUSTOMER
     ============================ -->
<div class="card">
    <div class="card-body">

        <!-- ===== Form Pencarian ===== -->
        <form action="{{ route('admin.customer.index') }}" method="GET" class="row g-2 mb-3 align-items-center">
            <div class="col-md-6">
                <input type="text" 
                       name="email" 
                       value="{{ request('email') }}" 
                       class="form-control"
                       placeholder="Cari berdasarkan email...">
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary" title="Cari">
                    <i class="bi bi-search"></i>
                </button>
            </div>

            @if(request('email'))
            <div class="col-auto">
                <a href="{{ route('admin.customer.index') }}" class="btn btn-outline-secondary" title="Reset pencarian">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>
            @endif
        </form>

        <!-- ===== Tabel Data Customer ===== -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center" style="table-layout: fixed; width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 4%;">No</th>
                        <th style="width: 15%;">Nama</th>
                        <th style="width: 15%;">Username</th>
                        <th style="width: 22%;">Email</th>
                        <th style="width: 12%;">No HP</th>
                        <th style="width: 8%;">Booking</th>
                        <th style="width: 13%;">Tanggal Daftar</th>
                        <th style="width: 5%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $i => $c)
                    <tr>
                        <td>{{ $i + $customers->firstItem() }}</td>
                        <td>{{ $c->nama }}</td>
                        <td>{{ $c->username }}</td>
                        <td style="word-break: break-all;">{{ $c->email }}</td>
                        <td>{{ $c->no_hp ?? '-' }}</td>
                        <td>{{ $c->bookings_count }}</td>
                        <td>{{ $c->created_at->format('d M Y') }}</td>
                        <td>
                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.customer.destroy', $c->id_user) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus customer ini?')" 
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus Customer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data customer.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ===== Pagination ===== -->
        <div class="mt-3 d-flex justify-content-end">
            {{ $customers->links() }}
        </div>

    </div>
</div>
@endsection
