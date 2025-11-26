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
    public function reviewers()
{
    return $this->belongsToMany(\App\Models\User::class, 'usulan_reviewer', 'usulan_id', 'reviewer_id')
        ->withPivot(['id','assigned_by','assigned_at','deadline','status','catatan_assign','catatan_reviewer'])
        ->withTimestamps();
}
// App/Models/User.php (tambah method)
public function reviewerTasks()
{
    return $this->belongsToMany(\App\Models\Usulan::class, 'usulan_reviewer', 'reviewer_id', 'usulan_id')
        ->withPivot(['id','assigned_by','assigned_at','deadline','status','catatan_assign','catatan_reviewer'])
        ->withTimestamps();
}

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