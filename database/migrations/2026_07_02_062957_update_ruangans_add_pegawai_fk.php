<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus kolom nama+nip string lama di ruangans,
     * ganti dengan foreign key ke tabel pegawais.
     *
     * Jalankan SETELAH migration pegawais sudah ada.
     */
    public function up(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {

            // Hapus kolom string lama
            $table->dropColumn([
                'pengurus_barang_nama',
                'pengurus_barang_nip',
                'penanggung_jawab_nama',
                'penanggung_jawab_nip',
            ]);

            // Tambah FK ke pegawais
            $table->foreignId('pengurus_barang_id')
                  ->nullable()
                  ->after('pengguna_barang')
                  ->constrained('pegawais')
                  ->onDelete('set null');

            $table->foreignId('penanggung_jawab_id')
                  ->nullable()
                  ->after('pengurus_barang_id')
                  ->constrained('pegawais')
                  ->onDelete('set null');

            // Kolom tanggal tanda tangan (sesuai form KIR di Excel)
            $table->date('tanggal_ttd_pengurus')->nullable()->after('penanggung_jawab_id');
            $table->date('tanggal_ttd_pj')->nullable()->after('tanggal_ttd_pengurus');
        });
    }

    public function down(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            $table->dropForeign(['pengurus_barang_id']);
            $table->dropForeign(['penanggung_jawab_id']);
            $table->dropColumn([
                'pengurus_barang_id',
                'penanggung_jawab_id',
                'tanggal_ttd_pengurus',
                'tanggal_ttd_pj',
            ]);
            $table->string('pengurus_barang_nama')->nullable();
            $table->string('pengurus_barang_nip')->nullable();
            $table->string('penanggung_jawab_nama')->nullable();
            $table->string('penanggung_jawab_nip')->nullable();
        });
    }
};
