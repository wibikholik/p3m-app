<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'name',
        'display_name',
    ];

    /**
     * Relasi many-to-many ke User
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
