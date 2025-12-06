<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanReviewer extends Model
{
    use HasFactory;

    protected $table = 'usulan_reviewer';

    protected $fillable = [
        'usulan_id',
        'reviewer_id',
        'status',
        'assigned_at',
        'submitted_at',
    ];

    /**
     * Relasi ke Usulan
     */
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    /**
     * Relasi ke User (Reviewer)
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
