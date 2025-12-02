<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nidn',
        'jabatan_akademik',
        'blocked_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'blocked_at' => 'datetime',
    ];

    /**
     * Relasi many-to-many ke Role
     */
public function usulanReviewer()
{
    return $this->belongsToMany(Usulan::class, 'usulan_reviewer', 'reviewer_id', 'usulan_id')
                ->withPivot(['status', 'assigned_at', 'deadline']);
}

    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user');
    }

    /**
     * Cek apakah user punya role tertentu
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Cek apakah user punya salah satu role dari array
     */
    public function hasAnyRole(array $roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }
    public function jabatanAkademik()
{
    return $this->belongsTo(\App\Models\JabatanAkademik::class, 'id_jabatan_akademik');
}
public function isBlocked()
    {
        return ! is_null($this->blocked_at);
    }
    public function reviewers()
{
    return $this->belongsToMany(User::class, 'usulan_reviewer', 'usulan_id', 'reviewer_id')
                ->withPivot(['status', 'assigned_at', 'deadline', 'catatan_review'])
                ->withTimestamps();
}


}
