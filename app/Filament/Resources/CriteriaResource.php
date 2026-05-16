<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CriteriaResource\Pages;
use App\Models\Criteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CriteriaResource extends Resource
{
    protected static ?string $model = Criteria::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Kriteria SAW';

    protected static ?string $modelLabel = 'Kriteria Penilaian';

    protected static ?string $pluralModelLabel = 'Manajemen Kriteria';

    protected static ?string $navigationGroup = 'Manajemen Evaluasi';

    /**
     * Konfigurasi formulir terpadu untuk kriteria dan sub kriteria
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Informasi Inti Kriteria')
                            ->description('Tentukan parameter utama penilaian beserta bobot kalkulasi algoritma')
                            ->schema([
                                Forms\Components\TextInput::make('kode_kriteria')
                                    ->label('Kode Kriteria')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Contoh C1'),
                                Forms\Components\TextInput::make('nama_kriteria')
                                    ->label('Nama Kriteria')
                                    ->required()
                                    ->placeholder('Contoh Kedisiplinan'),
                                Forms\Components\TextInput::make('bobot_kriteria')
                                    ->label('Bobot Persentase')
                                    ->numeric()
                                    ->required()
                                    ->suffix('%')
                                    ->minValue(1)
                                    ->maxValue(100),
                                Forms\Components\Select::make('tipe_kriteria')
                                    ->label('Tipe Kalkulasi')
                                    ->options([
                                        'benefit' => 'Benefit (Semakin Besar Semakin Baik)',
                                        'cost' => 'Cost (Semakin Kecil Semakin Baik)',
                                    ])
                                    ->required()
                                    ->native(false),
                            ])
                            ->columnSpan(1),

                        Forms\Components\Section::make('Parameter Nilai Sub Kriteria')
                            ->description('Rincian opsi penilaian yang dapat dipilih saat evaluasi dilakukan')
                            ->schema([
                                Forms\Components\Repeater::make('subCriterias')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('nama_sub_kriteria')
                                            ->label('Keterangan Parameter')
                                            ->required()
                                            ->placeholder('Contoh Sangat Baik'),
                                        Forms\Components\TextInput::make('nilai_rating')
                                            ->label('Skala Nilai')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(5)
                                            ->placeholder('1 sampai 5'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(1)
                                    ->addActionLabel('Tambah Parameter Baru')
                                    ->reorderable(false)
                                    ->collapsible(),
                            ])
                            ->columnSpan(2),
                    ]),
            ]);
    }

    /**
     * Konfigurasi tabel pemantauan kriteria penilaian
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_kriteria')
                    ->label('Kode')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('nama_kriteria')
                    ->label('Kriteria')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bobot_kriteria')
                    ->label('Bobot')
                    ->suffix('%')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('tipe_kriteria')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->badge()
                    ->color(fn (string $state): string => $state === 'benefit' ? 'info' : 'warning'),
                Tables\Columns\TextColumn::make('sub_criterias_count')
                    ->label('Jumlah Parameter')
                    ->counts('subCriterias')
                    ->alignCenter(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label('Options')
                ->icon('heroicon-m-chevron-down')
                ->color('gray')
                ->button(),
            ]);
    }

    /**
     * Tampilan rincian kriteria dan daftar parameternya
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Ringkasan Kriteria')
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('kode_kriteria')
                                    ->label('Identitas Kode')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('nama_kriteria')
                                    ->label('Nama Kriteria'),
                                Infolists\Components\TextEntry::make('bobot_kriteria')
                                    ->label('Kontribusi Bobot')
                                    ->suffix('%'),
                                Infolists\Components\TextEntry::make('tipe_kriteria')
                                    ->label('Kategori')
                                    ->badge(),
                            ]),
                    ]),
                Infolists\Components\Section::make('Daftar Parameter Penilaian')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('subCriterias')
                            ->label('Parameter Terdaftar')
                            ->schema([
                                Infolists\Components\TextEntry::make('nama_sub_kriteria')
                                    ->label('Deskripsi Nilai'),
                                Infolists\Components\TextEntry::make('nilai_rating')
                                    ->label('Poin Rating')
                                    ->weight('bold'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCriterias::route('/'),
            'create' => Pages\CreateCriteria::route('/create'),
            'edit' => Pages\EditCriteria::route('/{record}/edit'),
        ];
    }
}