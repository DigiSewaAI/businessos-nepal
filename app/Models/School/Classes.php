<?php

namespace App\Models\School;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_classes';

    protected $fillable = [
        'organization_id', 'name', 'code', 'order', 'is_active'
    ];

    public function sections()
    {
        return $this->hasMany(Section::class, 'school_class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'school_class_id');
    }

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class, 'school_class_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'school_class_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}