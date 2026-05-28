<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Evaluation;
use Filament\Widgets\ChartWidget;

class EvaluationChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Penilaian per Divisi';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '275px';

    protected function getData(): array
    {
        $departemen = Department::all();
        $jumlahEvaluasi = [];
        $labelSingkatan = [];

        foreach ($departemen as $divisi) {
            /** Logika dinamis untuk mengubah nama panjang menjadi singkatan akronim */
            $kataKunci = explode(' ', $divisi->nama_departemen);
            $akronim = '';
            
            foreach ($kataKunci as $kata) {
                if (!in_array(strtolower($kata), ['and', 'dan', 'of', 'di'])) {
                    $akronim .= strtoupper(substr($kata, 0, 1));
                }
            }
            $labelSingkatan[] = $akronim;
            
            /** Menghitung jumlah evaluasi berdasarkan relasi staf di divisi tersebut */
            $total = Evaluation::whereHas('employee', function ($kueri) use ($divisi) {
                $kueri->where('department_id', $divisi->id);
            })->count();
            
            $jumlahEvaluasi[] = $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Rekam Evaluasi',
                    'data' => $jumlahEvaluasi,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
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