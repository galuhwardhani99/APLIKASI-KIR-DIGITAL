<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            // Kolom lain (nibar, nomor_register, kode_barang, nama_barang, dst)
            // SUDAH ADA di tabel asets kamu -> tidak perlu ditambahkan lagi.
            // Cuma perlu relasi ke tabel klasifikasi_barangs yang baru.
            $table->foreignId('klasifikasi_barang_id')
                  ->nullable()
                  ->after('ruangan_id')
                  ->constrained('klasifikasi_barangs')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->dropForeign(['klasifikasi_barang_id']);
            $table->dropColumn('klasifikasi_barang_id');
        });
    }
};