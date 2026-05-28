<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Evaluation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    /** Menetapkan posisi paling atas pada dasbor */
    protected static ?int $sort = 1;

    /** Memaksa komponen untuk mengambil lebar penuh layar */
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Divisi Operasional', Department::count())
                ->description('Seluruh departemen perusahaan yang terdaftar')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
                
            Stat::make('Total Staf Profesional', Employee::where('status_aktif', true)->count())
                ->description('Jumlah karyawan aktif di seluruh divisi')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Total Rekam Penilaian', Evaluation::count())
                ->description('Dokumen evaluasi kinerja yang telah masuk')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
        ];
    }
}