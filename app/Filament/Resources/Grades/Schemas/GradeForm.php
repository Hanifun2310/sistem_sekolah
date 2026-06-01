<?php

namespace App\Filament\Resources\Grades\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                // 👉 Kita buat dropdown Mata Pelajaran ini mendeteksi Jadwal Guru secara otomatis
                Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship(
                        name: 'subject',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            // 1. Cek jika yang login adalah akun dengan role 'teacher'
                            if (Auth::check() && Auth::user()->role === 'teacher') {
                                // 2. Cari profil Gurunya berdasarkan user_id yang sedang login
                                $teacher = Teacher::query()->where('user_id', Auth::id())->first();
                                
                                if ($teacher) {
                                    // 3. Ambil semua subject_id unik yang diajar oleh guru ini di tabel schedules
                                    $subjectIds = Schedule::query()->where('teacher_id', $teacher->id)
                                        ->pluck('subject_id')
                                        ->unique(); // ->unique() memastikan jika mengajar di banyak kelas, mapelnya tidak dobel di opsi
                                        
                                    // 4. Filter opsi agar hanya memunculkan ID mata pelajaran yang ada di jadwalnya
                                    $query->whereIn('id', $subjectIds);
                                } else {
                                    // Jika akun guru belum ditautkan ke profil guru manapun, kosongkan pilihan
                                    $query->where('id', 0);
                                }
                            }
                            // Jika yang login adalah Super Admin, perintah di atas dilewati dan Admin bisa melihat semua mapel!
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Select::make('type')
                    ->label('Jenis Nilai')
                    ->options([
                        'Tugas' => 'Tugas',
                        'UTS' => 'UTS',
                        'UAS' => 'UAS',
                    ])
                    ->required(),
                    
                TextInput::make('score')
                    ->label('Nilai (Angka)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->required(),
            ]);
    }
}