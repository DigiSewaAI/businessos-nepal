<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'branch_id', 'table_id', 'customer_id', 'user_id',
        'sale_id', 'order_number', 'order_type', 'status', 'guest_count',
        'special_instructions', 'subtotal', 'discount', 'tax', 'total',
        'ordered_at', 'prepared_at', 'served_at'
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'prepared_at' => 'datetime',
        'served_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function items()
    {
        return $this->hasMany(RestaurantOrderItem::class, 'restaurant_order_id');
    }

    public function kotLogs()
    {
        return $this->hasMany(KOTLog::class, 'restaurant_order_id');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'preparing', 'ready', 'served']);
    }

    public function isActive()
    {
        return in_array($this->status, ['pending', 'preparing', 'ready', 'served']);
    }

    public function canConvertToSale()
    {
        return $this->status === 'served' && !$this->sale_id;
    }
}