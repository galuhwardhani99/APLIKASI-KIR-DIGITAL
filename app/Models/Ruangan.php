<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangans';

    protected $fillable = [
        'kode_lokasi',
        'nama_ruangan',
        'pengguna_barang',
        'pengurus_barang_id',
        'penanggung_jawab_id',
        'tanggal_ttd_pengurus',
        'tanggal_ttd_pj',
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
}
