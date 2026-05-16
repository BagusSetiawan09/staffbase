<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Karyawan';

    protected static ?string $navigationGroup = 'Data Induk';

    /**
     * Konfigurasi formulir penambahan dan penyuntingan data profil staf.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profil Staf Perusahaan')
                    ->description('Pastikan data staf terisi dengan akurat untuk keperluan evaluasi kinerja')
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->relationship('department', 'nama_departemen')
                            ->label('Penempatan Departemen')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('nomor_induk_karyawan')
                            ->label('Nomor Induk Karyawan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('jabatan')
                            ->label('Posisi Jabatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('status_aktif')
                            ->label('Status Karyawan Aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    /**
     * Konfigurasi tabel untuk menampilkan daftar staf secara efisien.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_induk_karyawan')
                    ->label('Nomor Induk')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.nama_departemen')
                    ->label('Departemen')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->relationship('department', 'nama_departemen')
                    ->label('Saring Berdasarkan Departemen'),
            ])
            ->actions([
                /** Pengelompokan tombol aksi untuk efisiensi ruang tabel karyawan */
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
     * Kustomisasi tampilan rincian profil staf menggunakan antarmuka modern.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Rincian Identitas Karyawan')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('nomor_induk_karyawan')
                                    ->label('Nomor Induk')
                                    ->weight('bold')
                                    ->color('primary')
                                    ->copyable(),
                                Infolists\Components\TextEntry::make('nama_lengkap')
                                    ->label('Nama Lengkap Karyawan')
                                    ->weight('bold'),
                            ]),
                    ]),
                Infolists\Components\Section::make('Informasi Penempatan dan Status')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('department.nama_departemen')
                                    ->label('Divisi Penempatan')
                                    ->badge()
                                    ->color('info'),
                                Infolists\Components\TextEntry::make('jabatan')
                                    ->label('Posisi Jabatan Profesional'),
                                Infolists\Components\TextEntry::make('status_aktif')
                                    ->label('Status Karyawan')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Tidak Aktif')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                            ]),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}