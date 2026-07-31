<?php
namespace App\Jobs;

use App\Models\AI\Conversation;
use App\Models\AI\Message;
use App\Services\AI\OllamaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AIProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $conversationId;
    protected $message;
    protected $userId;

    public $timeout = 300;
    public $tries = 3;

    public function __construct($conversationId, $message, $userId)
    {
        $this->conversationId = $conversationId;
        $this->message = $message;
        $this->userId = $userId;
    }

    public function handle(OllamaService $ollama)
    {
        try {
            $conversation = Conversation::find($this->conversationId);
            if (!$conversation) {
                Log::error('Conversation not found: ' . $this->conversationId);
                return;
            }

            $history = Message::where('conversation_id', $this->conversationId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->reverse()
                ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
                ->toArray();

            $response = $ollama->generateWithContext($history, $this->message);

            Message::create([
                'conversation_id' => $this->conversationId,
                'role' => 'assistant',
                'content' => $response,
                'metadata' => ['source' => 'queue_job'],
            ]);

            Log::info('AI Job completed for conversation: ' . $this->conversationId);

        } catch (\Exception $e) {
            Log::error('AI Job failed: ' . $e->getMessage());
            throw $e;
        }
    }
}