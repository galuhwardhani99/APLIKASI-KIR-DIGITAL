<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawais';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'unit_kerja',
        'is_active',
    ];

    // Helper: tampilkan "NAMA — NIP"
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama} — {$this->nip}";
    }

    public function pengurusLama()
    {
        return $this->hasMany(PicHistory::class, 'pengurus_barang_lama_id');
    }

    public function pengurusBaru()
    {
        return $this->hasMany(PicHistory::class, 'pengurus_barang_baru_id');
    }

    public function pjLama()
    {
        return $this->hasMany(PicHistory::class, 'penanggung_jawab_lama_id');
    }

    public function pjBaru()
    {
        return $this->hasMany(PicHistory::class, 'penanggung_jawab_baru_id');
    }
}
