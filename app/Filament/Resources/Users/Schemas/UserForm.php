<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
        TextInput::make('name')
            ->label('Nama Lengkap')
            ->required()
            ->maxLength(255),
            
        TextInput::make('email')
            ->label('Alamat Email')
            ->email()
            ->required()
            ->maxLength(255),
            
        TextInput::make('password')
            ->label('Password')
            ->password()
            ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // Enkripsi password
            ->dehydrated(fn ($state) => filled($state)) // Hanya ubah password jika diisi
            ->required(fn (string $context): bool => $context === 'create'), // Wajib diisi saat buat baru
            
        Select::make('role')
            ->label('Hak Akses (Role)')
            ->options([
                'admin' => 'Administrator',
                'teacher' => 'Guru',
            ])
            ->default('teacher')
            ->required(),
    ]);
    }
}
