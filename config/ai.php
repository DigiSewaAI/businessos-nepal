<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ollama Configuration
    |--------------------------------------------------------------------------
    */
    'ollama_url' => env('OLLAMA_URL', 'http://127.0.0.1:11434/api/generate'),
    'ollama_model' => env('OLLAMA_MODEL', 'llama3.2'),
    'timeout' => env('AI_TIMEOUT', 120),
    'cache_ttl' => env('AI_CACHE_TTL', 3600),
    'warmup' => env('AI_WARMUP', true),

    /*
    |--------------------------------------------------------------------------
    | AI Features
    |--------------------------------------------------------------------------
    */
    'features' => [
        'faq' => true,          // Knowledge base (FAQ)
        'data' => true,         // Real business data (stock, sales, profit)
        'forecast' => true,     // Sales forecasting
        'anomaly' => true,      // Anomaly detection
        'voice' => false,       // Voice input (future)
    ],
];