<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';
    protected $fillable = ['id_usulan', 'id_user', 'peran'];

    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'id_usulan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
