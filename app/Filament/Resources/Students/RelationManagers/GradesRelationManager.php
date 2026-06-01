<?php

namespace App\Filament\Resources\Students\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradesRelationManager extends RelationManager
{
    protected static string $relationship = 'grades';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->label('Mata Pelajaran')
                    ->required(),
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->label('Tahun Ajaran')
                    ->required(),
                Select::make('type')
                    ->label('Tipe Nilai')
                    ->options([
                        'Tugas' => 'Tugas',
                        'UTS' => 'UTS',
                        'UAS' => 'UAS',
                    ])
                    ->required(),
                TextInput::make('score')
                    ->label('Nilai Akhir')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject.name')
            ->columns([
                TextColumn::make('subject.name')->label('Mata Pelajaran'),
                TextColumn::make('academicYear.name')->label('Tahun Ajaran'),
                TextColumn::make('type')->label('Tipe Nilai')->badge(),
                TextColumn::make('score')->label('Nilai Akhir'),
            ])
            ->filters([
                //
            ])
            // 👉 KITA KOSONGKAN TOTAL SEMUA AKSI SEMENTARA WAKTU
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}