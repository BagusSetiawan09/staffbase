<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eksekusi penambahan struktur tabel kriteria pada basis data sistem
     */
    public function up(): void
    {
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria')->unique();
            $table->string('nama_kriteria');
            $table->integer('bobot_kriteria');
            $table->enum('tipe_kriteria', ['benefit', 'cost']);
            $table->timestamps();
        });
    }

    /**
     * Eksekusi penghapusan struktur tabel kriteria
     */
    public function down(): void
    {
        Schema::dropIfExists('criterias');
    }
};