<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'asets';

    protected $fillable = [
        'ruangan_id',
        'klasifikasi_barang_id',
        'no_urut',
        'nibar',
        'nomor_register',
        'kode_barang',
        'nama_barang',
        'spesifikasi_nama_barang',
        'merk_tipe',
        'tahun_perolehan',
        'jumlah',
        'satuan',
        'keterangan',
        'kode_aset',
        'qr_code_path',
        'kondisi',
        'created_by',
        'updated_by',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function klasifikasiBarang()
    {
        return $this->belongsTo(KlasifikasiBarang::class);
    }

    public function kirs()
    {
        return $this->belongsToMany(Kir::class, 'aset_kir');
    }
}
