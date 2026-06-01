<?php

namespace App\Filament\Resources\Subjects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportBulkAction;
use App\Filament\Exports\SubjectExporter;


class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('name')
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
                    
                    // Tombol Export Kebanggaan Menu Mata Pelajaran
                    ExportBulkAction::make()
                        ->exporter(SubjectExporter::class)
                        ->label('Export Excel/CSV')
                        ->color('success')
                        ->icon('heroicon-m-arrow-down-tray'),
                ]),
            ]);

    }
}
