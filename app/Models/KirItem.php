<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KirItem extends Model
{
    protected $table = 'aset_kir'; // sesuai nama tabel migration kamu

    protected $fillable = [
        'kir_id',
        'aset_id',
    ];

    public function kir()
    {
        return $this->belongsTo(Kir::class);
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }
}