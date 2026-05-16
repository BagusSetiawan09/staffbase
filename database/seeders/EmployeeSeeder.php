<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Menjalankan proses pengisian data awal untuk tabel karyawan skala besar
     */
    public function run(): void
    {
        /** Mempersiapkan pembuat data tiruan dengan format nama wilayah Indonesia */
        $faker = Faker::create('id_ID');
        
        /** Mengambil seluruh data departemen yang telah terdaftar pada basis data */
        $departemenTersedia = Department::all();

        /** Daftar pilihan jabatan profesional untuk variasi posisi staf */
        $pilihanJabatan = [
            'Manajer Senior',
            'Spesialis Tingkat Lanjut',
            'Analis Sistem Madya',
            'Pimpinan Proyek Digital',
            'Konsultan Internal Korporasi',
            'Staf Ahli Operasional',
            'Peneliti Data Senior'
        ];

        /** Melakukan iterasi untuk menyuntikkan karyawan ke setiap departemen */
        foreach ($departemenTersedia as $departemen) {
            
            /** Menciptakan dua puluh lima karyawan untuk setiap departemen */
            /** Total akhir menjadi seratus dua puluh lima karyawan terdaftar */
            for ($indeks = 1; $indeks <= 25; $indeks++) {
                
                /** Menghasilkan nomor induk unik gabungan kode departemen dan angka acak */
                $nomorAcak = $faker->unique()->numerify('#####');
                $nomorInduk = 'EMP' . $departemen->id . $nomorAcak;

                /** Mengambil satu posisi jabatan secara acak dari daftar yang tersedia */
                $jabatanAcak = $faker->randomElement($pilihanJabatan);

                Employee::create([
                    'department_id' => $departemen->id,
                    'nomor_induk_karyawan' => $nomorInduk,
                    'nama_lengkap' => $faker->name(),
                    'jabatan' => $jabatanAcak,
                    'status_aktif' => true,
                ]);
            }
        }
    }
}