<?php

namespace App\Filament\Resources\Grades;

use App\Filament\Resources\Grades\Pages\CreateGrade;
use App\Filament\Resources\Grades\Pages\EditGrade;
use App\Filament\Resources\Grades\Pages\ListGrades;
use App\Filament\Resources\Grades\Schemas\GradeForm;
use App\Filament\Resources\Grades\Tables\GradesTable;
use App\Models\Grade;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Teacher;
use App\Models\Schedule;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Jika yang login adalah guru, saring data di tabel nilai
        if (Auth::check() && Auth::user()->role === 'teacher') {
            $teacher = Teacher::query()->where('user_id', Auth::id())->first();
            
            if ($teacher) {
                // Cari mata pelajaran apa saja yang diajar oleh guru ini dari tabel jadwal
                $subjectIds = Schedule::query()->where('teacher_id', $teacher->id)
                    ->pluck('subject_id')
                    ->unique();
                    
                // Tampilkan HANYA nilai yang subject_id-nya cocok dengan mapel guru tersebut
                $query->whereIn('subject_id', $subjectIds);
            } else {
                // Jika tidak ada profil guru yang tertaut, kosongkan tabel
                $query->where('id', 0);
            }
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return GradeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GradesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGrades::route('/'),
            'create' => CreateGrade::route('/create'),
            'edit' => EditGrade::route('/{record}/edit'),
        ];
    }
}
