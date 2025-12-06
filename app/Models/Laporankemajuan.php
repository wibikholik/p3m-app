<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKemajuan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kemajuan';

    protected $fillable = [
        'id_usulan',
        'ringkasan_kemajuan',
        'kendala',
        'persentase',
        'file_laporan',
        'status',
        'nilai_reviewer',
        'catatan_reviewer',
        'reviewer_id',
    ];

    /**
     * Relasi ke Usulan
     */
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'id_usulan');
    }

    /**
     * Relasi ke Reviewer (User)
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
