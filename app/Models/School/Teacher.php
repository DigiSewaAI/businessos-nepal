<?php

namespace App\Models\School;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_teachers';

    protected $fillable = [
        'organization_id', 'user_id', 'first_name', 'last_name',
        'email', 'phone', 'address', 'date_of_birth', 'gender',
        'qualification', 'joining_date', 'status', 'photo'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'school_subject_teacher', 'school_teacher_id', 'school_subject_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'school_subject_teacher', 'school_teacher_id', 'school_class_id');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'school_subject_teacher', 'school_teacher_id', 'school_section_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'school_teacher_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('last_name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%");
    }
}