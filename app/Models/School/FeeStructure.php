<?php

namespace App\Models\School;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_fee_structures';

    protected $fillable = [
        'organization_id', 'school_class_id', 'name', 'frequency',
        'amount', 'description', 'is_mandatory', 'is_active'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'school_class_id');
    }

    public function feeCollections()
    {
        return $this->hasMany(FeeCollection::class, 'school_fee_structure_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('school_class_id', $classId);
    }
}