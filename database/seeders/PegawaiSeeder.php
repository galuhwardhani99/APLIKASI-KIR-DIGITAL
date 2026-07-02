<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    /**
     * Isi dengan data pegawai nyata dari dinas.
     * Contoh di bawah menggunakan nama yang muncul di Excel KIR.
     */
    public function run(): void
    {
        DB::table('pegawais')->insert([
            [
                'nip'        => '196801011990031001',
                'nama'       => 'HERY PURNOMO',
                'jabatan'    => 'Pengurus Barang',
                'unit_kerja' => 'Dinas Kearsipan dan Perpustakaan',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip'        => '197005152000121002',
                'nama'       => 'EKO LUKMONO HADI',
                'jabatan'    => 'Pengguna Barang',
                'unit_kerja' => 'Dinas Kearsipan dan Perpustakaan',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambah pegawai lain di sini...
        ]);
    }
}
