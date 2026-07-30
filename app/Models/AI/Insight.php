<?php
namespace App\Models\AI;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Insight extends Model
{
    use HasFactory;

    protected $table = 'ai_insights';
    protected $fillable = ['organization_id', 'type', 'data', 'priority', 'is_read'];

    protected $casts = ['data' => 'array', 'is_read' => 'boolean'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }
}