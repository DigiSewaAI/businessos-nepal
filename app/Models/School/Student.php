<?php

namespace App\Models\School;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_students';

    protected $fillable = [
        'organization_id', 'user_id', 'school_class_id', 'school_section_id',
        'admission_number', 'roll_number', 'first_name', 'last_name',
        'date_of_birth', 'gender', 'phone', 'email', 'address',
        'guardian_name', 'guardian_phone', 'guardian_email',
        'admission_date', 'status', 'photo'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
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

    public function class()
    {
        return $this->belongsTo(Classes::class, 'school_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'school_section_id');
    }

    public function feeCollections()
    {
        return $this->hasMany(FeeCollection::class, 'school_student_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'school_student_id');
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'school_student_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('school_class_id', $classId);
    }
}