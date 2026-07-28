<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrganizationSubscription; // <-- ADD THIS

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'address',
        'logo', 'settings', 'status', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'settings' => 'array',
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
}