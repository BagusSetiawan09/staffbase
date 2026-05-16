<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    /**
     * Penentuan atribut yang diizinkan untuk pengisian data korporasi secara massal.
     */
    protected $fillable = [
        'kode_departemen',
        'nama_departemen',
        'lokasi_kantor',
        'deskripsi_departemen',
        'status_aktif'
    ];

    /**
     * Mendefinisikan relasi satu departemen memiliki banyak karyawan.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}