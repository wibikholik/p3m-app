<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPenilaian extends Model
{
    protected $table = 'master_penilaian';

    protected $fillable = [
        'nama', 'deskripsi', 'bobot', 'order', 'is_active'
    ];
}