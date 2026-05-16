<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    /**
     * Penentuan atribut yang diizinkan untuk penyimpanan data kriteria evaluasi
     */
    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'bobot_kriteria',
        'tipe_kriteria'
    ];

    /**
     * Menghubungkan satu kriteria utama dengan banyak parameter sub kriteria
     */
    public function subCriterias(): HasMany
    {
        return $this->hasMany(SubCriteria::class);
    }
}