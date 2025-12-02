<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKelengkapan extends Model
{
    protected $table = 'master_kelengkapan';
    protected $fillable = ['nama','deskripsi','is_active','order'];

    public function UsulanKelengkapan()
    {
        return $this->hasMany(UsulanKelengkapan::class, 'kelengkapan_id');
    }

}
