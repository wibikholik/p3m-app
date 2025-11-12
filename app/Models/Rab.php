<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    use HasFactory;

    protected $table = 'rabs';
    protected $fillable = ['id_usulan', 'nama_item', 'jumlah', 'harga_satuan'];

    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'id_usulan');
    }
}
