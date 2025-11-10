<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    // Menampilkan semua data barang ke halaman utama
    public function index()
    {
        $barangs = Barang::all();
        return view('pages.admin.laporan.barang', compact('barangs'));
    }

    // Menampilkan form untuk menambah barang baru
    public function create()
    {
        return view('pages.admin.laporan.create-barang');
    }

    // Menyimpan data barang baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'brand' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
        ]);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'brand' => $request->brand,
            'stok' => $request->stok,
        ]);

        return redirect()->route('admin.barang.index')
                         ->with('success', 'Barang berhasil ditambahkan');
    }

    // Menampilkan form edit barang berdasarkan ID
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('pages.admin.laporan.edit-barang', compact('barang'));
    }

    // Memperbarui data barang di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'brand' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'brand' => $request->brand,
            'stok' => $request->stok,
        ]);

        return redirect()->route('admin.barang.index')
                         ->with('success', 'Barang berhasil diperbarui');
    }

    // Menghapus data barang dari database
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')
                         ->with('success', 'Barang berhasil dihapus');
    }

    // Mengekspor data barang menjadi file PDF
    public function exportPdf()
    {
        $barangs = Barang::all();

        // Menggunakan view khusus untuk layout PDF
        $pdf = Pdf::loadView('pages.admin.laporan.barang-pdf', compact('barangs'))
                  ->setPaper('a4', 'portrait');

        // Mengunduh file hasil export
        return $pdf->download('data_barang.pdf');
    }
}
