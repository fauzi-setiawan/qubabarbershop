@extends('layouts.admin.app')

@section('content')
<style>
    body { background-color: #121212; color: #FFF; }
    h3, h5 { color: #C19A6B; font-weight: 600; }
    .card { 
        background-color: #1E1E1E; 
        border: none; 
        border-radius: 12px; 
        transition: transform 0.2s, box-shadow 0.2s; 
        position: relative; 
        z-index: 0; 
    }
    .card:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 3px 10px rgba(193,154,107,0.5); 
        z-index: 2; 
    }
    .btn-outline-secondary, .btn-outline-danger, .btn-outline-light { 
        color: #C19A6B; 
        border-color: #C19A6B; 
    }
    .btn-outline-secondary:hover, .btn-outline-danger:hover, .btn-outline-light:hover { 
        background-color: #C19A6B; 
        color: #1E1E1E; 
    }
    .badge.bg-success { background-color: #27AE60; }
    .badge.bg-secondary { background-color: #3C3C3C; }
    table.table { background-color: #1E1E1E; }
    table.table th, table.table td { color: #000 !important; vertical-align: middle; }
    .form-select, .form-control { 
        background-color: #2B2B2B; 
        color: #FFF; 
        border: 1px solid #C19A6B; 
    }
    .form-select:focus, .form-control:focus { 
        border-color: #C19A6B; 
        box-shadow: 0 0 6px rgba(193,154,107,0.6); 
        color: #c19a6b; 
    }
    ::placeholder { color: #d3c5b0 !important; opacity: 1; }
    button[data-bs-toggle="collapse"] { 
        position: relative; 
        z-index: 1; 
        transition: all 0.15s ease-in-out; 
    }
    button[data-bs-toggle="collapse"]:hover { 
        box-shadow: 0 0 8px rgba(193,154,107,0.35); 
    }
    .collapse { position: relative; z-index: 2; }
</style>

<h3 class="mb-4"><i class="bi bi-bookmark-check me-2"></i>Laporan Pesanan</h3>

<!-- Ringkasan total pesanan dan pendapatan -->
<div class="row mb-4 g-3">
    <div class="col-md-6">
        <div class="card shadow-sm p-3 text-center">
            <h5>Total Pesanan Selesai</h5>
            <h3>{{ $totalPesanan }}</h3>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm p-3 text-center">
            <h5>Total Pendapatan</h5>
            <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>
    </div>
</div>

<!-- Tombol reset tampilan ke default -->
<div class="d-flex justify-content-end me-2 mt-3 mb-3">
    <a href="{{ route('admin.laporan.pesanan') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1" title="Reset">
        <i class="bi bi-arrow-counterclockwise"></i>
        <span>Reset</span>
    </a>
</div>

<!-- Pilihan mode laporan -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div class="d-flex align-items-center gap-3">
        <form method="GET" action="{{ route('admin.laporan.pesanan') }}" class="d-flex align-items-center gap-2 mb-0">
            <select name="mode" class="form-select w-auto" onchange="this.form.submit()">
                <option value="daily" {{ $mode=='daily'?'selected':'' }}>Harian</option>
                <option value="weekly" {{ $mode=='weekly'?'selected':'' }}>Mingguan</option>
                <option value="monthly" {{ $mode=='monthly'?'selected':'' }}>Bulanan</option>
            </select>
        </form>
        @php \Carbon\Carbon::setLocale('id'); @endphp
        <div style="font-weight:500; color:#C19A6B; background-color:#2B2B2B; padding:3px 8px; border-radius:6px;">
            @if($mode === 'daily')
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            @elseif($mode === 'weekly')
                {{ \Carbon\Carbon::now()->translatedFormat('F, Y') }}
            @else
                {{ \Carbon\Carbon::now()->format('Y') }}
            @endif
        </div>
    </div>
@php
    \Carbon\Carbon::setLocale('id');
    $hariMap = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
@endphp

<!-- Buat Export -->
    <div class="d-flex align-items-center gap-2">
        <form action="{{ $mode === 'daily' ? route('admin.laporan.export.daily') : route('admin.laporan.excel') }}" method="GET" class="mb-0">
            <input type="hidden" name="mode" value="{{ $mode }}">
            <button class="btn btn-outline-light d-flex align-items-center gap-1" type="submit">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
            </button>
        </form>

        <form action="{{ route('admin.laporan.pdf', ['mode' => $mode]) }}" method="GET" class="mb-0">
            <input type="hidden" name="mode" value="{{ $mode }}">
            <button class="btn btn-outline-light d-flex align-items-center gap-1" type="submit">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </button>
        </form>
    </div>
</div>

<!-- Detail pesanan -->
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Detail Pesanan</h5>

        <!-- Tampilan harian -->
        @if($mode === 'daily')
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Nama Customer</th>
                        <th>Layanan</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Waktu Kunjungan</th>
                        <th>Status</th>
                        <th>Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $i => $p)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $p->kode_booking ?? '-' }}</td>
                            <td>{{ $p->user->nama ?? '-' }}</td>
                            <td>
                                {{ $p->layanan->nama_layanan ?? '-' }}
                                @if(isset($p->layananTambahan) && $p->layananTambahan->count())
                                    + {{ $p->layananTambahan->pluck('nama_layanan')->join(', ') }}
                                @endif
                            </td>
                            <td>Rp {{ number_format($p->total,0,',','.') }}</td>
                            <td>{{ $p->metode_pembayaran ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->waktu_kunjungan)->format('d-m-Y H:i') }}</td>
                            <td>
                                @if($p->status==='selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($p->total_bayar ?? $p->total,0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada pesanan hari ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @elseif($mode === 'weekly')
            {{-- Tampilan mingguan --}}
            @php
                $currentMonth = \Carbon\Carbon::now()->month;
                $currentYear = \Carbon\Carbon::now()->year;
                $startMonth = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
                $endMonth = $startMonth->copy()->endOfMonth();
                $weekRanges = [];
                $tempStart = $startMonth->copy();
                while ($tempStart->lte($endMonth)) {
                    $tempEnd = $tempStart->copy()->addDays(6);
                    if ($tempEnd->gt($endMonth)) $tempEnd = $endMonth->copy();
                    $weekRanges[] = [$tempStart->copy(), $tempEnd->copy()];
                    $tempStart = $tempEnd->copy()->addDay();
                }
            @endphp

            @foreach ($weekRanges as $index => [$weekStart, $weekEnd])
                @php
                    $pesananMinggu = $pesanans->filter(fn($p) => \Carbon\Carbon::parse($p->waktu_kunjungan)
                        ->between($weekStart->copy()->startOfDay(), $weekEnd->copy()->endOfDay()));
                    $jumlahPesanan = $pesananMinggu->count();
                    $totalMinggu = $pesananMinggu->sum('total');
                @endphp

                <div class="mb-3">
                    <button class="btn btn-light w-100 d-flex justify-content-between align-items-center shadow-sm"
                            type="button" data-bs-toggle="collapse" data-bs-target="#minggu{{ $index }}" aria-expanded="false">
                        <div>
                            <strong>Minggu {{ $index + 1 }}</strong>
                            <div style="font-size:0.85rem;">
                                ({{ $weekStart->format('d') }}–{{ $weekEnd->format('d M') }}) — {{ $jumlahPesanan }} pesanan — Rp {{ number_format($totalMinggu, 0, ',', '.') }}
                            </div>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="collapse mt-2" id="minggu{{ $index }}">
                        @foreach ($hariMap as $hariIndex => $hariNama)
                            @php
                                $pesananHari = $pesananMinggu->filter(fn($p) => \Carbon\Carbon::parse($p->waktu_kunjungan)->dayOfWeek == (($hariIndex + 1) % 7));
                                $jumlahHari = $pesananHari->count();
                                $totalHari = $pesananHari->sum('total');
                            @endphp

                            <div class="ms-3 mb-2 p-2" style="background-color:#2B2B2B; border-radius:6px;">
                                <button class="btn btn-outline-secondary w-100 text-start mb-1"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#hari{{ $index }}{{ $hariIndex }}">
                                    {{ $hariNama }} — {{ $jumlahHari }} pesanan — Rp {{ number_format($totalHari, 0, ',', '.') }}
                                </button>

                                <div class="collapse ms-3" id="hari{{ $index }}{{ $hariIndex }}">
                                    @if($pesananHari->isEmpty())
                                        <div class="text-muted ps-2">Tidak ada pesanan hari ini.</div>
                                    @else
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Booking</th>
                                                    <th>Nama Customer</th>
                                                    <th>Layanan</th>
                                                    <th>Total</th>
                                                    <th>Metode</th>
                                                    <th>Waktu Kunjungan</th>
                                                    <th>Status</th>
                                                    <th>Total Bayar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pesananHari as $i => $p)
                                                    <tr>
                                                        <td>{{ $i+1 }}</td>
                                                        <td>{{ $p->kode_booking ?? '-' }}</td>
                                                        <td>{{ $p->user->nama ?? '-' }}</td>
                                                        <td>
                                                            {{ $p->layanan->nama_layanan ?? '-' }}
                                                            @if(isset($p->layananTambahan) && $p->layananTambahan->count())
                                                                + {{ $p->layananTambahan->pluck('nama_layanan')->join(', ') }}
                                                            @endif
                                                        </td>
                                                        <td>Rp {{ number_format($p->total,0,',','.') }}</td>
                                                        <td>{{ $p->metode_pembayaran ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($p->waktu_kunjungan)->format('d-m-Y H:i') }}</td>
                                                        <td>
                                                            @if($p->status==='selesai')
                                                                <span class="badge bg-success">Selesai</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>Rp {{ number_format($p->total_bayar ?? $p->total,0,',','.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        @else
            {{-- Tampilan bulanan --}}
            @forelse($data as $bIndex => $bulan)
                <div class="mb-3">
                    <button class="btn btn-light w-100 d-flex justify-content-between align-items-center shadow-sm"
                            type="button" data-bs-toggle="collapse" data-bs-target="#bulan{{ $bIndex }}" aria-expanded="false">
                        <div>
                            <strong>{{ $bulan['nama_bulan'] ?? 'Bulan Ini' }}</strong>
                            <div style="font-size:0.85rem;">
                                {{ $bulan['jumlah_pesanan'] ?? 0 }} pesanan — Rp {{ number_format($bulan['total_pendapatan'] ?? 0,0,',','.') }}
                            </div>
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="collapse mt-2" id="bulan{{ $bIndex }}">
                        @if(isset($bulan['minggu']))
                            @foreach($bulan['minggu'] as $wIndex => $minggu)
                                <div class="ms-3 mb-2 p-2" style="background-color:#2B2B2B; border-radius:6px;">
                                    <button class="btn btn-outline-light w-100 text-start mb-1" 
                                            type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#monthlyMinggu{{ $bIndex }}{{ $wIndex }}">
                                        <strong>{{ $minggu['label'] ?? '-' }}</strong> — {{ $minggu['jumlah_pesanan'] ?? 0 }} pesanan — Rp {{ number_format($minggu['total_pendapatan'] ?? 0,0,',','.') }}
                                    </button>

                                    <div class="collapse ms-3 mb-2" id="monthlyMinggu{{ $bIndex }}{{ $wIndex }}">
                                        @foreach($minggu['items'] as $day => $itemsHari)
                                            @php $itemsHari = collect($itemsHari); @endphp
                                            <button class="btn btn-outline-secondary w-100 text-start mb-1" 
                                                    type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#monthlyHari{{ $bIndex }}{{ $wIndex }}{{ $day }}">
                                                {{ $hariMap[$day] ?? 'Hari' }} — {{ $itemsHari->count() }} pesanan — Rp {{ number_format($itemsHari->sum('total'),0,',','.') }}
                                            </button>

                                            <div class="collapse ms-3 mb-2" id="monthlyHari{{ $bIndex }}{{ $wIndex }}{{ $day }}">
                                                <table class="table table-bordered table-striped mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Kode Booking</th>
                                                            <th>Nama Customer</th>
                                                            <th>Layanan</th>
                                                            <th>Total</th>
                                                            <th>Metode</th>
                                                            <th>Waktu Kunjungan</th>
                                                            <th>Status</th>
                                                            <th>Total Bayar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($itemsHari as $i => $p)
                                                            <tr>
                                                                <td>{{ $i+1 }}</td>
                                                                <td>{{ $p->kode_booking ?? '-' }}</td>
                                                                <td>{{ $p->user->nama ?? '-' }}</td>
                                                                <td>
                                                                    {{ $p->layanan->nama_layanan ?? '-' }}
                                                                    @if(isset($p->layananTambahan) && $p->layananTambahan->count())
                                                                        + {{ $p->layananTambahan->pluck('nama_layanan')->join(', ') }}
                                                                    @endif
                                                                </td>
                                                                <td>Rp {{ number_format($p->total,0,',','.') }}</td>
                                                                <td>{{ $p->metode_pembayaran ?? '-' }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($p->waktu_kunjungan)->format('d-m-Y H:i') }}</td>
                                                                <td>
                                                                    @if($p->status==='selesai')
                                                                        <span class="badge bg-success">Selesai</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>Rp {{ number_format($p->total_bayar ?? $p->total,0,',','.') }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="9" class="text-center">Tidak ada pesanan</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-3">Tidak ada data</div>
            @endforelse
        @endif
    </div>
</div>
@endsection
