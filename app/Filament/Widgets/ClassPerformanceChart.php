<?php

namespace App\Filament\Widgets;

use App\Models\Classroom;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassPerformanceChart extends ChartWidget
{
    protected ?string $heading = 'Perbandingan Nilai Rata-rata Antar Kelas';
    
    protected static ?int $sort = 2; 

    protected function getType(): string
    {
        return 'bar'; 
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'Semua Tipe Nilai',
            'Tugas' => 'Khusus Tugas',
            'UTS' => 'Khusus UTS',
            'UAS' => 'Khusus UAS',
        ];
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $labels = [];
        $averages = [];
        $subjectIds = []; // Inisialisasi awal

        $activeFilter = $this->filter ?? 'all';
        $classroomQuery = Classroom::query();

        if ($user->role === 'teacher') {
            $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
            $teacherId = $teacher ? $teacher->id : 0;

            $schedules = DB::table('schedules')->where('teacher_id', $teacherId)->get();
            $classroomIds = $schedules->pluck('classroom_id')->unique()->toArray();
            // 👉 Ambil ID mata pelajaran guru untuk grafik
            $subjectIds = $schedules->pluck('subject_id')->unique()->toArray();

            if (empty($classroomIds)) { $classroomIds = [0]; }
            if (empty($subjectIds)) { $subjectIds = [0]; }

            $classroomQuery->whereIn('id', $classroomIds, 'and', false);
            $this->heading = 'Performa Nilai Rata-rata Kelas Anda';
        }

        $classrooms = $classroomQuery->get();

        foreach ($classrooms as $classroom) {
            $labels[] = $classroom->name;

            $studentIds = DB::table('students')
                ->where('classroom_id', $classroom->id)
                ->pluck('id')
                ->toArray();

            if (empty($studentIds)) { $studentIds = [0]; }

            // Base Query data nilai
            $gradeQuery = DB::table('grades')->whereIn('student_id', $studentIds, 'and', false);

            // 👉 KUNCI GRAFIK: Jika login sebagai guru, batasi hanya mapel miliknya
            if ($user->role === 'teacher') {
                $gradeQuery->whereIn('subject_id', $subjectIds, 'and', false);
            }

            // Filter dropdown aktif
            if ($activeFilter !== 'all') {
                $gradeQuery->where('type', $activeFilter);
            }

            $avgScore = $gradeQuery->avg('score') ?? 0;
            $averages[] = round($avgScore, 1);
        }

        $datasetLabel = 'Nilai Rata-rata';
        if ($activeFilter !== 'all') {
            $datasetLabel = 'Rata-rata ' . $activeFilter;
        }

        return [
            'datasets' => [
                [
                    'label' => $datasetLabel,
                    'data' => $averages,
                    'backgroundColor' => '#3b82f6', 
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }
}