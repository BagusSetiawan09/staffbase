<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eksekusi penambahan struktur tabel sub kriteria pada basis data sistem
     */
    public function up(): void
    {
        Schema::create('sub_criterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('criterias')->cascadeOnDelete();
            $table->string('nama_sub_kriteria');
            $table->integer('nilai_rating');
            $table->timestamps();
        });
    }

    /**
     * Eksekusi penghapusan struktur tabel sub kriteria
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_criterias');
    }
};