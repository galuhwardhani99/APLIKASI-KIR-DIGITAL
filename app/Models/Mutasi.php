<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $fillable = [
        'aset_id',
        'ruangan_asal_id',
        'ruangan_tujuan_id',
        'pemohon_id',
        'tanggal_pengajuan',
        'alasan',
        'status',
        'penerima_id',
        'tanggal_validasi',
        'catatan_validasi'
    ];


    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_validasi' => 'date'
    ];


    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }


    public function ruanganAsal()
    {
        return $this->belongsTo(
            Ruangan::class,
            'ruangan_asal_id'
        );
    }


    public function ruanganTujuan()
    {
        return $this->belongsTo(
            Ruangan::class,
            'ruangan_tujuan_id'
        );
    }


    public function pemohon()
    {
        return $this->belongsTo(
            Pegawai::class,
            'pemohon_id'
        );
    }


    public function auditor()
    {
        return $this->belongsTo(
            User::class,
            'penerima_id'
        );
    }
}