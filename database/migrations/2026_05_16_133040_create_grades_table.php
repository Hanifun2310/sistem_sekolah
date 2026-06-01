<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('grades', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke Siswa, Mapel, dan Tahun Ajaran
        $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
        $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
        $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
        
        // Jenis Nilai dan Angkanya
        $table->enum('type', ['Tugas', 'UTS', 'UAS']); // Kategori nilai
        $table->integer('score'); // Angka nilai (misal: 85, 90)
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
