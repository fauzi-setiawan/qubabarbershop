@extends('layouts.admin.app')

@section('content')
<style>
    body {
        background-color: #1E1E1E;
        color: #FFF;
    }

    .card {
        background-color: #2B2B2B;
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }

    .table {
        color: #F5F5F5;
        border-color: #3A3A3A;
        background-color: #1E1E1E;
    }

    .table thead {
        background-color: #C19A6B;
        color: #1E1E1E;
        font-weight: bold;
    }

    .table tbody tr:hover {
        background-color: rgba(193,154,107,0.15);
        transition: 0.3s;
    }

    .btn-success {
        background-color: #C19A6B;
        border: none;
        color: #1E1E1E;
        font-weight: 600;
    }

    .btn-success:hover {
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

    .btn-outline-light {
        border-color: #C19A6B;
        color: #C19A6B;
    }

    .btn-outline-light:hover {
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

    input.form-control {
        background-color: #2B2B2B;
        color: #FFF;
        border: 1px solid #C19A6B;
        padding: 8px;
    }

    input.form-control:focus {
        background-color: #252525;
        border-color: #FFD700;
        box-shadow: 0 0 6px rgba(193,154,107,0.6);
        color: #fff;
    }

    ::placeholder {
        color: #d3c5b0 !important;
        opacity: 1;
    }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="m-0"><i class="bi bi-box-seam me-2"></i>Data Barang</h3>
        <!-- Tombol Export PDF -->
        <a href="{{ route('admin.barang-pdf') }}" class="btn btn-outline-light">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <!-- Tabel Data Barang -->
        <table class="table table-bordered table-hover text-center align-middle">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama Barang</th>
                    <th>Brand</th>
                    <th style="width: 10%;">Stok</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->brand }}</td>
                    <td>{{ $barang->stok }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Edit -->
                            <a href="{{ route('admin.barang.edit', $barang->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <!-- Hapus -->
                            <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus barang ini?')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data barang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tombol bawah -->
        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
            <a href="{{ route('admin.barang.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Barang
            </a>
        </div>
    </div>
</div>
@endsection
