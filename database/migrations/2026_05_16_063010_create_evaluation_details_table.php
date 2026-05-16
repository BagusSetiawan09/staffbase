<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eksekusi penambahan struktur tabel rincian nilai evaluasi pada basis data
     */
    public function up(): void
    {
        Schema::create('evaluation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('criterias')->cascadeOnDelete();
            $table->foreignId('sub_criteria_id')->constrained('sub_criterias')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Eksekusi penghapusan struktur tabel rincian nilai evaluasi
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_details');
    }
};