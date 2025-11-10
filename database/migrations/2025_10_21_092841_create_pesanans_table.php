<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->string('kode_booking', 30)->unique();
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_layanan')->constrained('layanans', 'id_layanan')->onDelete('cascade');
            $table->foreignId('id_petugas')->constrained('petugas', 'id_petugas')->onDelete('cascade');
            $table->timestamp('waktu_pemesanan')->useCurrent();
            $table->dateTime('waktu_kunjungan');
            $table->decimal('total', 15, 2);
            $table->enum('metode_pembayaran', ['cash','qris'])->default('cash');
            $table->enum('status_pembayaran', ['belum_bayar','sudah_bayar'])->default('belum_bayar');
            $table->enum('status', ['pending','disetujui','ditolak','selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};  