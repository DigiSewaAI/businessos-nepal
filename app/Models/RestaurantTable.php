<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'branch_id', 'number', 'capacity',
        'status', 'qr_code', 'is_active'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function orders()
    {
        return $this->hasMany(RestaurantOrder::class, 'table_id');
    }

    public function activeOrder()
    {
        return $this->orders()
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
            ->latest()
            ->first();
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getQrCodeUrlAttribute()
    {
        if ($this->qr_code) {
            return asset('storage/qr/' . $this->qr_code);
        }
        return null;
    }
}