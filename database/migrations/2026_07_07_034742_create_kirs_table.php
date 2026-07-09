<?php
// FILE 1: database/migrations/2025_01_01_000009_create_kirs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel header KIR (satu dokumen per ruangan per periode)
        Schema::create('kirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruangan_id')
                  ->constrained('ruangans')
                  ->onDelete('cascade');
            $table->string('nomor_kir')->unique();
            // Contoh: KIR/2025/001
            $table->year('tahun');
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamps();
        });

        // Tabel item / baris aset dalam KIR
        Schema::create('kir_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kir_id')
                  ->constrained('kirs')
                  ->onDelete('cascade');
            $table->foreignId('aset_id')
                  ->constrained('asets')
                  ->onDelete('cascade');
            $table->timestamps();

            // Satu aset tidak boleh masuk KIR yang sama dua kali
            $table->unique(['kir_id', 'aset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kir_items');
        Schema::dropIfExists('kirs');
    }
};
