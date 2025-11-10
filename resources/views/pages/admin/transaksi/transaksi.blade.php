@extends('layouts.admin.app')

@section('title', 'Transaksi')

@section('content')
<style>
body { background-color: #1E1E1E; color: #FFF; font-family: 'Poppins', sans-serif; }
h3.fw-bold { color: #C19A6B; letter-spacing: 0.5px; }
.card { background-color: #2B2B2B; border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.4); }
.text-muted { color: #d3c5b0 !important; }

/* ALERT */
.alert-success { background-color: #27AE60; border: none; color: #fff; }
.alert-danger { background-color: #E74C3C; border: none; color: #fff; }

/* FORM INPUT */
input.form-control {
    background-color: #2B2B2B;
    border: 1px solid #C19A6B;
    color: #FFF;
    transition: box-shadow 0.3s ease;
}
input.form-control:focus {
    background-color: #252525;
    border-color: #FFD700;
    color: #FFF;
    box-shadow: 0 0 8px rgba(255, 215, 0, 0.7); 
}
input.form-control::placeholder { color: #d3c5b0 !important; opacity: 0.8; }

/* BUTTONS */
.btn-primary { background-color: #C19A6B; border: none; color: #1E1E1E; font-weight: 600; }
.btn-primary:hover { background-color: #B8860B; color: #FFF; }
.btn-secondary { background-color: #444; border: none; color: #FFF; }
.btn-secondary:hover { background-color: #666; }
.btn-success { background-color: #27AE60; border: none; }
.btn-danger { background-color: #E74C3C; border: none; }
.btn-outline-primary { border-color: #C19A6B; color: #C19A6B; }
.btn-outline-primary:hover { background-color: #C19A6B; color: #1E1E1E; }

/* TABLE */
.table { color: #F5F5F5; border-color: #3A3A3A; background-color: #1E1E1E; }
.table thead { background-color: #C19A6B; color: #1E1E1E; font-weight: bold; }
.table tbody tr:hover { background-color: rgba(193,154,107,0.15); transition: 0.3s; }
.table td, .table th { vertical-align: middle; }

/* SELECT */
select.form-select { border: 1px solid #C19A6B; color: #FFF; background-color: #2B2B2B; transition: background-color 0.3s ease; }
select.form-select option { color: #000; background-color: #FFF; }

/* MODAL */
.modal-content { background-color: #2B2B2B; color: #FFF; border-radius: 10px; }
.modal-header { background-color: #C19A6B; color: #1E1E1E; }
.form-label { color: #C19A6B; }
.input-group-text { background-color: #C19A6B; color: #1E1E1E; cursor: pointer; }
</style>

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0"><i class="bi bi-receipt-cutoff me-2"></i> Data Transaksi</h3>
        <small class="text-muted"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</small>
    </div>
</div>

{{-- ALERT --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show text-center shadow-sm" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show text-center shadow-sm" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- FORM PENCARIAN -->
<div class="mb-3">
    <form action="{{ route('admin.transaksi.index') }}" method="GET" class="d-flex justify-content-end align-items-center gap-2">
        <input type="text" name="search" class="form-control w-25 shadow-sm" placeholder="Cari Nama / kode booking..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary btn-sm shadow-sm" title="Cari"><i class="bi bi-search"></i></button>
        @if(request('search'))
        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-primary btn-sm shadow-sm" title="Reset pencarian"><i class="bi bi-x-circle"></i></a>
        @endif
    </form>
</div>

<!-- TABLE TRANSAKSI -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status Bayar</th>
                        <th>Status</th>
                        <th>Waktu Kunjungan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $i => $t)
                    <tr>
                        <td>{{ $i + $transaksis->firstItem() }}</td>
                        <td class="fw-semibold">{{ $t->kode_booking }}</td>
                        <td>{{ $t->user->nama ?? '-' }}</td>
                        <td>
                            @if($t->layanan)
                                <div class="fw-semibold">{{ $t->layanan->nama_layanan }}</div>
                            @endif
                            @if($t->layananTambahan && $t->layananTambahan->count() > 0)
                                <small class="text-success d-block">
                                    @foreach($t->layananTambahan as $lt)
                                        + {{ $lt->nama_layanan }}@if(!$loop->last), @endif
                                    @endforeach
                                </small>
                            @endif
                        </td>
                        <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td><span class="badge bg-warning text-dark">{{ strtoupper($t->metode_pembayaran) }}</span></td>

                        <td>
                            <form action="{{ route('admin.transaksi.update', $t->id_pesanan) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <select name="status_pembayaran" onchange="this.form.submit()" class="form-select form-select-sm text-center">
                                    <option value="belum_bayar" {{ $t->status_pembayaran == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="sudah_bayar" {{ $t->status_pembayaran == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                </select>
                            </form>
                        </td>

                        <td>
                            <form action="{{ route('admin.transaksi.update', $t->id_pesanan) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm text-center">
                                    <option value="pending" {{ $t->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="disetujui" {{ $t->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="ditolak" {{ $t->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="selesai" {{ $t->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        </td>

                        <td>{{ \Carbon\Carbon::parse($t->waktu_kunjungan)->translatedFormat('d M Y, H:i') }}</td>

                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                @php
                                    $printActive = $t->status == 'selesai';
                                @endphp
                                @if($t->metode_pembayaran == 'cash')
                                    <button type="button" class="btn btn-sm btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalStruk{{ $t->id_pesanan }}" {{ $printActive ? '' : 'disabled' }}>
                                        <i class="bi bi-printer-fill"></i>
                                    </button>
                                @else
                                    <a href="{{ $printActive ? route('admin.transaksi.print', $t->id_pesanan) : '#' }}" target="_blank" class="btn btn-sm btn-success shadow-sm {{ $printActive ? '' : 'disabled' }}">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                @endif

                                <form action="{{ route('admin.transaksi.destroy', $t->id_pesanan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger shadow-sm">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL CASH -->
                    <div class="modal fade" id="modalStruk{{ $t->id_pesanan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Input Uang Bayar - {{ $t->kode_booking }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Total Bayar: Rp {{ number_format($t->total,0,',','.') }}</label>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="number" min="{{ $t->total }}" id="uangBayar{{ $t->id_pesanan }}" class="form-control" placeholder="Masukkan jumlah uang bayar">
                                        <span class="input-group-text" id="btnKalkulator{{ $t->id_pesanan }}"><i class="bi bi-calculator-fill"></i></span>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kembalian:</label>
                                        <input type="text" id="kembalian{{ $t->id_pesanan }}" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="#" target="_blank" id="btnPrint{{ $t->id_pesanan }}" class="btn btn-success">Cetak Struk</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Belum ada transaksi hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $transaksis->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Warna select
    document.querySelectorAll('select.form-select').forEach(select => {
        updateSelectColor(select);
        select.addEventListener('change', () => updateSelectColor(select));
    });

    function updateSelectColor(select) {
        select.classList.remove('bg-success','bg-warning','bg-danger','bg-info','bg-secondary','text-dark','text-white');
        const val = select.value;
        if(val === 'sudah_bayar' || val === 'selesai') select.classList.add('bg-success','text-white');
        else if(val === 'belum_bayar') select.classList.add('bg-warning','text-dark');
        else if(val === 'disetujui') select.classList.add('bg-info','text-dark');
        else if(val === 'ditolak') select.classList.add('bg-danger','text-white');
        else if(val === 'pending') select.classList.add('bg-secondary','text-white');
    }

    // Kalkulator kembalian & cetak + format otomatis
    @foreach($transaksis as $t)
    const total{{ $t->id_pesanan }} = {{ $t->total }};
    const btnCalc{{ $t->id_pesanan }} = document.getElementById('btnKalkulator{{ $t->id_pesanan }}');
    const inputBayar{{ $t->id_pesanan }} = document.getElementById('uangBayar{{ $t->id_pesanan }}');
    const kembalian{{ $t->id_pesanan }} = document.getElementById('kembalian{{ $t->id_pesanan }}');
    const btnPrint{{ $t->id_pesanan }} = document.getElementById('btnPrint{{ $t->id_pesanan }}');

    // Format input otomatis
    inputBayar{{ $t->id_pesanan }}.addEventListener('input', function(e){
        let value = e.target.value.replace(/\D/g,''); // hapus semua bukan angka
        e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // tambahkan titik ribuan
    });

    // Hitung kembalian
    btnCalc{{ $t->id_pesanan }}.addEventListener('click', function(){
        let bayarStr = inputBayar{{ $t->id_pesanan }}.value.replace(/\./g,''); // hapus titik
        const bayar = parseInt(bayarStr) || 0;
        const kembali = bayar - total{{ $t->id_pesanan }};
        if(kembali >= 0){
            kembalian{{ $t->id_pesanan }}.value = 'Rp ' + kembali.toLocaleString('id-ID');
            btnPrint{{ $t->id_pesanan }}.href = '{{ route("admin.transaksi.print", $t->id_pesanan) }}?uang_bayar=' + bayar;
        } else {
            kembalian{{ $t->id_pesanan }}.value = 'Uang kurang!';
            btnPrint{{ $t->id_pesanan }}.href = '#';
        }
    });
    @endforeach

});
</script>
@endpush
