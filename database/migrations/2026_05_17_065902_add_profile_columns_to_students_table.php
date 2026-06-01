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
    Schema::table('students', function (Blueprint $table) {
        // 1. Foto Siswa
        $table->string('photo')->nullable()->after('name');
        
        // 2. Biodata Tambahan
        $table->date('birth_date')->nullable()->after('gender');
        $table->text('address')->nullable()->after('birth_date');
        
        // 3. Data Wali Murid
        $table->string('guardian_name')->nullable()->after('address');
        $table->string('guardian_phone')->nullable()->after('guardian_name');
        
        // 4. Sistem Poin (Kebaikan & Kesalahan)
        $table->integer('merit_points')->default(0)->after('guardian_phone');   // Poin Kebaikan
        $table->integer('demerit_points')->default(0)->after('merit_points'); // Poin Kesalahan
        
        // 5. Tanggal Terdaftar di Sekolah
        $table->date('enrolled_at')->nullable()->after('demerit_points');
    });
}

public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn([
            'photo', 'birth_date', 'address', 
            'guardian_name', 'guardian_phone', 
            'merit_points', 'demerit_points', 'enrolled_at'
        ]);
    });
}
};
