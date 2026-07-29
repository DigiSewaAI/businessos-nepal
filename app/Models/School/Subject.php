<?php

namespace App\Models\School;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_subjects';

    protected $fillable = [
        'organization_id', 'name', 'code', 'description', 'is_active'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'school_subject_teacher', 'school_subject_id', 'school_teacher_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'school_subject_teacher', 'school_subject_id', 'school_class_id');
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'school_subject_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'school_subject_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('code', 'LIKE', "%{$search}%");
    }
}