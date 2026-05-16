<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Departemen';

    protected static ?string $navigationGroup = 'Data Induk';

    /**
     * Konfigurasi formulir penambahan dan penyuntingan data departemen.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama Departemen')
                    ->description('Lengkapi detail identitas departemen untuk keperluan audit internal')
                    ->schema([
                        Forms\Components\TextInput::make('kode_departemen')
                            ->label('Kode Departemen')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh CORP ENG 001'),
                        Forms\Components\TextInput::make('nama_departemen')
                            ->label('Nama Departemen')
                            ->required(),
                        Forms\Components\TextInput::make('lokasi_kantor')
                            ->label('Lokasi Kantor Global')
                            ->required()
                            ->placeholder('Contoh Kota Jakarta Selatan'),
                        Forms\Components\Select::make('status_aktif')
                            ->label('Status Operasional')
                            ->options([
                                true => 'Aktif',
                                false => 'Tidak Aktif',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('deskripsi_departemen')
                            ->label('Rincian Tugas dan Deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    /**
     * Konfigurasi tabel untuk menampilkan daftar departemen tanpa kolom status.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_departemen')
                    ->label('Kode')
                    ->copyable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('nama_departemen')
                    ->label('Nama Departemen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi_kantor')
                    ->label('Lokasi Kantor')
                    ->searchable(),
            ])
            ->actions([
                /** Pengelompokan tombol aksi untuk efisiensi ruang antarmuka */
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
     * Kustomisasi rincian data menggunakan antarmuka modern.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Rincian Profil Departemen')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('kode_departemen')
                                    ->label('Kode Identitas')
                                    ->weight('bold')
                                    ->color('primary'),
                                Infolists\Components\TextEntry::make('nama_departemen')
                                    ->label('Nama Entitas'),
                                Infolists\Components\TextEntry::make('lokasi_kantor')
                                    ->label('Lokasi Kantor'),
                            ]),
                    ]),
                Infolists\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Infolists\Components\TextEntry::make('deskripsi_departemen')
                            ->label('Deskripsi Tugas Departemen')
                            ->markdown(),
                        Infolists\Components\TextEntry::make('status_aktif')
                            ->label('Status Operasional')
                            ->badge()
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Tidak Aktif')
                            ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}