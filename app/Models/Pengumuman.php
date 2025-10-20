<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'pengumuman';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'isi',
        'gambar',
        'kategori_id',
        'tanggal_mulai',
        'tanggal_akhir',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model KategoriPengumuman.
     * Setiap pengumuman memiliki satu kategori.
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriPengumuman::class, 'kategori_id');
    }
}
