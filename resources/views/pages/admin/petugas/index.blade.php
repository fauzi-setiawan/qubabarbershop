@extends('layouts.admin.app')

@section('title', 'Data Petugas')

@section('content')
<style>
    body {
        background-color: #1E1E1E;
        color: #FFF;
        font-family: 'Poppins', sans-serif;
    }

    /* header judul */
    h3.fw-bold {
        color: #C19A6B;
        letter-spacing: 0.5px;
    }

    /* teks kecil di bawah judul */
    small.text-muted {
        color: #B0B0B0 !important;
    }

    /* tombol tambah petugas */
    .btn-add {
        background-color: #C19A6B;
        border: none;
        color: #1E1E1E;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        transition: 0.3s;
    }

    .btn-add:hover {
        background-color: #B8860B;
        color: #FFF;
    }

    /* tombol edit & hapus */
    .btn-edit {
        background-color: #F4B942;
        border: none;
        color: #1E1E1E;
        font-weight: 600;
        padding: 0.3rem 0.7rem;
    }

    .btn-edit:hover {
        background-color: #DAA520;
        color: #FFF;
    }

    .btn-delete {
        background-color: #E74C3C;
        border: none;
        color: #FFF;
        font-weight: 600;
        padding: 0.3rem 0.7rem;
    }

    .btn-delete:hover {
        background-color: #C0392B;
    }

    /* wadah tabel */
    .table-container {
        background-color: #2B2B2B;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }

    /* style tabel utama */
    .table {
        color: #F5F5F5;
        border-color: #3A3A3A;
        background-color: #1E1E1E;
    }

    /* header tabel */
    .table thead {
        background-color: #C19A6B;
        color: #1E1E1E;
        font-weight: bold;
    }

    /* efek hover pas baris diarahin */
    .table tbody tr:hover {
        background-color: rgba(193,154,107,0.15);
        transition: 0.3s;
    }

    table td, table th {
        vertical-align: middle;
        text-align: center;
    }

    /* toggle switch buat status */
    .switch {
        position: relative;
        display: inline-block;
        width: 45px;
        height: 22px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #555;
        transition: 0.4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px; width: 16px;
        left: 3px; bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #C19A6B;
    }

    input:checked + .slider:before {
        transform: translateX(22px);
    }
</style>

{{-- header atas + tombol tambah --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0"><i class="bi bi-person-badge me-2"></i>Data Petugas</h3>
        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Kelola petugas aktif dan nonaktif</small>
    </div>
    <a href="{{ route('admin.petugas.create') }}" class="btn btn-add">
        <i class="bi bi-plus-circle me-1"></i> Tambah Petugas
    </a>
</div>

{{-- tabel daftar petugas --}}
<div class="table-container">
    <table class="table table-bordered table-hover align-middle">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 25%">Nama Petugas</th>
                <th style="width: 35%">Alamat</th>
                <th style="width: 15%">Daftar Hadir</th>
                <th style="width: 20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- looping data petugas --}}
            @forelse ($petugas as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->nama_petugas }}</td>
                <td>{{ $p->alamat }}</td>
                <td>
                    {{-- toggle aktif/nonaktif --}}
                    <label class="switch">
                        <input type="checkbox" class="toggle-status"
                               data-id="{{ $p->id_petugas }}"
                               {{ $p->status == 'aktif' ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td>
                    {{-- tombol edit --}}
                    <a href="{{ route('admin.petugas.edit', $p->id_petugas) }}" class="btn btn-edit btn-sm">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    {{-- tombol hapus --}}
                    <form action="{{ route('admin.petugas.destroy', $p->id_petugas) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus petugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-delete btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            {{-- kalau belum ada data --}}
            <tr>
                <td colspan="5" class="text-muted">Belum ada petugas terdaftar</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- script buat ubah status toggle --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggles = document.querySelectorAll('.toggle-status');

    // looping semua toggle
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const petugasId = this.dataset.id;
            const statusBaru = this.checked ? 'aktif' : 'nonaktif';

            // kirim data status ke backend pake fetch
            fetch(`/admin/petugas/${petugasId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: statusBaru })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log('status petugas diupdate:', data.status);
                } else {
                    alert('gagal update status');
                }
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
@endsection
