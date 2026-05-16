<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eksekusi penambahan struktur tabel karyawan pada basis data.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('nomor_induk_karyawan')->unique();
            $table->string('nama_lengkap');
            $table->string('jabatan');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Eksekusi penghapusan struktur tabel karyawan dari basis data.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};