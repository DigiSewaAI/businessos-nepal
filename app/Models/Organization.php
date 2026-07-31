<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrganizationSubscription;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'business_type', 'address', 'phone', 'email',
        'logo', 'plan_id', 'trial_ends_at', 'currency', 'timezone',
        'settings',
        'status',
        'created_by', 'updated_by',
        // ✅ NEW: Phase A Industry fields
        'industry',
        'business_category',
    ];

    protected $casts = [
        'settings' => 'array',
        'trial_ends_at' => 'datetime',
        // ✅ NEW: ensure industry and business_category are strings
        'industry' => 'string',
        'business_category' => 'string',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Plan Subscription (Phase 6)
    public function subscription()
    {
        return $this->hasOne(OrganizationSubscription::class)->where('is_active', true)->latest();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * ✅ NEW: Helper accessor for backward compatibility.
     * Existing organizations without industry get 'retail' as fallback.
     */
    public function getIndustryAttribute($value)
    {
        return $value ?? 'retail';
    }

    /**
     * ✅ NEW: Optional helper for getting business category label.
     */
    public function getBusinessCategoryLabelAttribute()
    {
        $categories = config('businessos.industries.' . $this->industry . '.business_categories', []);
        return $categories[$this->business_category] ?? $this->business_category ?? 'General';
    }

    /**
     * ✅ NEW: Check if organization has a specific industry.
     */
    public function isIndustry(string $industry): bool
    {
        return $this->industry === $industry;
    }
}