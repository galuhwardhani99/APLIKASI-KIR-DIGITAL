<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kirs', function (Blueprint $table) {
            $table->foreignId('pengurus_barang_id')->nullable()->change();
            $table->foreignId('penanggung_jawab_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('kirs', function (Blueprint $table) {
            $table->foreignId('pengurus_barang_id')->nullable(false)->change();
            $table->foreignId('penanggung_jawab_id')->nullable(false)->change();
        });
    }
    
};
