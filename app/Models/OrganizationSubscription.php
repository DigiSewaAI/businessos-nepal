<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id', 'plan_id', 'starts_at', 'ends_at', 'is_active', 'payment_method', 'transaction_id'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive()
    {
        return $this->is_active && ($this->ends_at === null || $this->ends_at->isFuture());
    }
}