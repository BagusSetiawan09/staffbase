<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan seluruh proses pengisian data awal aplikasi.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            CriteriaSeeder::class,
            EvaluationSeeder::class,
        ]);
    }
}