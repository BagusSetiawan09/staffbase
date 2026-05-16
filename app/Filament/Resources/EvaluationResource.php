<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluationResource\Pages;
use App\Models\Evaluation;
use App\Models\SubCriteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Penilaian Kinerja';

    protected static ?string $modelLabel = 'Rekam Evaluasi';

    protected static ?string $pluralModelLabel = 'Manajemen Penilaian';

    protected static ?string $navigationGroup = 'Manajemen Evaluasi';

    /**
     * Konfigurasi formulir untuk melakukan proses input penilaian kinerja staf
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Informasi Utama Penilaian')
                            ->description('Tentukan identitas staf serta periode waktu pelaksanaan evaluasi')
                            ->schema([
                                Forms\Components\Select::make('employee_id')
                                    ->relationship('employee', 'nama_lengkap')
                                    ->label('Nama Karyawan')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\TextInput::make('periode_penilaian')
                                    ->label('Periode Penilaian')
                                    ->required()
                                    ->placeholder('Contoh Mei 2026'),
                                Forms\Components\Textarea::make('catatan_evaluasi')
                                    ->label('Catatan Tambahan Manajer')
                                    ->rows(3)
                                    ->placeholder('Tuliskan ringkasan singkat hasil evaluasi kerja staf'),
                            ])
                            ->columnSpan(1),

                        Forms\Components\Section::make('Rincian Parameter Penilaian SAW')
                            ->description('Berikan nilai untuk setiap kriteria berdasarkan performa nyata staf di lapangan')
                            ->schema([
                                Forms\Components\Repeater::make('evaluationDetails')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('criteria_id')
                                            ->relationship('criteria', 'nama_kriteria')
                                            ->label('Kriteria Penilaian')
                                            ->live()
                                            ->required()
                                            ->native(false),
                                        Forms\Components\Select::make('sub_criteria_id')
                                            ->label('Parameter Nilai')
                                            ->options(fn (Get $get): Collection => SubCriteria::query()
                                                ->where('criteria_id', $get('criteria_id'))
                                                ->pluck('nama_sub_kriteria', 'id'))
                                            ->required()
                                            ->native(false)
                                            ->disabled(fn (Get $get): bool => ! $get('criteria_id')),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(5)
                                    ->addActionLabel('Tambah Parameter Penilaian Lainnya')
                                    ->reorderable(false)
                                    ->collapsible(),
                            ])
                            ->columnSpan(2),
                    ]),
            ]);
    }

    /**
     * Konfigurasi tabel untuk memantau seluruh riwayat penilaian yang telah dilakukan
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.nama_lengkap')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('employee.department.nama_departemen')
                    ->label('Departemen')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('periode_penilaian')
                    ->label('Periode')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Penginputan')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('employee_id')
                    ->relationship('employee', 'nama_lengkap')
                    ->label('Saring Berdasarkan Karyawan'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label('OPTIONS')
                ->icon('heroicon-m-chevron-down')
                ->color('gray')
                ->button(),
            ]);
    }

    /**
     * Tampilan rincian penilaian untuk keperluan audit dan transparansi data
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Ringkasan Evaluasi Staf')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('employee.nama_lengkap')
                                    ->label('Nama Lengkap Karyawan')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('periode_penilaian')
                                    ->label('Periode Waktu Evaluasi'),
                                Infolists\Components\TextEntry::make('employee.department.nama_departemen')
                                    ->label('Divisi Terkait')
                                    ->badge(),
                            ]),
                    ]),
                Infolists\Components\Section::make('Daftar Perolehan Nilai Kriteria')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('evaluationDetails')
                            ->label('Hasil Penilaian Parameter')
                            ->schema([
                                Infolists\Components\TextEntry::make('criteria.nama_kriteria')
                                    ->label('Nama Kriteria Utama'),
                                Infolists\Components\TextEntry::make('subCriteria.nama_sub_kriteria')
                                    ->label('Pilihan Parameter'),
                                Infolists\Components\TextEntry::make('subCriteria.nilai_rating')
                                    ->label('Poin Perolehan')
                                    ->weight('bold')
                                    ->color('primary'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluations::route('/'),
            'create' => Pages\CreateEvaluation::route('/create'),
            'edit' => Pages\EditEvaluation::route('/{record}/edit'),
        ];
    }
}