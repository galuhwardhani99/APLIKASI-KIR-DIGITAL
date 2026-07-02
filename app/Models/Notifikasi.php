<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $fillable = [
        'jenis', 'aset_id', 'ruangan_id',
        'pesan', 'is_read', 'user_id',
    ];
}