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
        'file_revisi',
        'status',
        'status_revisi',
        'catatan_revisi_admin',
        'tanggal_revisi',
        'status_lanjut',
    ];

    protected $casts = [
        'tanggal_revisi' => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    /**
     * ============================
     *   RELASI REVIEWER
     * ============================
     */
    public function reviewers()
{
    return $this->belongsToMany(
        User::class,
        'usulan_reviewer',
        'usulan_id',
        'reviewer_id'
    )->withPivot('status','assigned_at','submitted_at')->withTimestamps();
}

    /**
     * ============================
     *   RELASI PENGUSUL
     * ============================
     */
    public function pengusul()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * ============================
     *   RELASI PENGUMUMAN
     * ============================
     */
    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'id_pengumuman');
    }

    /**
     * ============================
     *   RELASI ANGGOTA
     * ============================
     */
    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_usulan');
    }

    /**
     * ============================
     *   RELASI KELENGKAPAN
     * ============================
     */
    public function kelengkapan()
    {
        return $this->hasMany(UsulanKelengkapan::class, 'usulan_id');
    }

    /**
     * ============================
     *   PENILAIAN AWAL
     * ============================
     */
    public function penilaian()
    {
        return $this->hasMany(UsulanPenilaian::class, 'usulan_id');
    }

    public function penilaianPerReviewer($reviewer_id)
    {
        return $this->penilaian()->where('reviewer_id', $reviewer_id);
    }

    /**
     * ============================
     *   PENILAIAN REVISI
     * ============================
     */
    public function penilaianRevisi()
    {
        return $this->hasMany(UsulanPenilaianRevisi::class, 'usulan_id');
    }

    public function penilaianRevisiPerReviewer($reviewer_id)
    {
        return $this->penilaianRevisi()->where('reviewer_id', $reviewer_id);
    }

    /**
     * ============================
     *   LAPORAN KEMAJUAN
     * ============================
     */
    public function laporanKemajuan()
    {
        return $this->hasMany(LaporanKemajuan::class, 'id_usulan');
    }
    public function laporanAkhir()
    {
        // Karena di tabel laporan_akhirs kita menggunakan foreign key 'usulan_id'
        // Ini adalah relasi one-to-one
        return $this->hasOne(LaporanAkhir::class, 'usulan_id');
    }
}

