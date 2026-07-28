<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'branch_id', 'supplier_id', 'user_id',
        'po_no', 'invoice_no', 'date', 'expected_date',
        'subtotal', 'discount', 'tax', 'total',
        'paid_amount', 'due_amount', 'payment_status', 'status',
        'note', 'created_by', 'updated_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lines()
    {
        return $this->hasMany(PurchaseLine::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }
}