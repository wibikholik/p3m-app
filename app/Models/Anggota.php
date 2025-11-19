<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';
    
    protected $fillable = [
        'id_usulan',
        'nama',
        'nidn',
        'jabatan',
    ];

    // Relasi Balik ke Usulan
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'id_usulan');
    }
}