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
    Schema::create('grades', function ($table) {
        $table->id();
        $table->string('name'); 
        $table->timestamps();
    });

    Schema::create('subjects', function ($table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    Schema::table('users', function ($table) {
        $table->foreignId('grade_id')->nullable()->constrained()->nullOnDelete();
    });

    Schema::create('marks', function ($table) {
        $table->id();
        $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
        $table->integer('score');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_management_tables');
    }
};
