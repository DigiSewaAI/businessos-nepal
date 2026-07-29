<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('school_section_id')->constrained('school_sections')->cascadeOnDelete();
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            $table->foreignId('school_teacher_id')->constrained('school_teachers')->cascadeOnDelete();
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->unique(['school_class_id', 'school_section_id', 'day', 'start_time'], 'timetable_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_timetables');
    }
};