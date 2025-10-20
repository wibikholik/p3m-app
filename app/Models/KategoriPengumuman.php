<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengumuman extends Model
{
    protected $table = 'kategori_pengumuman';
    protected $fillable = [
        'nama_kategori',
    ];
}
