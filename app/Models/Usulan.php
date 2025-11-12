<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usulan extends Model
{
    use HasFactory;

    protected $table = 'usulan';
    protected $fillable = [
        'id_dosen',
        'id_pengumuman',
        'judul',
        'skema',
        'deskripsi',
        'tahun_pelaksanaan',
        'file_lampiran',
        'status'
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'id_pengumuman');
    }

    public function rabs()
    {
        return $this->hasMany(Rab::class, 'id_usulan');
    }

    public function anggotas()
    {
        return $this->hasMany(Anggota::class, 'id_usulan');
    }
}

