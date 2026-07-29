<?php

namespace App\Models\School;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $table = 'school_timetables';

    protected $fillable = [
        'organization_id', 'school_class_id', 'school_section_id',
        'school_subject_id', 'school_teacher_id', 'day',
        'start_time', 'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'school_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'school_section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'school_subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'school_teacher_id');
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('school_class_id', $classId);
    }

    public function scopeBySection($query, $sectionId)
    {
        return $query->where('school_section_id', $sectionId);
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('school_teacher_id', $teacherId);
    }

    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    public function getDurationAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        return $start->diff($end)->format('%H:%I');
    }

    public function getDayNameAttribute()
    {
        return ucfirst($this->day);
    }
}