<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kirs', function (Blueprint $table) {

            $table->id();

            // Ruangan yang dibuatkan KIR
            $table->foreignId('ruangan_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Header KIR
            $table->string('pengguna_barang');

            $table->foreignId('pengurus_barang_id')
                  ->constrained('pegawais');

            $table->foreignId('penanggung_jawab_id')
                  ->constrained('pegawais');

            $table->date('tanggal');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kirs');
    }
};