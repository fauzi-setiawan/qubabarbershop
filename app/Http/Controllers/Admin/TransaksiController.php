<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        $query = Pesanan::with(['user', 'layanan', 'layananTambahan', 'petugas'])
            ->whereDate('waktu_kunjungan', $today)
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $transaksis = $query->paginate(10);

        return view('pages.admin.transaksi.transaksi', compact('transaksis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'nullable|in:belum_bayar,sudah_bayar',
            'status' => 'nullable|in:pending,disetujui,ditolak,selesai',
        ]);

        $transaksi = Pesanan::findOrFail($id);
        $transaksi->status_pembayaran = $request->status_pembayaran ?? $transaksi->status_pembayaran;
        $transaksi->status = $request->status ?? $transaksi->status;
        $transaksi->save();

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transaksi = Pesanan::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function print(Request $request, $id)
    {
        $pesanan = Pesanan::with(['user', 'layanan', 'layananTambahan', 'petugas'])->findOrFail($id);

        $uangBayarInput = $request->input('uang_bayar');
        $uangBayarInt = 0;
        $kembalian = null;

        if ($pesanan->metode_pembayaran === 'cash') {
            if (!$uangBayarInput) {
                // Belum input uang → redirect ke halaman transaksi dengan session modal
                return redirect()->route('admin.transaksi.index')
                    ->with('showCashModal', $pesanan->id_pesanan);
            }

            // Hapus titik/koma dan convert ke integer
            $uangBayarInt = (int) str_replace(['.', ','], '', $uangBayarInput);
            $total = is_numeric($pesanan->total) ? $pesanan->total : 0;
            $kembalian = $uangBayarInt - $total;
        }

        $pdf = PDF::loadView('pages.admin.struk.struk', [
            'pesanan' => $pesanan,
            'uangBayar' => $uangBayarInt,
            'kembalian' => $kembalian,
        ])->setPaper('A5', 'portrait');

        return $pdf->stream('Struk-' . $pesanan->kode_booking . '.pdf');
    }
}
