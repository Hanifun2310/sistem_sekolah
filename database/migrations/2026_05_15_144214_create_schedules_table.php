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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        
        // 4 Pilar Relasi Utama
        $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
        $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
        $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
        $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
        
        // Waktu Pelaksanaan
        $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
        $table->time('start_time'); // Jam mulai
        $table->time('end_time');   // Jam selesai
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
