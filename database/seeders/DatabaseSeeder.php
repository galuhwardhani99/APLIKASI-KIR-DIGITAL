<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. USERS ─────────────────────────────────────────────────────────
        DB::table('users')->insert([
            [
                'name'       => 'Administrator',
                'email'      => 'admin@kir.local',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Auditor',
                'email'      => 'auditor@kir.local',
                'password'   => Hash::make('password'),
                'role'       => 'auditor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ── 2. RUANGAN ───────────────────────────────────────────────────────
        DB::table('ruangans')->insert([
            [
                'kode_lokasi'           => '12.13.33.08.02.01.01',
                'nama_ruangan'          => 'Kepala Dinas',
                'pengguna_barang'       => 'EKO LUKMONO HADI',
                'pengurus_barang_nama'  => null,
                'pengurus_barang_nip'   => null,
                'penanggung_jawab_nama' => null,
                'penanggung_jawab_nip'  => null,
                'keterangan'            => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'kode_lokasi'           => '12.13.33.08.02.01.01',
                'nama_ruangan'          => 'Sekretaris',
                'pengguna_barang'       => 'EKO LUKMONO HADI',
                'pengurus_barang_nama'  => null,
                'pengurus_barang_nip'   => null,
                'penanggung_jawab_nama' => null,
                'penanggung_jawab_nip'  => null,
                'keterangan'            => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'kode_lokasi'           => '12.13.33.08.02.01.01',
                'nama_ruangan'          => 'Sub Keuangan',
                'pengguna_barang'       => 'EKO LUKMONO HADI',
                'pengurus_barang_nama'  => null,
                'pengurus_barang_nip'   => null,
                'penanggung_jawab_nama' => null,
                'penanggung_jawab_nip'  => null,
                'keterangan'            => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'kode_lokasi'           => '12.13.33.08.02.01.01',
                'nama_ruangan'          => 'Sub Umum',
                'pengguna_barang'       => 'EKO LUKMONO HADI',
                'pengurus_barang_nama'  => null,
                'pengurus_barang_nip'   => null,
                'penanggung_jawab_nama' => null,
                'penanggung_jawab_nip'  => null,
                'keterangan'            => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'kode_lokasi'           => '12.13.33.08.02.01.01',
                'nama_ruangan'          => 'Record Center Arsip',
                'pengguna_barang'       => 'HERY PURNOMO',
                'pengurus_barang_nama'  => null,
                'pengurus_barang_nip'   => null,
                'penanggung_jawab_nama' => null,
                'penanggung_jawab_nip'  => null,
                'keterangan'            => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'kode_lokasi'           => 'Dinas Kearsipan dan Perpustakaan',
                'nama_ruangan'          => 'Tamu',
                'pengguna_barang'       => 'HERY PURNOMO',
                'pengurus_barang_nama'  => null,
                'pengurus_barang_nip'   => null,
                'penanggung_jawab_nama' => null,
                'penanggung_jawab_nip'  => null,
                'keterangan'            => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'kode_lokasi'           => 'Dinas Kearsipan dan Perpustakaan',
                'nama_ruangan'          => 'Rapat',
                'pengguna_barang'       => 'HERY PURNOMO',
                'pengurus_barang_nama'  => null,
                'pengurus_barang_nip'   => null,
                'penanggung_jawab_nama' => null,
                'penanggung_jawab_nip'  => null,
                'keterangan'            => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
        ]);
    }
}