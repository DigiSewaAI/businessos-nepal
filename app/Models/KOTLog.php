<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KOTLog extends Model
{
    use HasFactory;

    protected $table = 'kot_logs';   // ✅ specify table name

    protected $fillable = [
        'restaurant_order_id', 'organization_id', 'kot_number',
        'items', 'status', 'sent_at', 'printed_at', 'viewed_at'
    ];

    protected $casts = [
        'items' => 'array',
        'sent_at' => 'datetime',
        'printed_at' => 'datetime',
        'viewed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(RestaurantOrder::class, 'restaurant_order_id');
    }
}