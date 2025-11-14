<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nidn',                 // <-- TAMBAHKAN INI
        'jabatan_akademik_id',  // <-- TAMBAHKAN INI
        'role',
        'blocked_at',           // <-- (Tambahkan juga 'role' jika ada)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // (Gunakan 'hashed' jika di Laravel 10+)
    ];

    // ===================================================================
    // TAMBAHKAN FUNGSI RELASI DI BAWAH INI
    // ===================================================================

    /**
     * Mendapatkan data jabatan akademik user.
     */
    public function jabatanAkademik()
    {
        return $this->belongsTo(JabatanAkademik::class);
    }
    public function isBlocked()
{
    return !is_null($this->blocked_at);
}

}