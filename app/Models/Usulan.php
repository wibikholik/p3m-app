<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usulan extends Model
{
    use HasFactory;

    protected $table = 'usulans';
    
    protected $fillable = [
        'id_user',
        'email_ketua',
        'id_pengumuman',
        'judul',
        'skema',
        'abstrak',
        'file_usulan',
        'status',
    ];

    // Relasi ke User (Ketua)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Pengumuman
    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'id_pengumuman');
    }

    // Relasi ke Anggota
    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_usulan');
    }

    public function pengusul()
    {
    return $this->belongsTo(User::class, 'id_user');
    }

}