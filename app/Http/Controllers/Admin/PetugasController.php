<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    // Menampilkan semua data petugas
    public function index()
    {
        $petugas = Petugas::all();
        return view('pages.admin.petugas.index', compact('petugas'));
    }

    // Menampilkan form tambah petugas baru
    public function create()
    {
        return view('pages.admin.petugas.create-petugas');
    }

    // Menyimpan data petugas baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
        ]);

        Petugas::create([
            'nama_petugas' => $request->nama_petugas,
            'alamat' => $request->alamat,
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil ditambahkan!');
    }

    // Menampilkan form edit petugas berdasarkan ID
    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('pages.admin.petugas.edit-petugas', compact('petugas'));
    }

    // Memperbarui data petugas di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
        ]);

        $petugas = Petugas::findOrFail($id);
        $petugas->update([
            'nama_petugas' => $request->nama_petugas,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil diperbarui!');
    }

    // Menghapus data petugas dari database
    public function destroy($id)
    {
        Petugas::findOrFail($id)->delete();
        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus!');
    }

    // Mengubah status aktif/nonaktif petugas melalui AJAX
    public function toggleStatus(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->status = $request->status;
        $petugas->save();

        return response()->json(['success' => true, 'status' => $petugas->status]);
    }
}
