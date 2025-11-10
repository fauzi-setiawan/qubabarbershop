<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';  
    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'email',
        'username',
        'password',
        'role',
        'no_hp',
        'alamat',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // relasi ke semua pesanan milik user
    public function bookings()
    {
        return $this->hasMany(\App\Models\Pesanan::class, 'id_user', 'id_user');
    }

    // relasi ke transaksi user
    public function transaksis()
    {
        return $this->hasMany(\App\Models\Transaksi::class, 'id_user', 'id_user');
    }
}
