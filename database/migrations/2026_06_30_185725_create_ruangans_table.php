<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();

            // Header KIR dari Excel
            $table->string('kode_lokasi');
            // Contoh: 12.13.33.08.02.01.01
            // atau: Dinas Kearsipan dan Perpustakaan

            $table->string('nama_ruangan');
            // Kepala Dinas, Sekretaris, Sub Keuangan,
            // Sub Umum, Record Center Arsip, Tamu, Rapat

            // Pengguna Barang (kepala instansi / pimpinan dinas)
            $table->string('pengguna_barang')->nullable();
            // Contoh: EKO LUKMONO HADI

            // Pengurus Barang (PIC administrasi barang) - nama + NIP
            $table->string('pengurus_barang_nama')->nullable();
            $table->string('pengurus_barang_nip')->nullable();

            // Penanggung Jawab Ruangan (PIC fisik ruangan) - nama + NIP
            $table->string('penanggung_jawab_nama')->nullable();
            $table->string('penanggung_jawab_nip')->nullable();

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};