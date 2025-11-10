<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\Petugas;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Menampilkan daftar booking aktif milik user yang login
    public function booking()
    {
        $bookings = Pesanan::with(['layanan', 'layananTambahan', 'petugas'])
            ->where('id_user', Auth::id())
            ->where('status', '!=', 'selesai')
            ->where('waktu_kunjungan', '>=', now())
            ->orderBy('waktu_kunjungan', 'asc')
            ->get();

        return view('pages.user.pesanan.booking', compact('bookings'));
    }

    // Halaman form untuk buat booking baru
    public function createBooking()
    {
        $layanans = Layanan::all(); 
        $petugas = Petugas::where('status', 1)->get(); // cuma yang aktif

        return view('pages.user.pesanan.create-booking', compact('layanans', 'petugas'));
    }

    // Simpan data booking baru
    public function storeBooking(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanans,id_layanan',
            'layanan_tambahan.*' => 'nullable|exists:layanans,id_layanan',
            'id_petugas' => 'required|exists:petugas,id_petugas',
            'tanggal_kunjungan' => 'required|date',
            'jam_kunjungan' => 'required|date_format:H:i',
            'metode_pembayaran' => 'required|in:cash,qris',
        ]);

        $waktu_kunjungan = Carbon::parse($request->tanggal_kunjungan . ' ' . $request->jam_kunjungan);

        // Cek biar tanggal kunjungan gak lewat dari waktu sekarang
        if ($waktu_kunjungan->lt(now())) {
            return back()->withErrors(['jam_kunjungan' => 'Jam kunjungan tidak boleh lewat dari waktu sekarang'])->withInput();
        }

        $layananUtama = Layanan::findOrFail($request->id_layanan);
        $total = $layananUtama->harga;

        // Tambah harga dari layanan tambahan kalau ada
        $layananTambahanIds = [];
        if ($request->filled('layanan_tambahan')) {
            $layananTambahanIds = $request->layanan_tambahan;
            $total += Layanan::whereIn('id_layanan', $layananTambahanIds)->sum('harga');
        }

        // Generate kode unik booking
        $kode_booking = 'BK-' . date('Ymd') . strtoupper(substr(uniqid(), -4));

        $pesanan = Pesanan::create([
            'id_user' => Auth::id(),
            'id_layanan' => $layananUtama->id_layanan,
            'id_petugas' => $request->id_petugas,
            'waktu_kunjungan' => $waktu_kunjungan,
            'kode_booking' => $kode_booking,
            'total' => $total,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'pending',
        ]);

        // Simpan layanan tambahan ke pivot kalau ada
        if (!empty($layananTambahanIds)) {
            $pesanan->layananTambahan()->sync($layananTambahanIds);
        }

        return redirect()->route('user.booking')->with('success', 'Booking berhasil dibuat!');
    }

    // Halaman edit booking
    public function editBooking($id)
    {
        $booking = Pesanan::with('layananTambahan')->where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $layanans = Layanan::all(); 
        $petugas = Petugas::where('status', 1)->get(); // cuma petugas aktif

        return view('pages.user.pesanan.edit-booking', compact('booking', 'layanans', 'petugas'));
    }

    // Update data booking user
    public function updateBooking(Request $request, $id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $request->validate([
            'id_layanan' => 'required|exists:layanans,id_layanan',
            'layanan_tambahan.*' => 'nullable|exists:layanans,id_layanan',
            'id_petugas' => 'required|exists:petugas,id_petugas',
            'tanggal_kunjungan' => 'required|date',
            'jam_kunjungan' => 'required|date_format:H:i',
            'metode_pembayaran' => 'required|in:cash,qris',
        ]);

        $waktu_kunjungan = Carbon::parse($request->tanggal_kunjungan . ' ' . $request->jam_kunjungan);

        $pesanan->id_layanan = $request->id_layanan;
        $pesanan->id_petugas = $request->id_petugas;
        $pesanan->waktu_kunjungan = $waktu_kunjungan;
        $pesanan->metode_pembayaran = $request->metode_pembayaran;

        // Hitung ulang total harga
        $total = Layanan::findOrFail($request->id_layanan)->harga;
        $layananTambahanIds = $request->filled('layanan_tambahan') ? $request->layanan_tambahan : [];
        if (!empty($layananTambahanIds)) {
            $total += Layanan::whereIn('id_layanan', $layananTambahanIds)->sum('harga');
        }
        $pesanan->total = $total;

        $pesanan->save();

        // Update pivot untuk layanan tambahan
        $pesanan->layananTambahan()->sync($layananTambahanIds);

        return redirect()->route('user.booking')->with('success', 'Booking berhasil diperbarui!');
    }

    // Hapus booking user (batalin)
    public function destroyBooking($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        // Hapus relasi pivot lalu hapus data booking-nya
        $pesanan->layananTambahan()->detach();
        $pesanan->delete();

        return redirect()->route('user.booking')->with('success', 'Booking berhasil dibatalkan!');
    }
}
