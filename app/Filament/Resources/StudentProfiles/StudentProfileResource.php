<?php

namespace App\Filament\Resources\StudentProfiles;

use App\Filament\Resources\StudentProfiles\Pages;
use App\Filament\Resources\Students\Schemas\StudentForm;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class StudentProfileResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification'; 
    
    protected static ?string $navigationLabel = 'Profil Siswa';
    protected static ?string $pluralModelLabel = 'Profil Siswa';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary') // Mengubah teks jadi warna biru
                    // 👉 JURUS ANTI ERROR: Kolom nama ini kita sulap jadi link ke profil!
                    ->url(fn ($record): string => static::getUrl('view', ['record' => $record])),
                    
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('Kelas'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // KOSONGKAN TOTAL BAGIAN INI AGAR TIDAK ADA ERROR ACTION LAGI
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Students\RelationManagers\GradesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentProfiles::route('/'),
            'view' => Pages\ViewStudentProfile::route('/{record}'),
        ];
    }
}