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

    /**
     * Generate response with industry-aware system prompt
     */
    public function generate(string $prompt, array $options = []): string
    {
        // Extract industry from options or default to retail
        $industry = $options['industry'] ?? 'retail';
        
        // Build full prompt with system context
        $systemPrompt = $this->getSystemPrompt($industry);
        $fullPrompt = $systemPrompt . "\n\nUser: " . $prompt . "\nAssistant:";

        $payload = array_merge([
            'model' => $this->model,
            'prompt' => $fullPrompt,
            'stream' => false,
            'options' => [
                'temperature' => 0.7,
                'top_p' => 0.9,
            ],
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

    /**
     * Get system prompt based on industry
     */
    private function getSystemPrompt(string $industry): string
    {
        $prompts = [
            'school' => "You are an AI assistant for a School Management System in Nepal.
                         You speak both Nepali and English.
                         
                         Your role is to help with:
                         - Students: enrollment, status, performance
                         - Teachers: details, classes, workload
                         - Attendance: daily records, reports, trends
                         - Fees: collection, pending, invoices
                         - Exams: schedules, results, analysis
                         
                         IMPORTANT RULES:
                         1. NEVER mention sales, products, inventory, POS, or retail concepts.
                         2. If user asks about sales/stock, politely redirect to school topics.
                         3. Use clear bullet points when listing multiple items.
                         4. When giving numbers, mention context clearly.
                         5. Be helpful, concise, and professional.",

            'retail' => "You are an AI assistant for a Retail Business Management System.
                         You help with sales, products, inventory, expenses, and profits.
                         Provide clear, actionable insights.
                         When asked for data, give specific numbers with context.",

            'restaurant' => "You are an AI assistant for a Restaurant Management System.
                             You help with orders, tables, kitchen, and revenue.
                             Provide clear, actionable insights.",
        ];

        return $prompts[$industry] ?? $prompts['retail'];
    }

    public function generateWithContext(array $conversation, string $newMessage, string $industry = 'retail'): string
    {
        $history = '';
        foreach ($conversation as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'Assistant';
            $history .= "{$role}: {$msg['content']}\n";
        }
        return $this->generate($newMessage, ['industry' => $industry, 'context' => $history]);
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