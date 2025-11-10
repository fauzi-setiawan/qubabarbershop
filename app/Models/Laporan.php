<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pesanan';

    // kolom yang bisa diisi massal
    protected $fillable = [
        'kode_booking',
        'id_user',
        'id_layanan',
        'id_petugas',
        'waktu_pemesanan',
        'waktu_kunjungan',
        'total',
        'metode_pembayaran',
        'status_pembayaran',
        'status'
    ];

    // relasi ke user (customer)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // relasi ke layanan utama
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    // relasi ke petugas
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }
}
