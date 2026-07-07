<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pic_histories', function (Blueprint $table) {

            $table->id();

            // Ruangan
            $table->foreignId('ruangan_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Pengguna Barang
            $table->string('pengguna_barang_lama')->nullable();
            $table->string('pengguna_barang_baru');

            // Pengurus Barang
            $table->foreignId('pengurus_barang_lama_id')
                  ->nullable()
                  ->constrained('pegawais')
                  ->nullOnDelete();

            $table->foreignId('pengurus_barang_baru_id')
                  ->constrained('pegawais')
                  ->cascadeOnDelete();

            // Penanggung Jawab
            $table->foreignId('penanggung_jawab_lama_id')
                  ->nullable()
                  ->constrained('pegawais')
                  ->nullOnDelete();

            $table->foreignId('penanggung_jawab_baru_id')
                  ->constrained('pegawais')
                  ->cascadeOnDelete();

            // Tanggal pergantian
            $table->date('tanggal');

            // Catatan
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pic_histories');
    }
};
