<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Student extends Model
{
    // 👉 Tambahkan kolom-kolom baru ke dalam fillable
    protected $fillable = [
        'classroom_id', 
        'academic_year_id', 
        'nis', 
        'name', 
        'photo',
        'gender', 
        'birth_date', 
        'address', 
        'guardian_name',
        'guardian_phone', 
        'merit_points', 
        'demerit_points', 
        'enrolled_at' 
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // 👉 Tambahkan relasi ini agar Siswa bisa ditarik data nilainya
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}