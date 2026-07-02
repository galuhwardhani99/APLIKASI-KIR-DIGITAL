<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangans';

    protected $fillable = [
        'kode_lokasi', 'nama_ruangan', 'pengguna_barang',
        'pengurus_barang_nama', 'pengurus_barang_nip',
        'penanggung_jawab_nama', 'penanggung_jawab_nip',
        'keterangan',
    ];

    public function asets()
    {
        return $this->hasMany(Aset::class);
    }
}