<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasis', function (Blueprint $table) {

            $table->id();


            // Aset yang akan dipindahkan
            $table->foreignId('aset_id')
                  ->constrained('asets')
                  ->cascadeOnDelete();


            // Lokasi aset sebelum dipindahkan
            $table->foreignId('ruangan_asal_id')
                  ->constrained('ruangans')
                  ->restrictOnDelete();


            // Lokasi tujuan pemindahan
            $table->foreignId('ruangan_tujuan_id')
                  ->constrained('ruangans')
                  ->restrictOnDelete();


            // Pegawai yang meminta mutasi
            $table->foreignId('pemohon_id')
                  ->constrained('pegawais')
                  ->restrictOnDelete();


            // Tanggal pengajuan
            $table->date('tanggal_pengajuan');


            // Alasan perpindahan
            $table->text('alasan')
                  ->nullable();


            // Status proses auditor
            $table->enum('status', [
                'pending',
                'disetujui',
                'ditolak'
            ])
            ->default('pending');


            // Auditor yang memproses
            $table->foreignId('penerima_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();


            // Waktu validasi auditor
            $table->date('tanggal_validasi')
                  ->nullable();


            // Catatan auditor
            $table->text('catatan_validasi')
                  ->nullable();


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};