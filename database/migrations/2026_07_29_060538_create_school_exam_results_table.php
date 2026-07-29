<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_exam_id')->constrained('school_exams')->cascadeOnDelete();
            $table->foreignId('school_student_id')->constrained('school_students')->cascadeOnDelete();
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            $table->decimal('marks_obtained', 8, 2)->default(0);
            $table->decimal('max_marks', 8, 2)->default(100);
            $table->string('grade')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['school_exam_id', 'school_student_id', 'school_subject_id'], 'exam_results_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_exam_results');
    }
};