<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiAset extends Model
{
    protected $table = 'mutasi_asets';

    protected $fillable = [
        'aset_id', 'ruangan_asal_id', 'ruangan_tujuan_id',
        'tanggal_mutasi', 'alasan', 'berita_acara_path',
        'bast_path', 'nomor_bast', 'created_by',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function ruanganAsal()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_asal_id');
    }

    public function ruanganTujuan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_tujuan_id');
    }
}