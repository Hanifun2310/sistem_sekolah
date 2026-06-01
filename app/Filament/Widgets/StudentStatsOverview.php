<?php

namespace App\Filament\Widgets;

use App\Models\Grade;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        
        $studentQuery = Student::query();
        $gradeQuery = Grade::query();

        // 🔒 LEVEL 2: Saring ketat berdasarkan Mata Pelajaran yang diajar Guru
        if ($user->role === 'teacher') {
            $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
            $teacherId = $teacher ? $teacher->id : 0;

            // Ambil semua jadwal guru ini untuk mendapatkan id kelas dan id mapel
            $schedules = DB::table('schedules')->where('teacher_id', $teacherId)->get();
            
            $classroomIds = $schedules->pluck('classroom_id')->unique()->toArray();
            // 👉 KUNCI UTAMA: Ambil ID Mata Pelajaran yang diajar oleh guru ini
            $subjectIds = $schedules->pluck('subject_id')->unique()->toArray();

            if (empty($classroomIds)) { $classroomIds = [0]; }
            if (empty($subjectIds)) { $subjectIds = [0]; }

            // Saring siswa berdasarkan kelas yang diajar
            $studentQuery->whereIn('classroom_id', $classroomIds, 'and', false);
            
            $studentIds = DB::table('students')->whereIn('classroom_id', $classroomIds, 'and', false)->pluck('id')->toArray();
            if (empty($studentIds)) { $studentIds = [0]; }
            
            // 👉 SUNTIK FILTER: Nilai harus milik siswa tersebut DAN mata pelajaran milik guru tersebut
            $gradeQuery->whereIn('student_id', $studentIds, 'and', false)
                       ->whereIn('subject_id', $subjectIds, 'and', false);
        }

        // 1. Hitung Total Siswa
        $totalSiswa = $studentQuery->get()->count(); 

        // 2. Ambil data nilai yang sudah dikunci per mata pelajaran guru
        $allGrades = $gradeQuery->get();

        // 3. Hitung Rata-rata Tugas
        $avgTugas = $allGrades->where('type', 'Tugas')->avg('score') ?? 0;
        $avgTugasFormatted = number_format((float)$avgTugas, 1);
        
        // 4. Hitung Rata-rata Ujian (UTS & UAS)
        $avgUjian = $allGrades->whereIn('type', ['UTS', 'UAS'])->avg('score') ?? 0;
        $avgUjianFormatted = number_format((float)$avgUjian, 1);

        return [
            Stat::make('Total Siswa', $totalSiswa . ' Orang')
                ->description($user->role === 'teacher' ? 'Siswa di kelas yang Anda ajar' : 'Siswa aktif seluruh sekolah')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Rata-rata Tugas', $avgTugasFormatted)
                ->description('Performa nilai harian')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('Rata-rata Ujian', $avgUjianFormatted)
                ->description('Gabungan UTS & UAS')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),
        ];
    }
}