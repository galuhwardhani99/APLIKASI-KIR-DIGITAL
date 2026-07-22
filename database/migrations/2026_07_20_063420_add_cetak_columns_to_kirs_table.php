<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kirs', function (Blueprint $table) {
            $table->string('pengguna_barang')->nullable()->after('status');
            $table->foreignId('pengurus_barang_id')->nullable()->after('pengguna_barang');
            $table->foreignId('penanggung_jawab_id')->nullable()->after('pengurus_barang_id');
            $table->string('keterangan')->nullable()->after('penanggung_jawab_id');
        });
    }

    public function down(): void
    {
        Schema::table('kirs', function (Blueprint $table) {
            $table->dropColumn([
                'pengguna_barang',
                'pengurus_barang_id',
                'penanggung_jawab_id',
                'keterangan'
            ]);
        });
    }
};