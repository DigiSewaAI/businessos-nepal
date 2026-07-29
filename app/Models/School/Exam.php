<?php

namespace App\Models\School;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_exams';

    protected $fillable = [
        'organization_id', 'school_class_id', 'name',
        'start_date', 'end_date', 'max_marks', 'passing_marks',
        'description', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'school_class_id');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class, 'school_exam_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'school_exam_results', 'school_exam_id', 'school_student_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('school_class_id', $classId);
    }

    public function getStudentResult($studentId)
    {
        return $this->results()->where('school_student_id', $studentId)->get();
    }

    public function getTotalMarks($studentId)
    {
        return $this->results()->where('school_student_id', $studentId)->sum('marks_obtained');
    }
}