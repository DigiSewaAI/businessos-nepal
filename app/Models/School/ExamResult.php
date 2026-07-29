<?php

namespace App\Models\School;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $table = 'school_exam_results';

    protected $fillable = [
        'organization_id', 'school_exam_id', 'school_student_id',
        'school_subject_id', 'marks_obtained', 'max_marks',
        'grade', 'remarks'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'school_exam_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'school_student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'school_subject_id');
    }

    public function getPercentageAttribute()
    {
        if ($this->max_marks == 0) return 0;
        return round(($this->marks_obtained / $this->max_marks) * 100, 2);
    }

    public function getGradeAttribute()
    {
        $percentage = $this->percentage;
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        return 'D';
    }

    public function isPassed()
    {
        return $this->marks_obtained >= $this->max_marks * 0.4; // 40% passing
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('school_exam_id', $examId);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('school_student_id', $studentId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('school_subject_id', $subjectId);
    }
}