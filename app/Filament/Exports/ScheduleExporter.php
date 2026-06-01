<?php

namespace App\Filament\Exports;

use App\Models\Schedule;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ScheduleExporter extends Exporter
{
    protected static ?string $model = Schedule::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('teacher.name')->label('Nama Guru'),
            ExportColumn::make('subject.name')->label('Nama Mata Pelajaran'),
            ExportColumn::make('classroom.name')->label('Nama Kelas'),
            ExportColumn::make('academicYear.name')->label('Nama Tahun Akademik'),
            ExportColumn::make('day')->label('Hari'),
            ExportColumn::make('start_time')->label('Jam Mulai Kelas'),
            ExportColumn::make('end_time')->label('Jam Akhir Kelas'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your schedule export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileDisk(): string
{
    return 'public';
}
}
