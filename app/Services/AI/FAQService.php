<?php
namespace App\Services\AI;

use App\Models\AI\FAQ;
use Illuminate\Support\Facades\Cache;

class FAQService
{
    public function findAnswer($question)
    {
        $cacheKey = 'faq_' . md5($question);
        
        return Cache::remember($cacheKey, 3600, function() use ($question) {
            // Exact match
            $faq = FAQ::where('question', $question)->first();
            if ($faq) return $faq->answer;
            
            // Keyword search
            $keywords = explode(' ', $question);
            $faq = FAQ::where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    if (strlen($keyword) > 2) {
                        $q->orWhere('question', 'LIKE', "%{$keyword}%");
                    }
                }
            })->orderBy('priority', 'desc')->first();
            
            return $faq ? $faq->answer : null;
        });
    }
}