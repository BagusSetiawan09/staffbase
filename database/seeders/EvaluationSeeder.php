<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Employee;
use App\Models\Evaluation;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    /**
     * Menjalankan proses pengisian data awal untuk transaksi penilaian kinerja staf
     */
    public function run(): void
    {
        /** Mengambil tiga puluh data karyawan secara acak untuk dijadikan sampel evaluasi */
        $karyawanTerpilih = Employee::inRandomOrder()->limit(30)->get();
        
        /** Mengambil seluruh kriteria beserta rincian parameter nilainya secara serentak */
        $semuaKriteria = Criteria::with('subCriterias')->get();

        /** Melakukan iterasi untuk setiap karyawan yang terpilih */
        foreach ($karyawanTerpilih as $karyawan) {
            
            /** Menciptakan rekam induk evaluasi untuk periode bulan Mei tahun ini */
            $evaluasi = Evaluation::create([
                'employee_id' => $karyawan->id,
                'periode_penilaian' => 'Mei 2026',
                'catatan_evaluasi' => 'Evaluasi kinerja rutin periode kuartal kedua untuk penentuan staf terbaik tingkat korporasi',
            ]);

            /** Melakukan iterasi untuk mengisi kelima nilai kriteria secara otomatis */
            foreach ($semuaKriteria as $kriteria) {
                
                /** Memilih satu parameter nilai secara acak dari kriteria yang sedang diproses */
                $subKriteriaAcak = $kriteria->subCriterias->random();

                /** Menyimpan rincian perolehan nilai ke dalam basis data transaksi */
                $evaluasi->evaluationDetails()->create([
                    'criteria_id' => $kriteria->id,
                    'sub_criteria_id' => $subKriteriaAcak->id,
                ]);
            }
        }
    }
}