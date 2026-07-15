<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlasifikasiBarang;

class KlasifikasiBarangSeeder extends Seeder
{
    /**
     * Hanya level 3-6 (kategori). Level 7 (jenis barang, ex: Station Wagon)
     * TIDAK dimasukkan sini -> itu sudah jadi kolom kode_barang/nama_barang
     * langsung di tabel asets sesuai skema project ini.
     */
    public function run(): void
    {
        $data = [
            ['1.3.2', 'PERALATAN DAN MESIN', 3, '1.3'],
            ['1.3.2.02', 'ALAT ANGKUTAN', 4, '1.3.2'],
            ['1.3.2.02.01', 'ALAT ANGKUTAN DARAT BERMOTOR', 5, '1.3.2.02'],
            ['1.3.2.02.01.01', 'KENDARAAN DINAS BERMOTOR PERORANGAN', 6, '1.3.2.02.01'],
            ['1.3.2.02.01.04', 'KENDARAAN BERMOTOR BERODA DUA', 6, '1.3.2.02.01'],
            ['1.3.2.02.01.09', 'ALAT ANGKUTAN DARAT BERMOTOR LAINNYA', 6, '1.3.2.02.01'],
            ['1.3.2.02.02', 'ALAT ANGKUTAN DARAT TAK BERMOTOR', 5, '1.3.2.02'],
            ['1.3.2.02.02.01', 'KENDARAAN TAK BERMOTOR ANGKUTAN BARANG', 6, '1.3.2.02.02'],
            ['1.3.2.03', 'ALAT BENGKEL DAN ALAT UKUR', 4, '1.3.2'],
            ['1.3.2.03.01', 'ALAT BENGKEL BERMESIN', 5, '1.3.2.03'],
            ['1.3.2.03.01.01', 'PERKAKAS KONSTRUKSI LOGAM TERPASANG PADA PONDASI', 6, '1.3.2.03.01'],
            ['1.3.2.03.01.04', 'PERKAKAS BENGKEL SERVICE', 6, '1.3.2.03.01'],
            ['1.3.2.03.02', 'ALAT BENGKEL TAK BERMESIN', 5, '1.3.2.03'],
            ['1.3.2.03.02.05', 'PERKAKAS STANDARD (STANDARD TOOLS)', 6, '1.3.2.03.02'],
            ['1.3.2.05', 'ALAT KANTOR DAN RUMAH TANGGA', 4, '1.3.2'],
            ['1.3.2.05.01', 'ALAT KANTOR', 5, '1.3.2.05'],
            ['1.3.2.05.01.04', 'ALAT PENYIMPAN PERLENGKAPAN KANTOR', 6, '1.3.2.05.01'],
            ['1.3.2.05.01.05', 'ALAT KANTOR LAINNYA', 6, '1.3.2.05.01'],
            ['1.3.2.05.02', 'ALAT RUMAH TANGGA', 5, '1.3.2.05'],
            ['1.3.2.05.02.01', 'MEUBELAIR', 6, '1.3.2.05.02'],
            ['1.3.2.05.02.03', 'ALAT PEMBERSIH', 6, '1.3.2.05.02'],
            ['1.3.2.05.02.04', 'ALAT PENDINGIN', 6, '1.3.2.05.02'],
            ['1.3.2.05.02.05', 'ALAT DAPUR', 6, '1.3.2.05.02'],
            ['1.3.2.05.02.06', 'ALAT RUMAH TANGGA LAINNYA (HOME USE)', 6, '1.3.2.05.02'],
            ['1.3.2.05.02.07', 'ALAT PEMADAM KEBAKARAN', 6, '1.3.2.05.02'],
            ['1.3.2.05.03', 'MEJA DAN KURSI KERJA/RAPAT PEJABAT', 5, '1.3.2.05'],
            ['1.3.2.05.03.01', 'MEJA KERJA PEJABAT', 6, '1.3.2.05.03'],
            ['1.3.2.05.03.03', 'KURSI KERJA PEJABAT', 6, '1.3.2.05.03'],
            ['1.3.2.05.03.07', 'LEMARI DAN ARSIP PEJABAT', 6, '1.3.2.05.03'],
            ['1.3.2.06', 'ALAT STUDIO, KOMUNIKASI DAN PEMANCAR', 4, '1.3.2'],
            ['1.3.2.06.01', 'ALAT STUDIO', 5, '1.3.2.06'],
            ['1.3.2.06.01.01', 'PERALATAN STUDIO AUDIO', 6, '1.3.2.06.01'],
            ['1.3.2.06.01.02', 'PERALATAN STUDIO VIDEO DAN FILM', 6, '1.3.2.06.01'],
            ['1.3.2.06.01.04', 'PERALATAN CETAK', 6, '1.3.2.06.01'],
            ['1.3.2.06.02', 'ALAT KOMUNIKASI', 5, '1.3.2.06'],
            ['1.3.2.06.02.01', 'ALAT KOMUNIKASI TELEPHONE', 6, '1.3.2.06.02'],
            ['1.3.2.06.03', 'PERALATAN PEMANCAR', 5, '1.3.2.06'],
            ['1.3.2.06.03.17', 'PERALATAN MICROWAVE TVRO', 6, '1.3.2.06.03'],
            ['1.3.2.07', 'ALAT KEDOKTERAN DAN KESEHATAN', 4, '1.3.2'],
            ['1.3.2.07.01', 'ALAT KEDOKTERAN', 5, '1.3.2.07'],
            ['1.3.2.07.01.01', 'ALAT KEDOKTERAN UMUM', 6, '1.3.2.07.01'],
            ['1.3.2.08', 'ALAT LABORATORIUM', 4, '1.3.2'],
            ['1.3.2.08.01', 'UNIT ALAT LABORATORIUM', 5, '1.3.2.08'],
            ['1.3.2.08.01.13', 'ALAT LABORATORIUM KIMIA', 6, '1.3.2.08.01'],
            ['1.3.2.08.03', 'ALAT PERAGA PRAKTEK SEKOLAH', 5, '1.3.2.08'],
            ['1.3.2.08.03.03', 'ALAT PERAGA PRAKTEK SEKOLAH BIDANG STUDI : IPA DASAR', 6, '1.3.2.08.03'],
            ['1.3.2.10', 'KOMPUTER', 4, '1.3.2'],
            ['1.3.2.10.01', 'KOMPUTER UNIT', 5, '1.3.2.10'],
            ['1.3.2.10.01.01', 'KOMPUTER JARINGAN', 6, '1.3.2.10.01'],
            ['1.3.2.10.01.02', 'PERSONAL KOMPUTER', 6, '1.3.2.10.01'],
            ['1.3.2.10.01.03', 'KOMPUTER UNIT LAINNYA', 6, '1.3.2.10.01'],
            ['1.3.2.10.02', 'PERALATAN KOMPUTER', 5, '1.3.2.10'],
            ['1.3.2.10.02.02', 'PERALATAN MINI KOMPUTER', 6, '1.3.2.10.02'],
            ['1.3.2.10.02.03', 'PERALATAN PERSONAL KOMPUTER', 6, '1.3.2.10.02'],
            ['1.3.2.10.02.04', 'PERALATAN JARINGAN', 6, '1.3.2.10.02'],
            ['1.3.2.10.02.05', 'PERALATAN KOMPUTER LAINNYA', 6, '1.3.2.10.02'],
            ['1.3.2.19', 'PERALATAN OLAH RAGA', 4, '1.3.2'],
            ['1.3.2.19.01', 'PERALATAN OLAH RAGA', 5, '1.3.2.19'],
            ['1.3.2.19.01.02', 'PERALATAN PERMAINAN', 6, '1.3.2.19.01'],
            ['e-BMD', '', 1, null],
        ];

        usort($data, fn ($a, $b) => $a[2] <=> $b[2]);

        foreach ($data as [$kode, $nama, $level, $parentKode]) {
            $parentId = $parentKode
                ? KlasifikasiBarang::where('kode', $parentKode)->value('id')
                : null;

            KlasifikasiBarang::updateOrCreate(
                ['kode' => $kode],
                ['nama' => $nama, 'level' => $level, 'parent_id' => $parentId]
            );
        }
    }
}
