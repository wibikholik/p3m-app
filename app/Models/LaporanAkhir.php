<?php

// App/Models/LaporanAkhir.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAkhir extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'usulan_id',
        'ringkasan_hasil',
        'file_laporan_akhir',
        'publikasi_target',
        'status',
        'nilai_reviewer',
        'catatan_reviewer',
        'reviewer_id',
        'catatan_admin'
    ];

    public function usulan()
    {
        return $this->belongsTo(\App\Models\Usulan::class, 'usulan_id');
    }
        
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
