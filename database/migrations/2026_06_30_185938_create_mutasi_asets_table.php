<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_asets', function (Blueprint $table) {
            $table->id();

            // Aset yang dimutasi
            $table->foreignId('aset_id')
                  ->constrained('asets')
                  ->onDelete('cascade');

            // Ruangan asal sebelum mutasi
            $table->foreignId('ruangan_asal_id')
                  ->constrained('ruangans')
                  ->onDelete('restrict');

            // Ruangan tujuan setelah mutasi
            $table->foreignId('ruangan_tujuan_id')
                  ->constrained('ruangans')
                  ->onDelete('restrict');

            $table->date('tanggal_mutasi');

            $table->text('alasan')->nullable();
            // Alasan / keterangan perpindahan aset

            $table->string('berita_acara_path')->nullable();
            // Path file upload Berita Acara (PDF/image)

            $table->string('bast_path')->nullable();
            // Path file BAST (Berita Acara Serah Terima) yang di-generate sistem

            $table->string('nomor_bast')->nullable();
            // Nomor dokumen BAST yang digenerate

            // User yang melakukan mutasi
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_asets');
    }
};