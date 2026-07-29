<?php

namespace App\Models\School;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeCollection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_fee_collections';

    protected $fillable = [
        'organization_id', 'school_student_id', 'school_fee_structure_id',
        'invoice_number', 'due_date', 'paid_date', 'amount',
        'paid_amount', 'discount', 'late_fee', 'due_amount',
        'status', 'payment_method', 'notes', 'created_by'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'school_student_id');
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class, 'school_fee_structure_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('school_student_id', $studentId);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - ($this->paid_amount + $this->discount);
    }

    public function isFullyPaid()
    {
        return $this->paid_amount >= $this->amount;
    }
}