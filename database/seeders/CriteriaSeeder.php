<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Menjalankan proses pengisian data awal untuk tabel kriteria dan parameternya
     */
    public function run(): void
    {
        /** Kriteria Pertama Pencapaian Target Kinerja */
        $kriteriaSatu = Criteria::create([
            'kode_kriteria' => 'C01',
            'nama_kriteria' => 'Pencapaian Target Kinerja',
            'bobot_kriteria' => 30,
            'tipe_kriteria' => 'benefit',
        ]);

        $kriteriaSatu->subCriterias()->createMany([
            ['nama_sub_kriteria' => 'Melampaui Target Secara Signifikan', 'nilai_rating' => 5],
            ['nama_sub_kriteria' => 'Memenuhi Target Secara Penuh', 'nilai_rating' => 4],
            ['nama_sub_kriteria' => 'Hampir Memenuhi Target', 'nilai_rating' => 3],
            ['nama_sub_kriteria' => 'Kurang Memenuhi Target', 'nilai_rating' => 2],
            ['nama_sub_kriteria' => 'Sangat Kurang Dari Target', 'nilai_rating' => 1],
        ]);

        /** Kriteria Kedua Kualitas Hasil Pekerjaan */
        $kriteriaDua = Criteria::create([
            'kode_kriteria' => 'C02',
            'nama_kriteria' => 'Kualitas Hasil Pekerjaan',
            'bobot_kriteria' => 25,
            'tipe_kriteria' => 'benefit',
        ]);

        $kriteriaDua->subCriterias()->createMany([
            ['nama_sub_kriteria' => 'Sangat Akurat dan Sempurna', 'nilai_rating' => 5],
            ['nama_sub_kriteria' => 'Akurat dengan Sedikit Perbaikan', 'nilai_rating' => 4],
            ['nama_sub_kriteria' => 'Cukup Akurat Namun Butuh Revisi', 'nilai_rating' => 3],
            ['nama_sub_kriteria' => 'Banyak Kesalahan Minor', 'nilai_rating' => 2],
            ['nama_sub_kriteria' => 'Banyak Kesalahan Fatal', 'nilai_rating' => 1],
        ]);

        /** Kriteria Ketiga Inisiatif dan Pemecahan Masalah */
        $kriteriaTiga = Criteria::create([
            'kode_kriteria' => 'C03',
            'nama_kriteria' => 'Inisiatif dan Pemecahan Masalah',
            'bobot_kriteria' => 20,
            'tipe_kriteria' => 'benefit',
        ]);

        $kriteriaTiga->subCriterias()->createMany([
            ['nama_sub_kriteria' => 'Sangat Proaktif Memberikan Solusi', 'nilai_rating' => 5],
            ['nama_sub_kriteria' => 'Sering Memberikan Ide Baru', 'nilai_rating' => 4],
            ['nama_sub_kriteria' => 'Cukup Mampu Menyelesaikan Masalah', 'nilai_rating' => 3],
            ['nama_sub_kriteria' => 'Kurang Inisiatif Menunggu Perintah', 'nilai_rating' => 2],
            ['nama_sub_kriteria' => 'Sangat Pasif Dalam Bekerja', 'nilai_rating' => 1],
        ]);

        /** Kriteria Keempat Kolaborasi dan Kerja Sama Tim */
        $kriteriaEmpat = Criteria::create([
            'kode_kriteria' => 'C04',
            'nama_kriteria' => 'Kolaborasi dan Kerja Sama Tim',
            'bobot_kriteria' => 10,
            'tipe_kriteria' => 'benefit',
        ]);

        $kriteriaEmpat->subCriterias()->createMany([
            ['nama_sub_kriteria' => 'Sangat Mengayomi dan Kooperatif', 'nilai_rating' => 5],
            ['nama_sub_kriteria' => 'Aktif Berkolaborasi Dalam Tim', 'nilai_rating' => 4],
            ['nama_sub_kriteria' => 'Bisa Bekerja Sama Dalam Batas Wajar', 'nilai_rating' => 3],
            ['nama_sub_kriteria' => 'Cenderung Bekerja Secara Individu', 'nilai_rating' => 2],
            ['nama_sub_kriteria' => 'Sulit Berkoordinasi Dengan Rekan', 'nilai_rating' => 1],
        ]);

        /** Kriteria Kelima Tingkat Keterlambatan dan Izin */
        /** Kriteria ini bertipe cost karena semakin sering absen maka semakin buruk nilainya */
        $kriteriaLima = Criteria::create([
            'kode_kriteria' => 'C05',
            'nama_kriteria' => 'Tingkat Keterlambatan dan Izin',
            'bobot_kriteria' => 15,
            'tipe_kriteria' => 'cost',
        ]);

        $kriteriaLima->subCriterias()->createMany([
            ['nama_sub_kriteria' => 'Nol Sampai Dua Kali Absen', 'nilai_rating' => 1],
            ['nama_sub_kriteria' => 'Tiga Sampai Empat Kali Absen', 'nilai_rating' => 2],
            ['nama_sub_kriteria' => 'Lima Sampai Enam Kali Absen', 'nilai_rating' => 3],
            ['nama_sub_kriteria' => 'Tujuh Sampai Delapan Kali Absen', 'nilai_rating' => 4],
            ['nama_sub_kriteria' => 'Lebih Dari Sembilan Kali Absen', 'nilai_rating' => 5],
        ]);
    }
}