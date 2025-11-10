<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    public function history()
    {
        // 🧹 Hapus otomatis data booking lebih dari 30 hari dari waktu kunjungan
        Pesanan::where('id_user', Auth::id())
            ->where('waktu_kunjungan', '<', Carbon::now()->subDays(30))
            ->delete();

        // 🔍 Ambil data booking selesai atau sudah lewat waktu kunjungan
        $bookings = Pesanan::with(['layanan', 'petugas', 'layananTambahan']) // Tambahkan relasi layananTambahan
            ->where('id_user', Auth::id())
            ->where(function ($q) {
                $q->where('status', 'selesai')
                  ->orWhere('waktu_kunjungan', '<', now());
            })
            ->orderBy('waktu_kunjungan', 'desc')
            ->get();

        return view('pages.user.pesanan.history', compact('bookings'));
    }

    public function selesai($id)
    {
        $booking = Pesanan::where('id_user', Auth::id())->findOrFail($id);
        $booking->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Pesanan berhasil diselesaikan.');
    }

    public function printBookingDetail($id)
    {
        $booking = Pesanan::with(['layanan', 'petugas', 'layananTambahan'])
            ->where('id_user', Auth::id())
            ->findOrFail($id);

        if ($booking->status !== 'disetujui') {
            return redirect()->route('user.booking')->with('error', 'Booking belum disetujui, tidak dapat dicetak.');
        }

        $pdf = Pdf::loadView('pages.user.pesanan.print-booking', compact('booking'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Tiket_' . $booking->kode_booking . '.pdf');
    }
}
