<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Admin 1
        User::updateOrCreate(
            ['email' => 'admin@quba.com'], 
            [
                'nama' => 'ari',
                'username' => 'adminquba',
                'password' => Hash::make('12345678'), 
                'role' => 'admin',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Contoh No. 123, Palembang',
                'foto' => 'default.png', 
                'email_verified_at' => now(),
            ]
        );

        // Admin 2
        User::updateOrCreate(
            ['email' => 'budi@quba.com'], 
            [
                'nama' => 'budi',
                'username' => 'adminbudi',
                'password' => Hash::make('87654321'), 
                'role' => 'admin',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Contoh No. 456, Palembang',
                'foto' => 'default.png', 
                'email_verified_at' => now(),
            ]
        );
    }
}
