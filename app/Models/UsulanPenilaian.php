<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsulanPenilaian extends Model
{
    protected $table = 'usulan_penilaian';

    protected $fillable = [
        'usulan_id', 'reviewer_id', 'komponen_id', 'nilai', 'catatan'
    ];

    public function komponen()
    {
        return $this->belongsTo(MasterPenilaian::class, 'komponen_id');
    }
}
