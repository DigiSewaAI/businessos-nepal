<?php

namespace App\Models\School;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'school_attendance';

    protected $fillable = [
        'organization_id', 'school_student_id', 'school_class_id',
        'school_section_id', 'date', 'status', 'remarks'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'school_student_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'school_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'school_section_id');
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('school_class_id', $classId);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('school_student_id', $studentId);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }
}