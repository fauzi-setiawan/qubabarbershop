<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    // Tampilkan daftar layanan
    public function index()
    {
        $layanans = Layanan::latest()->paginate(10);
        return view('pages.admin.layanan.layanan', compact('layanans'));
    }

    // Tampilkan form tambah layanan
    public function create()
    {
        return view('pages.admin.layanan.create-layanan');
    }

    // Simpan data layanan baru
    public function store(Request $request)
    {
        // Hapus titik dari input harga (biar bisa disimpan sebagai angka)
        $request->merge([
            'harga' => str_replace('.', '', $request->harga),
        ]);

        // Validasi input
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori'     => 'required|in:utama,tambahan',
            'harga'        => 'required|numeric|min:0|max:999999999999.99',
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload foto jika ada
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('layanans', 'public');
        }

        // Simpan data ke database
        Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'kategori'     => $request->kategori,
            'harga'        => $request->harga,
            'deskripsi'    => $request->deskripsi,
            'foto'         => $foto,
        ]);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    // Tampilkan form edit layanan
    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('pages.admin.layanan.edit-layanan', compact('layanan'));
    }

    // Update data layanan
    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        // Bersihkan format harga sebelum validasi
        $request->merge([
            'harga' => str_replace('.', '', $request->harga),
        ]);

        // Validasi input
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori'     => 'required|in:utama,tambahan',
            'harga'        => 'required|numeric|min:0|max:999999999999.99',
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Data yang akan diperbarui
        $data = $request->only('nama_layanan', 'kategori', 'harga', 'deskripsi');

        // Ganti foto lama jika upload baru
        if ($request->hasFile('foto')) {
            if ($layanan->foto && Storage::disk('public')->exists($layanan->foto)) {
                Storage::disk('public')->delete($layanan->foto);
            }
            $data['foto'] = $request->file('foto')->store('layanans', 'public');
        }

        // Update data layanan
        $layanan->update($data);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    // Hapus data layanan
    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);

        // Hapus file foto jika ada
        if ($layanan->foto && Storage::disk('public')->exists($layanan->foto)) {
            Storage::disk('public')->delete($layanan->foto);
        }

        // Hapus record dari database
        $layanan->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
