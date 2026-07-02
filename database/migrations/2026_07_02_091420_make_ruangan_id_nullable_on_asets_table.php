<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            // Drop foreign key dulu sebelum ubah kolom
            $table->dropForeign(['ruangan_id']);
        });

        Schema::table('asets', function (Blueprint $table) {
            $table->foreignId('ruangan_id')
                  ->nullable()
                  ->change();
        });

        Schema::table('asets', function (Blueprint $table) {
            $table->foreign('ruangan_id')
                  ->references('id')->on('ruangans')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
        });

        Schema::table('asets', function (Blueprint $table) {
            $table->foreignId('ruangan_id')
                  ->nullable(false)
                  ->change();
        });

        Schema::table('asets', function (Blueprint $table) {
            $table->foreign('ruangan_id')
                  ->references('id')->on('ruangans')
                  ->onDelete('restrict');
        });
    }
};