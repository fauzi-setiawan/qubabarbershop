<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';
    protected $primaryKey = 'id';

    // kolom yang bisa diisi massal
    protected $fillable = [
        'nama_barang',
        'brand',
        'stok',
    ];
}
