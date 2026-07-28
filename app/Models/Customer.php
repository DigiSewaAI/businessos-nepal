<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'name', 'email', 'phone', 'address',
        'opening_due', 'total_purchases', 'created_by', 'updated_by'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}