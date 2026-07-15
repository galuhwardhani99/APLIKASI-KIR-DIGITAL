<?php
// database/migrations/2026_07_10_000001_drop_pic_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pic_histories');
    }

    public function down(): void
    {
        // Tabel lama tidak direstore otomatis.
        // Kalau butuh rollback struktur asli, jalankan ulang migration
        // pembuatan pic_histories yang lama secara manual.
    }
};