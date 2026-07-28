<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'branch_id', 'customer_id', 'user_id',
        'invoice_no', 'date', 'subtotal', 'discount', 'discount_percent',
        'tax', 'tax_percent', 'total', 'paid_amount', 'due_amount',
        'payment_status', 'payment_method', 'note', 'status',
        'created_by', 'updated_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lines()
    {
        return $this->hasMany(SaleLine::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}