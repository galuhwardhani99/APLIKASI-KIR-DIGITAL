<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aset;
use App\Models\KlasifikasiBarang;
use App\Models\User;

class AsetImportSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/aset_import.csv');
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);

        $klasCache = [];

        // Semua data di 1_DATA_BMD.xlsx ada di bawah kategori "PERALATAN DAN MESIN"
        // (kode 1.3.2), jadi kolom `jenis` didefault ke ini untuk seluruh import.
        // Kalau ada file BMD lain untuk "ASET TETAP LAINNYA", buat seeder terpisah.
        $jenisDefault = 'peralatan_mesin';

        // created_by wajib diisi -> pakai user pertama di tabel users (biasanya admin/seeder awal)
        $userId = User::query()->value('id');
        if (!$userId) {
            throw new \Exception('Tidak ada user di tabel users. Buat/seed user dulu sebelum import aset.');
        }

        $noUrut = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $kodeL6 = $data['klasifikasi_kode_l6'];
            if (!isset($klasCache[$kodeL6])) {
                $klasCache[$kodeL6] = KlasifikasiBarang::where('kode', $kodeL6)->value('id');
            }

            Aset::create([
                'ruangan_id'               => null, // isi manual nanti / sesuaikan sumber lain
                'klasifikasi_barang_id'    => $klasCache[$kodeL6],
                'jenis'                    => $jenisDefault,
                'no_urut'                  => $noUrut++,
                'nibar'                    => $data['nibar'],
                'nomor_register'           => $data['nomor_register'],
                'kode_barang'              => $data['kode_barang'],
                'nama_barang'              => $data['nama_barang'],
                'spesifikasi_nama_barang'  => $data['spesifikasi_nama_barang'],
                'merk_tipe'                => $data['merk_tipe'],
                'tahun_perolehan'          => $data['tahun_perolehan']
                    ? date('Y', strtotime($data['tahun_perolehan']))
                    : null,
                'jumlah'                   => (float) $data['jumlah'],
                'satuan'                   => $data['satuan'],
                'keterangan'               => $data['keterangan'],
                'kode_aset'                => null,
                'qr_code_path'             => null,
                'kondisi'                  => 'baik', // default -> ubah manual per unit kalau perlu
                'created_by'               => $userId,
                'updated_by'               => $userId,
            ]);
        }

        fclose($handle);
    }
}
