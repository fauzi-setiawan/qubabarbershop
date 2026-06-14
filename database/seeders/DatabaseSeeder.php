<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Module;
use App\Models\TestCase;
use App\Models\TestExecution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. BUAT DATA PENGGUNA (USERS) DENGAN ROLE BERBEDA
        $admin = User::firstOrCreate(
            ['email' => 'admin@sistem.com'],
            [
                'name' => 'Fauzi Setiawan',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
                'avatar' => 'FS'
            ]
        );

        $pm = User::firstOrCreate(
            ['email' => 'pm@sistem.com'],
            [
                'name' => 'Budi Hartono',
                'password' => Hash::make('password123'),
                'role' => 'Project Manager',
                'avatar' => 'BH'
            ]
        );

        $qa = User::firstOrCreate(
            ['email' => 'qa@sistem.com'],
            [
                'name' => 'Citra Lestari',
                'password' => Hash::make('password123'),
                'role' => 'Software QA',
                'avatar' => 'CL'
            ]
        );

        // 2. BUAT DATA PROYEK (PROJECTS)
        $projectAlpha = Project::firstOrCreate(
            ['name' => 'Project Alpha - E-commerce'],
            [
                'description' => 'Pengembangan platform e-commerce retail baru berbasis web dan mobile.',
                'priority' => 'High',
                'created_by' => $pm->id
            ]
        );

        $projectBeta = Project::firstOrCreate(
            ['name' => 'BNI-QRIS - Mobile Banking'],
            [
                'description' => 'Aplikasi perbankan untuk transaksi keuangan nasabah via smartphone.',
                'priority' => 'High',
                'created_by' => $pm->id
            ]
        );

        $projectSauce = Project::firstOrCreate(
            ['name' => 'Swag Labs (SauceDemo)'],
            [
                'description' => 'Aplikasi e-commerce demo untuk latihan otomatisasi pengujian.',
                'priority' => 'Medium',
                'created_by' => $pm->id
            ]
        );

        // 3. BUAT DATA MODUL UNTUK PROJECT BETA
        Module::firstOrCreate([
            'project_id' => $projectBeta->id,
            'name' => 'Login'
        ]);

        // 4. BUAT DATA MODUL UNTUK PROJECT SAUCEDEMO
        $sauceModules = [
            'Authentication',
            'Dashboard / Inventory',
            'Product Detail',
            'Shopping Cart',
            'Checkout Step One',
            'Checkout Step Two',
            'Checkout Complete',
            'Sidebar Navigation & Global Actions'
        ];

        foreach ($sauceModules as $modName) {
            Module::firstOrCreate([
                'project_id' => $projectSauce->id,
                'name' => $modName
            ]);
        }

        // 5. BUAT DATA MODUL & TEST CASES UNTUK PROJECT ALPHA
        $modulesData = [
            'Login' => [
                ['name' => 'Verifikasi Login dengan Kredensial Valid', 'steps' => "1. Buka login\n2. Isi email valid\n3. Isi password valid\n4. Klik login", 'expected' => 'Masuk dashboard', 'priority' => 'High', 'status' => 'Pass'],
                ['name' => 'Verifikasi Login dengan Password Salah', 'steps' => "1. Buka login\n2. Isi email valid\n3. Isi password salah\n4. Klik login", 'expected' => 'Tampil error kredensial', 'priority' => 'High', 'status' => 'Pass'],
                ['name' => 'Verifikasi Login dengan Email Tidak Terdaftar', 'steps' => "1. Buka login\n2. Isi email acak\n3. Isi password\n4. Klik login", 'expected' => 'Tampil error email tidak ada', 'priority' => 'High', 'status' => 'Fail'],
            ],
            'Homepage' => [
                ['name' => 'Cek Slider Promo Banner', 'steps' => "1. Buka homepage\n2. Tunggu 5 detik", 'expected' => 'Banner bergeser otomatis', 'priority' => 'Medium', 'status' => 'Pass'],
                ['name' => 'Cek Loading Kategori Produk', 'steps' => "1. Buka homepage\n2. Lihat section kategori", 'expected' => 'Kategori produk termuat lengkap', 'priority' => 'Medium', 'status' => 'Fail'],
            ],
            'Product Detail' => [
                ['name' => 'Tampilkan Deskripsi & Ulasan', 'steps' => "1. Klik salah satu produk\n2. Gulir ke bawah", 'expected' => 'Deskripsi dan ulasan termuat', 'priority' => 'Medium', 'status' => 'Pass'],
                ['name' => 'Verifikasi Tombol Tambah ke Keranjang', 'steps' => "1. Klik salah satu produk\n2. Klik Tambah ke Keranjang", 'expected' => 'Angka keranjang bertambah', 'priority' => 'High', 'status' => 'Blocked'],
            ],
            'Cart' => [
                ['name' => 'Ubah Kuantitas Produk di Keranjang', 'steps' => "1. Buka keranjang\n2. Ubah kuantitas produk jadi 2", 'expected' => 'Subtotal harga diperbarui otomatis', 'priority' => 'High', 'status' => 'Fail'],
            ],
            'Checkout' => [
                ['name' => 'Proses Checkout dengan Virtual Account', 'steps' => "1. Klik Checkout\n2. Pilih Virtual Account\n3. Klik Bayar", 'expected' => 'Nomor invoice terbit', 'priority' => 'High', 'status' => 'Pass'],
            ]
        ];

        foreach ($modulesData as $moduleName => $testCases) {
            // Buat Modul
            $module = Module::firstOrCreate([
                'project_id' => $projectAlpha->id,
                'name' => $moduleName
            ]);

            foreach ($testCases as $tc) {
                // Buat Skenario Test Case
                $testCase = TestCase::firstOrCreate(
                    [
                        'module_id' => $module->id,
                        'name' => $tc['name']
                    ],
                    [
                        'steps' => $tc['steps'],
                        'expected_result' => $tc['expected'],
                        'priority' => $tc['priority'],
                        'created_by' => $qa->id
                    ]
                );

                // Buat Riwayat Eksekusi agar langsung terbaca di Dashboard Reporting & Monitoring
                if ($testCase->wasRecentlyCreated) {
                    TestExecution::create([
                        'test_case_id' => $testCase->id,
                        'executed_by' => $qa->id,
                        'status' => $tc['status'],
                        'notes' => $tc['status'] === 'Blocked' ? 'Gateway API bank sedang maintenance.' : 'Pengujian manual berhasil dilakukan.'
                    ]);
                }
            }
        }
    }
}
