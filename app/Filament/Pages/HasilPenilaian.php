<?php

namespace App\Filament\Pages;

use App\Models\Criteria;
use App\Models\Evaluation;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HasilPenilaian extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Kalkulasi Peringkat';

    protected static ?string $title = 'Hasil Akhir Pemilihan Karyawan Terbaik per Divisi';

    protected static ?string $navigationGroup = 'Manajemen Evaluasi';

    protected static string $view = 'filament.pages.hasil-penilaian';

    /**
     * Penampung data kalkulasi agar tidak dihitung berulang kali
     */
    protected array $calculatedScores = [];
    protected array $calculatedRanks = [];

    /**
     * Konfigurasi tabel bawaan Filament
     */
    public function table(Table $table): Table
    {
        /** Jalankan kalkulasi algoritma di awal untuk mendapatkan matriks nilai */
        $this->calculateSAW();

        /** Membangun query dinamis untuk mengurutkan data berdasarkan skor yang telah dihitung */
        $caseSql = "CASE id ";
        foreach ($this->calculatedScores as $id => $score) {
            $caseSql .= "WHEN {$id} THEN {$score} ";
        }
        $caseSql .= "ELSE 0 END";

        return $table
            ->query(
                Evaluation::query()->with(['employee.department'])
            )
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByRaw("$caseSql DESC"))
            ->columns([
                TextColumn::make('peringkat')
                    ->label('Peringkat')
                    ->getStateUsing(fn (Evaluation $record) => $this->calculatedRanks[$record->id] ?? '-')
                    ->weight('bold')
                    ->alignCenter()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'warning',
                        '2' => 'gray',
                        '3' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('employee.nama_lengkap')
                    ->label('Nama Staf Profesional')
                    ->searchable()
                    ->description(fn (Evaluation $record) => "Periode Evaluasi: {$record->periode_penilaian}"),
                TextColumn::make('employee.department.nama_departemen')
                    ->label('Divisi Departemen')
                    ->badge()
                    ->color('info'),
                TextColumn::make('employee.jabatan')
                    ->label('Posisi Jabatan'),
                TextColumn::make('skor_final')
                    ->label('Skor Akhir SAW')
                    ->getStateUsing(fn (Evaluation $record) => $this->calculatedScores[$record->id] ?? 0)
                    ->numeric(decimalPlaces: 4)
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success'),
            ])
            ->defaultGroup('employee.department.nama_departemen')
            ->paginated(false);
    }

    /**
     * Logika perhitungan matriks keputusan dan normalisasi algoritma
     */
    protected function calculateSAW(): void
    {
        $kriteria = Criteria::all();
        $evaluasi = Evaluation::with(['employee.department', 'evaluationDetails.subCriteria'])->get();

        $nilaiMaks = [];
        $nilaiMin = [];
        $matriks = [];

        /** Tahap 1 Mencari Nilai Ekstrem Matriks Keputusan */
        foreach ($evaluasi as $rekam) {
            foreach ($rekam->evaluationDetails as $rincian) {
                $idK = $rincian->criteria_id;
                $poin = $rincian->subCriteria->nilai_rating ?? 0;
                $matriks[$rekam->id][$idK] = $poin;

                if (!isset($nilaiMaks[$idK]) || $poin > $nilaiMaks[$idK]) {
                    $nilaiMaks[$idK] = $poin;
                }
                if (!isset($nilaiMin[$idK]) || $poin < $nilaiMin[$idK]) {
                    $nilaiMin[$idK] = $poin;
                }
            }
        }

        $scores = [];
        $groupedByDept = [];

        /** Tahap 2 Melakukan Normalisasi dan Mengalikan Dengan Bobot */
        foreach ($evaluasi as $rekam) {
            $totalSkor = 0;
            foreach ($kriteria as $k) {
                $nilaiAsli = $matriks[$rekam->id][$k->id] ?? 0;
                $normalisasi = 0;

                if ($nilaiAsli > 0) {
                    if ($k->tipe_kriteria === 'benefit') {
                        $normalisasi = $nilaiAsli / $nilaiMaks[$k->id];
                    } else {
                        $normalisasi = $nilaiMin[$k->id] / $nilaiAsli;
                    }
                }
                $totalSkor += ($normalisasi * ($k->bobot_kriteria / 100));
            }
            
            $finalScore = round($totalSkor, 4);
            $scores[$rekam->id] = $finalScore;

            $deptName = $rekam->employee->department->nama_departemen ?? 'Tidak Ada Departemen';
            $groupedByDept[$deptName][] = [
                'id' => $rekam->id,
                'skor' => $finalScore
            ];
        }

        $this->calculatedScores = $scores;
        $ranks = [];

        /** Tahap 3 Mengurutkan dan Memberikan Peringkat Spesifik per Divisi */
        foreach ($groupedByDept as $deptName => $items) {
            usort($items, function ($kandidatA, $kandidatB) {
                return $kandidatB['skor'] <=> $kandidatA['skor'];
            });
            
            foreach ($items as $index => $item) {
                /** Diubah menjadi string agar bisa dieksekusi dengan aman pada warna badge Filament */
                $ranks[$item['id']] = (string) ($index + 1);
            }
        }

        $this->calculatedRanks = $ranks;
    }
}