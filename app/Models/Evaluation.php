<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    /**
     * Penentuan atribut yang diizinkan untuk penyimpanan data rekam evaluasi
     */
    protected $fillable = [
        'employee_id',
        'periode_penilaian',
        'catatan_evaluasi'
    ];

    /**
     * Menghubungkan rekam evaluasi dengan entitas karyawan yang dinilai
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Menghubungkan rekam evaluasi utama dengan rincian parameter nilainya
     */
    public function evaluationDetails(): HasMany
    {
        return $this->hasMany(EvaluationDetail::class);
    }
}