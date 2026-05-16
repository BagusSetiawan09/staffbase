<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Jalankan proses pengisian data awal untuk tabel departemen skala korporat nasional.
     */
    public function run(): void
    {
        $departments = [
            [
                'kode_departemen' => 'CORP ENG 001',
                'nama_departemen' => 'Software Engineering',
                'lokasi_kantor' => 'Sudirman Central Business District Gedung Treasury Tower Lantai 45 Jakarta Selatan',
                'deskripsi_departemen' => 'Divisi ini bertanggung jawab penuh atas pengelolaan arsitektur sistem komputasi awan pengembangan platform digital inti perusahaan serta pemeliharaan infrastruktur teknologi informasi skala besar nasional',
            ],
            [
                'kode_departemen' => 'CORP AI 002',
                'nama_departemen' => 'Artificial Intelligence and Data Research',
                'lokasi_kantor' => 'Green Office Park Gedung GOP 9 Lantai 3 BSD City Tangerang',
                'deskripsi_departemen' => 'Divisi ini berfokus pada riset pemodelan kecerdasan buatan pengolahan analitik data besar serta implementasi algoritma pembelajaran mesin untuk mendukung otomatisasi bisnis korporasi',
            ],
            [
                'kode_departemen' => 'CORP DES 003',
                'nama_departemen' => 'Product and Industrial Design',
                'lokasi_kantor' => 'Jalan Setiabudi Gedung Graha Digital Lantai 2 Kota Medan',
                'deskripsi_departemen' => 'Divisi ini berfokus pada perancangan pengalaman pengguna riset perilaku konsumen estetika antarmuka aplikasi serta penataan alur interaksi produk digital agar berjalan intuitif',
            ],
            [
                'kode_departemen' => 'CORP OPS 004',
                'nama_departemen' => 'Global Operations and Supply Chain',
                'lokasi_kantor' => 'Kawasan Industri Rungkut Gedung Logistik Utama Blok C Kota Surabaya',
                'deskripsi_departemen' => 'Divisi ini mengelola seluruh jaringan rantai pasok distribusi logistik operasional harian kantor cabang serta memastikan keandalan layanan backend sistem di seluruh wilayah Indonesia',
            ],
            [
                'kode_departemen' => 'CORP MKT 005',
                'nama_departemen' => 'Digital Marketing and Communication',
                'lokasi_kantor' => 'Jalan Asia Afrika Gedung Wisma Asia Lantai 12 Kota Bandung',
                'deskripsi_departemen' => 'Divisi ini merancang strategi kampanye kreatif manajemen reputasi publik perusahaan perluasan jangkauan merek serta analisis pertumbuhan pasar digital secara berkelanjutan',
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}