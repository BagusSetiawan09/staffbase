<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationDetail extends Model
{
    /**
     * Penentuan atribut yang diizinkan untuk penyimpanan rincian parameter nilai evaluasi
     */
    protected $fillable = [
        'evaluation_id',
        'criteria_id',
        'sub_criteria_id'
    ];

    /**
     * Menghubungkan rincian parameter nilai kembali ke rekam evaluasi utama
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    /**
     * Menghubungkan rincian parameter dengan entitas kriteria utama yang menjadi tolak ukur
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }

    /**
     * Menghubungkan rincian parameter dengan entitas sub kriteria yang mewakili poin spesifik
     */
    public function subCriteria(): BelongsTo
    {
        return $this->belongsTo(SubCriteria::class);
    }
}