<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;


class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // KOTAK 1: Informasi Utama & Sekolah
                Section::make('Informasi Utama')
                    ->description('Biodata dasar dan penempatan kelas siswa.')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto Siswa')
                            ->image()
                            ->directory('student-photos') // Akan disimpan di folder storage/app/public/student-photos
                            ->maxSize(10024) // Maksimal ukuran file 10MB
                            ->columnSpanFull(), // Foto mengambil lebar penuh
                            
                        TextInput::make('nis')
                            ->label('NIS')
                            ->required(),
                            
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required(),
                            
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan'=> 'Perempuan',
                            ])
                            ->required(),
                            
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir'),
                            
                        Select::make('classroom_id')
                            ->relationship('classroom', 'name')
                            ->label('Kelas')
                            ->required(),
                            
                        Select::make('academic_year_id')
                            ->relationship('academicYear', 'name')
                            ->label('Tahun Ajaran')
                            ->required(),
                            
                        DatePicker::make('enrolled_at')
                            ->label('Tanggal Terdaftar')
                            ->default(now()),
                    ])->columns(2), // Membagi inputan menjadi 2 kolom bersebelahan

                // KOTAK 2: Data Keluarga & Alamat
                Section::make('Informasi Tambahan')
                    ->schema([
                        Textarea::make('address')
                            ->label('Alamat Rumah')
                            ->columnSpanFull(),
                            
                        TextInput::make('guardian_name')
                            ->label('Nama Wali Murid'),
                            
                        TextInput::make('guardian_phone')
                            ->label('Nomor Telepon Wali')
                            ->tel(),
                    ])->columns(2),

                // KOTAK 3: Poin Kedisiplinan
                Section::make('Sistem Poin')
                    ->schema([
                        TextInput::make('merit_points')
                            ->label('Poin Kebaikan')
                            ->numeric()
                            ->default(0),
                            
                        TextInput::make('demerit_points')
                            ->label('Poin Kesalahan')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }
}