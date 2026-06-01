<?php

namespace App\Filament\Exports;

use App\Models\Student;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class StudentExporter extends Exporter
{
    protected static ?string $model = Student::class;

    // 🔒 PAKSA FILAMENT PAKAI DISK PUBLIC AGAR LINK DOWNLOAD TIDAK REFUSED
    public function getFileDisk(): string
    {
        return 'public';
    }
    
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('nis')->label('NIS'),
            ExportColumn::make('name')->label('Nama Siswa'),
            ExportColumn::make('gender')->label('Jenis Kelamin'),
            ExportColumn::make('classroom.name')->label('Kelas'),
            ExportColumn::make('academicYear.name')->label('Tahun Ajaran'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export data siswa telah selesai dan ' . number_format($export->successful_rows) . ' baris data berhasil diunduh.';

        if ($failedRowsCount = $export->failed_rows) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }

        return $body;
    }
    

    
}