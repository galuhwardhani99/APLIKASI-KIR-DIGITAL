<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KlasifikasiBarang extends Model
{
    protected $table = 'klasifikasi_barangs';

    protected $fillable = ['kode', 'nama', 'level', 'parent_id'];

    // -------- Relasi hierarki --------
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('kode');
    }

    // Dipanggil rekursif di Blade untuk load semua level anak sekaligus
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with(['childrenRecursive', 'asets']);
    }

    // -------- Relasi ke aset fisik (hanya ada di node level paling bawah, level 6) --------
    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class);
    }

    // Label dropdown: "1.3.2.02.01.01 — KENDARAAN DINAS BERMOTOR PERORANGAN"
    public function getLabelAttribute(): string
    {
        return "{$this->kode} — {$this->nama}";
    }

    // Root nodes untuk membangun tree dari controller (level 3 = grup paling atas)
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id')->orWhere('level', 3);
    }
}
