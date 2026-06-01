<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nip')
                    ->label('NIP'),
                    
                // 👉 1. Kita kembalikan input Nama Guru
                TextInput::make('name')
                    ->label('Nama Guru')
                    ->required(),
                    
                // 👉 2. Dropdown akun login kita beri label agar tidak bernama "User"
                Select::make('user')
                    ->relationship('user', 'name')
                    ->label('Akun Login (Pilih Akun)')
                    ->searchable()
                    ->preload(),
                    
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan'=> 'Perempuan',
                    ])
                    ->required(),
                    
                TextInput::make('phone_number')
                    ->label('Nomor Telepon')
                    ->tel(),
            ]);
    }
}