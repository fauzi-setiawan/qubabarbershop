<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_user',
        'id_layanan',
        'id_petugas',
        'kode_booking',
        'waktu_kunjungan',
        'total',
        'metode_pembayaran',
        'status_pembayaran',
        'status',
    ];

    // relasi ke customer
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // relasi ke layanan utama
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    // relasi layanan tambahan (many-to-many)
    public function layananTambahan()
    {
        return $this->belongsToMany(
            Layanan::class,
            'pesanan_layanan',
            'id_pesanan',
            'id_layanan'
        );
    }

    // relasi ke petugas
    public function petugas()
    {
        return $this->belongsTo(\App\Models\Petugas::class, 'id_petugas', 'id_petugas');
    }
}
