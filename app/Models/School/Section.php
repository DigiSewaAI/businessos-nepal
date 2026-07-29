<?php

namespace App\Models\School;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_sections';

    protected $fillable = [
        'organization_id', 'school_class_id', 'name', 'capacity', 'is_active'
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'school_class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'school_section_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}