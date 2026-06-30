<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();

            // Relasi ke ruangan
            $table->foreignId('ruangan_id')
                  ->constrained('ruangans')
                  ->onDelete('restrict');

            // ── Kolom sesuai KIR Excel ──────────────────────────────────────

            $table->integer('no_urut')->nullable();
            // Kolom "No" di Excel (nomor urut dalam KIR)

            $table->string('nibar')->nullable();
            // NIBAR = Nomor Induk Barang

            $table->string('nomor_register')->nullable();
            // Contoh: 000004, 000001, 000025

            $table->string('kode_barang')->nullable();
            // Contoh: 02.05.01.04.005, 02.10.01.02.002

            $table->string('nama_barang');
            // Contoh: Filling Cabinet, AC, Laptop

            $table->string('spesifikasi_nama_barang')->nullable();
            // Kolom "Spesifikasi Nama Barang" (deskripsi tambahan nama)

            $table->string('merk_tipe')->nullable();
            // Kolom "Merk/Tipe" – contoh: VIP, Panasonik, HP, Brother

            $table->year('tahun_perolehan')->nullable();
            // Kolom "Tahun Perolehan" – contoh: 2009, 2012, 2019

            $table->decimal('jumlah', 10, 2)->default(1);
            // Kolom "Jumlah"

            $table->string('satuan')->nullable();
            // Kolom "Satuan" – contoh: Buah, Unit

            $table->text('keterangan')->nullable();
            // Kolom "Ket" – contoh: Baik, Rusak, dst.

            // ── Tambahan sistem digital ─────────────────────────────────────

            $table->string('kode_aset')->unique()->nullable();
            // Kode unik yang di-generate sistem (untuk QR code)

            $table->string('qr_code_path')->nullable();
            // Path file gambar QR code yang telah di-generate

            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])
                  ->default('baik');

            // User yang menginput / terakhir edit
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};