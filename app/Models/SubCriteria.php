<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCriteria extends Model
{
    /**
     * Penentuan atribut yang diizinkan untuk penyimpanan data parameter nilai
     */
    protected $fillable = [
        'criteria_id',
        'nama_sub_kriteria',
        'nilai_rating'
    ];

    /**
     * Menghubungkan parameter sub kriteria kembali ke entitas kriteria utama
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
}