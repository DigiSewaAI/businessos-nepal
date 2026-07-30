<?php
namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    protected string $model;
    protected string $url;

    public function __construct()
    {
        $this->model = config('ai.ollama_model', 'haubaa/sanu-ai:7b');
        $this->url = config('ai.ollama_url', 'http://127.0.0.1:11434/api/generate');
    }

    public function generate(string $prompt, array $options = []): string
    {
        $payload = array_merge([
            'model' => $this->model,
            'prompt' => $prompt,
            'stream' => false,
        ], $options);

        try {
            $response = Http::timeout(120)->post($this->url, $payload);
            if ($response->successful()) {
                $data = $response->json();
                return trim($data['response'] ?? 'No response generated.');
            }
            Log::error('Ollama error: ' . $response->body());
            return 'Error: Unable to generate response. Please check Ollama is running.';
        } catch (\Exception $e) {
            Log::error('Ollama exception: ' . $e->getMessage());
            return 'Error: Service unavailable. Please start Ollama with `ollama serve`';
        }
    }

    public function generateWithContext(array $conversation, string $newMessage): string
    {
        $history = '';
        foreach ($conversation as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'Assistant';
            $history .= "{$role}: {$msg['content']}\n";
        }
        $fullPrompt = $history . "User: {$newMessage}\nAssistant:";
        return $this->generate($fullPrompt);
    }

    public function isRunning(): bool
    {
        try {
            $response = Http::get('http://127.0.0.1:11434');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}