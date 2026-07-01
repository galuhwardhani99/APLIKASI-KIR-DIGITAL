<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();

            $table->enum('jenis', [
                'aset_rusak',
                'aset_hilang',
                'aset_pindah_ruangan',
                'perubahan_pic',
                'aset_tidak_sesuai_kir',
            ]);
            // Sesuai use case diagram: 5 jenis notifikasi

            // Relasi opsional ke aset atau ruangan
            $table->foreignId('aset_id')
                  ->nullable()
                  ->constrained('asets')
                  ->onDelete('cascade');

            $table->foreignId('ruangan_id')
                  ->nullable()
                  ->constrained('ruangans')
                  ->onDelete('cascade');

            $table->text('pesan');
            // Isi pesan notifikasi

            $table->boolean('is_read')->default(false);

            // Notifikasi ditujukan ke user tertentu (opsional, null = broadcast semua admin)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};