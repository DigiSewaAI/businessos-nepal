<?php
namespace App\Services\AI;

class IntentParser
{
    public function parse(string $message): array
    {
        $lower = strtolower($message);
        $intent = [
            'category' => $this->detectCategory($lower),
            'action' => $this->detectAction($lower),
            'timeframe' => $this->detectTimeframe($lower),
            'entity' => $this->detectEntity($lower),
            'parameters' => [],
        ];
        return $intent;
    }

    protected function detectCategory($msg): string
    {
        // Updated with Phase 1 keywords: added 'school' and 'kot' for restaurant
        if (preg_match('/sales|sell|revenue|income|bikri/', $msg)) return 'sales';
        if (preg_match('/stock|inventory|product|item|samagri/', $msg)) return 'inventory';
        if (preg_match('/profit|loss|expense|balance|cash|kharcha/', $msg)) return 'financial';
        if (preg_match('/student|fee|attendance|exam|class|vidhyarthi|school/', $msg)) return 'school'; // Added 'school'
        if (preg_match('/order|menu|table|kitchen|restaurant|kot/', $msg)) return 'restaurant'; // Added 'kot'
        if (preg_match('/predict|forecast|projection|bhawi/', $msg)) return 'forecast';
        if (preg_match('/anomaly|unusual|suspicious|alert|bigreko/', $msg)) return 'anomaly';
        return 'general';
    }

    protected function detectAction($msg): string
    {
        if (preg_match('/how much|total|summary|overview|kati/', $msg)) return 'summary';
        if (preg_match('/top|best|highest|best/', $msg)) return 'top';
        if (preg_match('/compare|vs|versus|bhanda/', $msg)) return 'compare';
        if (preg_match('/trend|growth|decline|badhi|ghati/', $msg)) return 'trend';
        if (preg_match('/alert|warning|attention|savdhan/', $msg)) return 'alert';
        return 'query';
    }

    protected function detectTimeframe($msg): ?string
    {
        if (preg_match('/today|aja/', $msg)) return 'today';
        if (preg_match('/yesterday|hijo/', $msg)) return 'yesterday';
        if (preg_match('/this week|yo hapta/', $msg)) return 'this_week';
        if (preg_match('/this month|yo mahina/', $msg)) return 'this_month';
        if (preg_match('/this quarter|yo trimas/', $msg)) return 'this_quarter';
        if (preg_match('/this year|yo barsa/', $msg)) return 'this_year';
        if (preg_match('/last week|geko hapta/', $msg)) return 'last_week';
        if (preg_match('/last month|geko mahina/', $msg)) return 'last_month';
        if (preg_match('/last quarter|geko trimas/', $msg)) return 'last_quarter';
        if (preg_match('/last year|geko barsa/', $msg)) return 'last_year';
        return null;
    }

    protected function detectEntity($msg): ?string
    {
        if (preg_match('/product|product|samagri/', $msg)) return 'product';
        if (preg_match('/customer|grahak/', $msg)) return 'customer';
        if (preg_match('/employee|karmachari/', $msg)) return 'employee';
        return null;
    }
}