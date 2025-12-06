<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanPenilaianRevisi extends Model
{
    use HasFactory;

    protected $table = 'usulan_reviewer_penilaian_revisi';

    protected $fillable = [
        'usulan_id',
        'reviewer_id',
        'kriteria_id',
        'nilai',
        'catatan',
    ];

    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function komponen()
    {
        return $this->belongsTo(MasterKelengkapan::class, 'komponen_id'); // atau model komponen sesuai
    }
}
