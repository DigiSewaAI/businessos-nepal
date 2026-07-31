<?php
namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;

class AIAnalytics extends Model
{
    protected $table = 'ai_analytics';

    protected $fillable = [
        'organization_id',
        'user_id',
        'source',
        'intent',
        'query',
        'response_time_ms',
        'tokens_used',
        'success',
        'error_message',
    ];

    protected $casts = [
        'success' => 'boolean',
        'response_time_ms' => 'integer',
        'tokens_used' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}