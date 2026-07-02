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

    // Ruangan di mana pegawai ini sebagai Pengurus Barang
    public function ruanganSebagaiPengurus()
    {
        return $this->hasMany(Ruangan::class, 'pengurus_barang_id');
    }

    // Ruangan di mana pegawai ini sebagai Penanggung Jawab
    public function ruanganSebagaiPJ()
    {
        return $this->hasMany(Ruangan::class, 'penanggung_jawab_id');
    }

    // Helper: tampilkan "NAMA — NIP"
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama} — {$this->nip}";
    }
}
