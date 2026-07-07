<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicHistory extends Model
{
    protected $table = 'pic_histories';

    protected $fillable = [
        'ruangan_id',
        'pengguna_barang_lama',
        'pengguna_barang_baru',
        'pengurus_barang_lama_id',
        'pengurus_barang_baru_id',
        'penanggung_jawab_lama_id',
        'penanggung_jawab_baru_id',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function pengurusBarangLama()
    {
        return $this->belongsTo(Pegawai::class, 'pengurus_barang_lama_id');
    }

    public function pengurusBarangBaru()
    {
        return $this->belongsTo(Pegawai::class, 'pengurus_barang_baru_id');
    }

    public function penanggungJawabLama()
    {
        return $this->belongsTo(Pegawai::class, 'penanggung_jawab_lama_id');
    }

    public function penanggungJawabBaru()
    {
        return $this->belongsTo(Pegawai::class, 'penanggung_jawab_baru_id');
    }
}