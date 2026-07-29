<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('school_subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            $table->foreignId('school_teacher_id')->constrained('school_teachers')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('school_section_id')->constrained('school_sections')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_subject_teacher');
    }
};