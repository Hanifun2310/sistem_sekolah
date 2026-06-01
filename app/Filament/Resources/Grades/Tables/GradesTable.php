<?php

namespace App\Filament\Resources\Grades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportBulkAction;
use App\Filament\Exports\GradeExporter;

class GradesTable
{
    public static function configure(Table $table): Table
    {
return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('academicYear.name')
                    ->label('Tahun Ajaran')
                    ->sortable(),
                    
                TextColumn::make('type')
                    ->label('Jenis Nilai')
                    ->badge() // Membuat tampilannya cantik seperti label
                    ->color(fn (string $state): string => match ($state) {
                        'Tugas' => 'info',
                        'UTS' => 'warning',
                        'UAS' => 'success',
                    }),
                    
                TextColumn::make('score')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Di Update Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    
                    // Tombol Export Kebanggaan Menu Guru
                    ExportBulkAction::make()
                        ->exporter(GradeExporter::class)
                        ->label('Export Excel/CSV')
                        ->color('success')
                        ->icon('heroicon-m-arrow-down-tray'),
                ]),
            ]);
    }
}
