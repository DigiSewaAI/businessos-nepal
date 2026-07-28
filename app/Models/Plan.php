<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'price_monthly', 'price_yearly', 'features', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(OrganizationSubscription::class);
    }

    public function getFeature($key, $default = null)
    {
        return $this->features[$key] ?? $default;
    }
}