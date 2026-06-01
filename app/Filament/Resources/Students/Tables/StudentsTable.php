<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

// 🔒 ALAMAT BARU YANG SUDAH DIVALIDASI & 100% TRUE
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use App\Filament\Exports\StudentExporter;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('gender')
                    ->searchable(),
                TextColumn::make('classroom.name')
                    ->searchable(),
                TextColumn::make('academicYear.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('classroom_id')
                    ->relationship('classroom', 'name')
                    ->label('Saring Berdasarkan Kelas')
                    ->searchable()
                    ->preload(),
            ])
            // 🎬 Aksi Baris (Row Actions)
            ->actions([
                EditAction::make(),
            ])
            // 🚀 Aksi Massal (Bulk Actions)
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    
                    // 📊 Tombol Export Excel Kebanggaan Kita
                    ExportBulkAction::make()
                        ->exporter(StudentExporter::class)
                        ->label('Export Excel/CSV')
                        ->color('success')
                        ->icon('heroicon-m-arrow-down-tray'),
                ]),
            ]);
    }
}