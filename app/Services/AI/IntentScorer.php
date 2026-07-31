<?php
namespace App\Services\AI;

class IntentScorer
{
    public function score(string $message): array
    {
        $scores = [
            'inventory' => 0,
            'sales' => 0,
            'financial' => 0,
            'school' => 0,
            'restaurant' => 0,
            'forecast' => 0,
            'anomaly' => 0,
            'general' => 10,  // Default score
        ];
        
        $lower = strtolower($message);
        
        // Inventory scoring
        if (preg_match('/stock|inventory|product|item|samagri|baki|quantity/', $lower)) {
            $scores['inventory'] += 40;
        }
        if (preg_match('/low|alert|kati|remaining/', $lower)) {
            $scores['inventory'] += 20;
        }
        
        // Sales scoring
        if (preg_match('/sales|sell|revenue|income|bikri/', $lower)) {
            $scores['sales'] += 40;
        }
        if (preg_match('/today|this month|total|overview/', $lower)) {
            $scores['sales'] += 20;
        }
        
        // Financial scoring
        if (preg_match('/profit|loss|expense|balance|cash|kharcha|laabh|ghata/', $lower)) {
            $scores['financial'] += 40;
        }
        if (preg_match('/calculate|breakdown|sources/', $lower)) {
            $scores['financial'] += 20;
        }
        
        // School scoring
        if (preg_match('/student|fee|attendance|exam|class|vidhyarthi|school|grade/', $lower)) {
            $scores['school'] += 40;
        }
        
        // Restaurant scoring
        if (preg_match('/order|menu|table|kitchen|restaurant|kot|food/', $lower)) {
            $scores['restaurant'] += 40;
        }
        
        // Forecast scoring
        if (preg_match('/predict|forecast|projection|bhawi|next month/', $lower)) {
            $scores['forecast'] += 50;
        }
        
        // Anomaly scoring
        if (preg_match('/anomaly|unusual|suspicious|alert|bigreko/', $lower)) {
            $scores['anomaly'] += 50;
        }
        
        // Return sorted with highest first
        arsort($scores);
        return $scores;
    }
    
    public function getBestIntent(array $scores): string
    {
        $best = key($scores);
        $score = reset($scores);
        
        // If score is too low, fallback to general
        if ($score < 30) {
            return 'general';
        }
        
        return $best;
    }
}