<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kir extends Model
{
    protected $table = 'kirs';

    protected $fillable = [
        'ruangan_id',
        'pengguna_barang',
        'pengurus_barang_id',
        'penanggung_jawab_id',
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

    public function items()
    {
        return $this->hasMany(KirItem::class, 'kir_id', 'id');
    }

    public function asets()
    {
        return $this->belongsToMany(Aset::class, 'aset_kir', 'kir_id', 'aset_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNomor(string $tanggal): string
    {
        $tahun = date('Y', strtotime($tanggal));
        $count = self::whereYear('tanggal', $tahun)->count() + 1;
        return sprintf('KIR/%s/%03d', $tahun, $count);
    }
}