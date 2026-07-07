<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kir extends Model
{
    protected $fillable = [
        'ruangan_id',
        'pengguna_barang',
        'pengurus_barang_id',
        'penanggung_jawab_id',
        'tanggal',
        'keterangan',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function pengurusBarang()
    {
        return $this->belongsTo(Pegawai::class, 'pengurus_barang_id');
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'penanggung_jawab_id');
    }

    public function asets()
    {
        return $this->belongsToMany(Aset::class, 'aset_kir');
    }
}