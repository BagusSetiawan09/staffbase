# StaffBase — Enterprise Employee Decision Support System

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-v3.x-amber.svg)](https://filamentphp.com)
[![License](https://img.shields.io/badge/License-Enterprise--Proprietary-blue.svg)](#)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%20%7C%208.3-777bb4.svg)](https://www.php.net)

**StaffBase** adalah platform digital berskala *Enterprise* yang dirancang khusus sebagai Sistem Pendukung Keputusan (SPK) untuk manajemen evaluasi, penilaian, dan penentuan peringkat karyawan terbaik secara objektif. Menggunakan implementasi matematis murni dari algoritma **Simple Additive Weighting (SAW)**, sistem ini mentransformasikan data kinerja mentah menjadi wawasan keputusan yang bebas dari bias subjektivitas (*human-bias reduction*).

Dibangun di atas fondasi ekosistem modern **Laravel 11** dan **Filament PHP v3**, StaffBase menggabungkan performa komputasi tingkat tinggi, arsitektur kode yang terdekopling (*decoupled architecture*), sistem keamanan berlapis, serta visualisasi antarmuka berstandar industri (*Brutalist Minimalist UI/UX*).

---

## Arsitektur Sistem & Spesifikasi Enterprise

Berbeda dengan aplikasi purwarupa standar, StaffBase mengadopsi pola arsitektur **Service-Repository Pattern** untuk memisahkan logika matematika SPK dari lapisan kontroler, memastikan skalabilitas tinggi saat menangani ribuan baris data evaluasi instansional.

### Fitur Unggulan Tingkat Produksi

1. **Decoupled SAW Core Engine Service**
   Seluruh kalkulasi matriks keputusan, pencarian nilai ekstrem (Maks/Min), tahap normalisasi ($R_{ij}$), hingga kalkulasi bobot preferensi akhir ($V_i$) diisolasi sepenuhnya ke dalam *Service Layer*. Logika ini dapat dikonsumsi ulang secara fleksibel oleh antarmuka Web Panel, RESTful API, maupun *Background Worker*.

2. **Enterprise Performance Optimization & Caching Strategy**
   Guna menghindari degradasi performa server (*request-timeout*) akibat komputasi matriks berulang pada basis data besar, StaffBase dilengkapi strategi enkapsulasi **Cache Tiering** (Redis/Memcached) selama 30 menit, yang secara otomatis di-*invalidate* hanya ketika terjadi transaksi penilaian baru.

3. **Robust Security, RBAC & Audit Trail Audit-Ready**
   Perlindungan data sensitif nilai performa karyawan dikunci rapat menggunakan *Role-Based Access Control* (RBAC) via `spatie/laravel-permission`:
   - **Direksi / Pimpinan**: Akses eksklusif pembacaan laporan eksekutif dan dasbor analitik makro.
   - **Manajer HRD**: Hak otorisasi penuh konfigurasi parameter kriteria, pembobotan, dan pengesahan final.
   - **Supervisor / Kepala Divisi**: Hak input transaksi evaluasi terbatas pada karyawan di bawah yurisdiksi divisinya.
   - **Audit Trail Logs**: Setiap manipulasi data performa direkam jejaknya (*who, when, what, old values, new values*) menggunakan `spatie/laravel-activitylog` untuk kepatuhan regulasi audit internal.

4. **Dynamic Contextual Criteria Allocation**
   Sistem mendukung skema parameterisasi kriteria yang dinamis per divisi kerja (misal: kriteria divisi *Engineering* berbeda bobot dengan divisi *Sales*), menggantikan pola kriteria global yang kaku.

5. **Formal Enterprise Document Reporting (DomPDF Implementation)**
   Modul pencetakan dokumen fisik mandiri berbentuk PDF formal berskala kertas A4 baku. Laporan didesain presisi mengikuti standar tata naskah dinas korporat, lengkap dengan struktur Kop Surat resmi, penomoran arsip otomatis, pembagian kluster kolom, serta blok otentikasi tanda tangan basah direksi.

6. **Premium Visual Antarmuka (Brutalist Minimalist UI/UX)**
   Halaman Beranda (*Landing Page*) dirancang menggunakan estetika modern *dark mode* monokromatik Vercel/Next.js, dilengkapi jaring latar belakang (*subtle grid layout*), tipografi *Inter*, animasi interaksi *staggered wave per character*, serta pratinjau tabel klasemen atas yang dapat digulir secara dramatis tanpa merusak tata letak.

---

## Pemodelan Matematis Algoritma SAW

Metode *Simple Additive Weighting* (SAW) sering dikenal sebagai metode penjumlahan terbobot. Konsep dasar SAW adalah mencari penjumlahan terbobot dari rating kinerja pada setiap alternatif pada semua kriteria.

### 1. Parameter Kriteria Aktif ($W$)

Sistem saat ini dikonfigurasi secara baku dengan 5 kriteria utama pembentuk bobot preferensi:

| Kode | Nama Parameter Kriteria | Tipe Kriteria | Bobot Nilai ($W_j$) |
| :---: | :--- | :---: | :---: |
| **C1** | Pencapaian Target Kinerja (KPI) | *Benefit* | 30% (0.30) |
| **C2** | Kualitas Hasil Pekerjaan | *Benefit* | 25% (0.25) |
| **C3** | Inisiatif & Pemecahan Masalah | *Benefit* | 20% (0.20) |
| **C4** | Kerja Sama Tim & Komunikasi | *Benefit* | 15% (0.15) |
| **C5** | Tingkat Keterlambatan / Absensi | *Cost* | 10% (0.10) |

### 2. Rumus Normalisasi Matriks ($R_{ij}$)

Matriks keputusan awal ($X$) dari rating kecocokan alternatif dikonversi menjadi matriks ternormalisasi ($R$) berdasarkan sifat kriteria:

- **Kriteria Benefit (Keuntungan):**
  $$R_{ij} = \frac{X_{ij}}{\max(X_{ij})}$$
  *(Jika nilai memberikan keuntungan, maka nilai alternatif dibagi dengan nilai maksimal dari kolom kriteria tersebut)*

- **Kriteria Cost (Biaya/Kerugian):**
  $$R_{ij} = \frac{\min(X_{ij})}{X_{ij}}$$
  *(Jika nilai memberikan kerugian/biaya, maka nilai minimal dari kolom kriteria dibagi dengan nilai alternatif tersebut)*

### 3. Rumus Nilai Preferensi Akhir ($V_i$)

Nilai preferensi akhir untuk masing-masing alternatif ($V_i$) dihitung dengan mengalikan baris matriks ternormalisasi ($R$) terhadap baris vektor bobot kriteria ($W$):

$$V_i = \sum_{j=1}^{n} W_j R_{ij}$$

Alternatif dengan nilai $V_i$ tertinggi diposisikan oleh sistem pada Peringkat 1 sebagai rekomendasi keputusan terbaik.

---

## Struktur Direktori Utama

```text
staffbase/
├── app/
│   ├── Filament/
│   │   ├── Pages/
│   │   │   └── HasilPenilaian.php       # Panel Hasil Perhitungan Global & Aksi Eksportir
│   │   └── Widgets/
│   │       └── StatsOverview.php        # Real-time Dashboard Analitik Widget (SAW Top 1)
│   ├── Models/
│   │   ├── Employee.php                 # Entitas Alternatif Karyawan
│   │   ├── Criteria.php                 # Entitas Bobot & Tipe Kriteria (Benefit/Cost)
│   │   ├── Evaluation.php               # Transaksi Induk Evaluasi Performa
│   │   └── EvaluationDetail.php         # Transaksi Rincian Nilai Rating Kriteria
│   └── Services/
│       └── SAWCalculationService.php    # Pusat Inti Komputasi Matematika Algoritma SAW
├── database/
│   ├── migrations/                      # Skema Relasi Database Integritas Tinggi
│   └── seeders/                         # Data Seeder Komprehensif untuk Skenario 5 Kandidat
├── resources/
│   ├── css/
│   │   └── app.css                      # Custom Token Styling Tailwind CSS
│   └── views/
│       ├── pdf/
│       │   └── laporan-peringkat.blade.php # Layout Formal Cetak Surat Keputusan A4 (DomPDF)
│       ├── dokumentasi.blade.php        # Panduan Interaktif Simulasi Langkah Perhitungan Matriks
│       └── welcome.blade.php            # Next.js Style Brutalist Animation Landing Page
└── routes/
    └── web.php                          # Deklarasi Route Pemrosesan Stream PDF & Dynamic Content