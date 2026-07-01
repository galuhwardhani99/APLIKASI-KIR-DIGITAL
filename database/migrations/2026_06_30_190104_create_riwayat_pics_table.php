<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pics', function (Blueprint $table) {
            $table->id();

            // Ruangan yang PIC-nya berubah
            $table->foreignId('ruangan_id')
                  ->constrained('ruangans')
                  ->onDelete('cascade');

            $table->enum('jenis_pic', ['pengurus_barang', 'penanggung_jawab']);
            // Membedakan apakah yang berubah Pengurus Barang atau Penanggung Jawab Ruangan

            // Data PIC lama
            $table->string('nama_lama')->nullable();
            $table->string('nip_lama')->nullable();

            // Data PIC baru
            $table->string('nama_baru');
            $table->string('nip_baru')->nullable();

            $table->date('tanggal_perubahan');
            $table->text('keterangan')->nullable();

            // User yang melakukan perubahan
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pics');
    }
};