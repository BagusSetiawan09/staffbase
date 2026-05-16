<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eksekusi penambahan struktur tabel departemen skala korporat.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('kode_departemen')->unique(); // Contoh: DEPT-ENG-001
            $table->string('nama_departemen');
            $table->string('lokasi_kantor'); // Contoh: Infinite Loop, Cupertino atau One Hacker Way, Menlo Park
            $table->text('deskripsi_departemen')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Eksekusi penghapusan tabel departemen.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};