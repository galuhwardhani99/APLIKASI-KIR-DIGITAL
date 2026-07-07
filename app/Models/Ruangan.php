<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangans';

    protected $fillable = [
        'nama_ruangan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_ttd_pengurus' => 'date',
        'tanggal_ttd_pj'       => 'date',
    ];

    // Relasi ke pegawai sebagai Pengurus Barang
    public function pengurusBarang()
    {
        return $this->belongsTo(Pegawai::class, 'pengurus_barang_id');
    }

    // Relasi ke pegawai sebagai Penanggung Jawab Ruangan
    public function penanggungJawab()
    {
        return $this->belongsTo(Pegawai::class, 'penanggung_jawab_id');
    }

    // Relasi ke aset
    public function asets()
    {
        return $this->hasMany(Aset::class);
    }

    public function kirs()
    {
        return $this->hasMany(Kir::class);
    }

    public function picHistories()
    {
        return $this->hasMany(PicHistory::class);
    }
}
