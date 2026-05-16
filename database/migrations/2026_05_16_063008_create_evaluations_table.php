<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eksekusi penambahan struktur tabel induk rekam evaluasi pada basis data
     */
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('periode_penilaian');
            $table->text('catatan_evaluasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Eksekusi penghapusan struktur tabel induk rekam evaluasi
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};