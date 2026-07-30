<?php
namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    protected $table = 'ai_faqs';
    protected $fillable = ['category', 'question', 'answer', 'keywords', 'priority', 'is_active'];
    protected $casts = ['keywords' => 'array'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('question', 'LIKE', "%{$term}%")
            ->orWhere('answer', 'LIKE', "%{$term}%")
            ->orWhereJsonContains('keywords', $term);
    }
}