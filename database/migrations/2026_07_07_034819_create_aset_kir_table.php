<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset_kir', function (Blueprint $table) {

            $table->id();

            $table->foreignId('kir_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('aset_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset_kir');
    }
};