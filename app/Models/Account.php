<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'code', 'name', 'type', 'parent_id',
        'is_active', 'opening_balance'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getBalanceAttribute()
    {
        // calculate from journal entries
        $debit = $this->journalEntries()->sum('debit');
        $credit = $this->journalEntries()->sum('credit');

        if (in_array($this->type, ['asset', 'expense'])) {
            return $this->opening_balance + $debit - $credit;
        } else { // liability, equity, revenue
            return $this->opening_balance + $credit - $debit;
        }
    }
}