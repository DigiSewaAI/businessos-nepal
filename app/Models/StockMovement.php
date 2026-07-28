<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id', 'warehouse_id', 'product_id', 'product_variant_id',
        'type', 'quantity', 'previous_quantity', 'current_quantity',
        'reference_type', 'reference_id', 'reason', 'created_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}