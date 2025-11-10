<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    // Primary key custom
    protected $primaryKey = 'id_layanan';

    // Kolom yang bisa diisi mass assignment
    protected $fillable = [
        'nama_layanan',
        'kategori',    // Tambahkan kategori
        'harga',
        'deskripsi',
        'foto',
    ];

    // Relasi ke transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_layanan');
    }
}
