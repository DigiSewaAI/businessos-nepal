<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_student_id')->constrained('school_students')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('school_section_id')->constrained('school_sections')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'leave', 'holiday']);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['school_student_id', 'date']);
            $table->index(['date', 'school_class_id', 'school_section_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_attendance');
    }
};