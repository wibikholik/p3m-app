<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsulanKelengkapan extends Model
{
    protected $table = 'usulan_kelengkapan';
    protected $fillable = ['usulan_id','kelengkapan_id','status','catatan','checked_by'];

    public function master()
    {
        return $this->belongsTo(MasterKelengkapan::class, 'kelengkapan_id');
    }

    public function usulan()
    {
        return $this->belongsTo(\App\Models\Usulan::class, 'usulan_id');
    }
}
