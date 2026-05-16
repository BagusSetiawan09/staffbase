<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * Penentuan atribut yang diizinkan untuk pengisian data secara massal.
     */
    protected $fillable = [
        'department_id',
        'nomor_induk_karyawan',
        'nama_lengkap',
        'jabatan',
        'status_aktif'
    ];

    /**
     * Menghubungkan entitas karyawan kembali ke entitas departemen.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}