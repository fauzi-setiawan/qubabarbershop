<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Layanan;

class LayananController extends Controller
{
    public function index()
    {
        $layananUtama = Layanan::where('kategori', 'utama')->get();
        $layananTambahan = Layanan::where('kategori', 'tambahan')->get();

        return view('pages.user.layanan.layanan', compact('layananUtama', 'layananTambahan'));
    }
}
