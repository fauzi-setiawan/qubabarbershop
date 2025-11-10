@extends('layouts.user.app')

@section('title', 'Buat Booking')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark">
            <i class="bi bi-calendar-plus text-warning me-2"></i> Buat Booking Baru
        </h3>
        <p class="text-muted mb-0">Isi form di bawah untuk membuat jadwal booking</p>
    </div>

    {{-- Form Booking --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form id="formBooking" action="{{ route('user.booking.store') }}" method="POST">
                @csrf

                {{-- Pilih Layanan Utama --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Layanan Utama</label>
                    <select name="id_layanan" id="id_layanan" class="form-select" required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($layanans as $layanan)
                            @if($layanan->kategori == 'utama')
                                <option value="{{ $layanan->id_layanan }}" data-harga="{{ $layanan->harga }}">
                                    {{ $layanan->nama_layanan }} - Rp {{ number_format($layanan->harga,0,',','.') }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- Pilih Layanan Tambahan (Opsional) --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Layanan Tambahan <small class="text-muted">(Opsional)</small></label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($layanans as $layanan)
                            @if($layanan->kategori == 'tambahan')
                                <div class="form-check">
                                    <input class="form-check-input layanan-tambahan" type="checkbox" 
                                           value="{{ $layanan->id_layanan }}" 
                                           data-harga="{{ $layanan->harga }}" 
                                           id="tambahan-{{ $layanan->id_layanan }}" name="layanan_tambahan[]">
                                    <label class="form-check-label" for="tambahan-{{ $layanan->id_layanan }}">
                                        {{ $layanan->nama_layanan }} (+Rp {{ number_format($layanan->harga,0,',','.') }})
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Pilih Capster --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Capster</label>
                    <select name="id_petugas" class="form-select" required>
                        <option value="">-- Pilih Capster --</option>
                        @foreach($petugas as $p)
                            <option value="{{ $p->id_petugas }}">{{ $p->nama_petugas }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilih Tanggal --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                    <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan" class="form-control" required min="{{ now()->format('Y-m-d') }}">
                </div>

                {{-- Pilih Jam --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jam Tersedia</label>
                    <div id="slot-jam" class="d-flex flex-wrap gap-2"></div>
                    <input type="hidden" name="jam_kunjungan" id="jam_kunjungan" required>
                    @error('jam_kunjungan')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Total Bayar --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Total Bayar</label>
                    <div id="totalBayar" class="fw-bold fs-5">Rp 0</div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                {{-- Tombol bawah --}}
                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                    <a href="{{ route('user.booking') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button id="btnSimpanBooking" type="submit" class="btn btn-warning text-dark fw-semibold rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Simpan Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal QRIS --}}
<div class="modal fade" id="qrisModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content text-center p-4 border-0 rounded-4 shadow">
      <h5 class="fw-bold mb-3 text-dark">Scan QRIS untuk Pembayaran</h5>
      <img src="{{ asset('image/qris.jpg') }}" 
           alt="QRIS" 
           class="img-fluid rounded mb-3 shadow-sm w-100"
           style="max-height: 450px; object-fit: contain;">
      <p class="text-bold mb-3">
        Jangan Lupa Untuk screenshot atau Menyimpan Bukti Pembayaran
      </p>
      <button id="btnSudahBayar" type="button" class="btn btn-success w-100 mb-2 rounded-pill">
        <i class="bi bi-whatsapp"></i> Konfirmasi via WhatsApp
      </button>
      <button type="button" class="btn btn-outline-secondary w-100 rounded-pill" data-bs-dismiss="modal">
        Batal
      </button>
    </div>
  </div>
</div>

{{-- Script interaktif --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const slotContainer = document.getElementById("slot-jam");
    const inputJam = document.getElementById("jam_kunjungan");
    const tanggalInput = document.getElementById("tanggal_kunjungan");
    const metode = document.getElementById("metode_pembayaran");
    const form = document.getElementById("formBooking");
    const layananUtama = document.getElementById("id_layanan");
    const layananTambahan = document.querySelectorAll(".layanan-tambahan");
    const totalBayarDisplay = document.getElementById("totalBayar");

    function generateSlots() {
        slotContainer.innerHTML = "";
        inputJam.value = "";
        const tanggalDipilih = tanggalInput.value;
        if (!tanggalDipilih) return;

        const today = new Date().toISOString().split("T")[0];
        const now = new Date();
        const currentTime = now.getHours() * 60 + now.getMinutes();
        const open = 10 * 60;
        const close = 21 * 60;
        const step = 30;

        for (let minutes = open; minutes <= close; minutes += step) {
            const h = String(Math.floor(minutes / 60)).padStart(2, "0");
            const m = String(minutes % 60).padStart(2, "0");
            const jam = `${h}:${m}`;
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "btn btn-outline-gold btn-sm rounded-pill";
            btn.textContent = jam;

            if (tanggalDipilih === today && minutes <= currentTime) {
                btn.classList.add("btn-disabled");
                btn.disabled = true;
            }

            btn.addEventListener("click", function () {
                document.querySelectorAll("#slot-jam button").forEach(b => b.classList.remove("active"));
                btn.classList.add("active");
                inputJam.value = jam;
            });

            slotContainer.appendChild(btn);
        }
    }

    tanggalInput.addEventListener("change", generateSlots);

    // Hitung total bayar
    function hitungTotal() {
        let total = parseInt(layananUtama.selectedOptions[0]?.dataset.harga || 0);
        layananTambahan.forEach(cb => {
            if (cb.checked) total += parseInt(cb.dataset.harga);
        });
        totalBayarDisplay.textContent = "Rp " + total.toLocaleString('id-ID');
    }

    layananUtama.addEventListener("change", hitungTotal);
    layananTambahan.forEach(cb => cb.addEventListener("change", hitungTotal));

    document.getElementById("btnSimpanBooking").addEventListener("click", function (e) {
        if (metode.value === "qris") {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById("qrisModal"));
            modal.show();
        }
    });

document.getElementById("btnSudahBayar").addEventListener("click", function () {
    const nomorAdmin = "6282140035275";

    // Ambil total bayar
    const totalBayarText = document.getElementById("totalBayar").textContent;

    // Ambil email user
    const emailUser = "{{ auth()->user()->email ?? '' }}";

    // Pesan multi-line dengan email
    const pesan = encodeURIComponent(
        `Halo admin, saya baru saja melakukan pembayaran via QRIS\nEmail: ${emailUser}\nTotal: ${totalBayarText}\nMohon di cek dan konfirmasi kembali yaa`
    );

    // Buka WA dengan pesan otomatis
    window.open("https://wa.me/" + nomorAdmin + "?text=" + pesan, "_blank");

    // Submit form setelah 1 detik
    setTimeout(() => form.submit(), 1000);
});


});
</script>

{{-- Style tambahan --}}
<style>
    body { background-color: #f4f4f4; }

    .btn-warning {
        background-color: #f4b400;
        border: none;
        transition: 0.3s;
    }
    .btn-warning:hover {
        background-color: #d99a00;
        color: #fff !important;
    }

    #slot-jam .btn-outline-gold {
        border-color: #f4b400;
        color: #f4b400;
        background-color: #fff;
        transition: 0.25s;
    }
    #slot-jam .btn-outline-gold:hover {
        background-color: #f4b400;
        color: #fff;
    }
    #slot-jam .btn-outline-gold.active {
        background-color: #d99a00 !important;
        color: #fff !important;
        border-color: #d99a00 !important;
    }
    #slot-jam .btn-disabled {
        background-color: #e0e0e0 !important;
        border-color: #c9c9c9 !important;
        color: #999 !important;
        cursor: not-allowed;
    }
</style>
@endsection
