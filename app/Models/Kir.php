<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kir extends Model
{
    protected $table = 'kirs';

    protected $fillable = [
        'ruangan_id',
        'nomor_kir',
        'tanggal',
        'tahun',
        'status',
        'pengguna_barang',
        'pengurus_barang_id',
        'penanggung_jawab_id',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tahun'   => 'integer',
        'tanggal' => 'date',
    ];

    // ── Accessor: $kir->nomor_kir ─────────────────────────────────────────
    // Format: KIR/2026/001
    public function getNomorKirAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return sprintf(
            'KIR/%s/%03d',
            $this->tahun ?? date('Y'),
            $this->id
        );
    }

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
        return $this->belongsToMany(
            Aset::class,
            'aset_kir',
            'kir_id',
            'aset_id'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNomor(string $tahun): string
    {
        $count = self::where('tahun', $tahun)->count() + 1;

        return sprintf(
            'KIR/%s/%03d',
            $tahun,
            $count
        );
    }
}