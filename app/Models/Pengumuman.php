<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman'; // harus sama dengan nama tabel di database

    protected $fillable = [
        'judul',
        'kategori',
        'isi',
        'gambar',
    ];
}
