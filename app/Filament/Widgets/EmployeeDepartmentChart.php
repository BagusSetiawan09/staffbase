<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;

class EmployeeDepartmentChart extends ChartWidget
{
    protected static ?string $heading = 'Komposisi Staf per Divisi';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '275px';

    protected function getData(): array
    {
        $departemen = Department::withCount('employees')->get();

        /** Logika dinamis untuk mengubah nama panjang menjadi singkatan akronim */
        $labelSingkatan = $departemen->map(function ($divisi) {
            $kataKunci = explode(' ', $divisi->nama_departemen);
            $akronim = '';
            
            foreach ($kataKunci as $kata) {
                /** Abaikan kata sambung agar singkatan terlihat lebih profesional */
                if (!in_array(strtolower($kata), ['and', 'dan', 'of', 'di'])) {
                    $akronim .= strtoupper(substr($kata, 0, 1));
                }
            }
            
            return $akronim;
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Staf Tersedia',
                    'data' => $departemen->pluck('employees_count')->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => true,
                    'tension' => 0,
                ],
            ],
            'labels' => $labelSingkatan,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}