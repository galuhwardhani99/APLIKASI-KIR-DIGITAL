<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasifikasi_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();      // ex: 1.3.2.02.01.01.003
            $table->string('nama');                 // SUDAH DISIMPAN UPPERCASE
            $table->unsignedTinyInteger('level');   // 3..7 (lihat penjelasan level di README)
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('klasifikasi_barangs')
                  ->nullOnDelete();
            $table->timestamps();

            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_barangs');
    }
};
