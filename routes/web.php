<?php

use Illuminate\Support\Facades\Route;

// Tampilkan halaman dasbor utama (yang juga mencakup layar login jika belum autentikasi)
Route::get('/', function () {
    return view('dashboard');
});