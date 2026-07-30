<?php
namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $table = 'ai_messages';
    protected $fillable = ['conversation_id', 'role', 'content', 'metadata', 'tokens'];

    protected $casts = ['metadata' => 'array'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}