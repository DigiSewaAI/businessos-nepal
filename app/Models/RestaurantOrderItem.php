<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_order_id', 'product_id', 'product_variant_id',
        'quantity', 'prepared_quantity', 'price', 'discount',
        'tax', 'total', 'special_instructions', 'status'
    ];

    public function order()
    {
        return $this->belongsTo(RestaurantOrder::class, 'restaurant_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}