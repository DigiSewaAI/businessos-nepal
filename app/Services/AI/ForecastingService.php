<?php
namespace App\Services\AI;

class ForecastingService
{
    /**
     * Generate forecast for given data and number of days.
     * Now includes seasonal factor (Nepal-specific).
     *
     * @param array $data  Associative array of date => value
     * @param int $days    Number of days to forecast
     * @return array
     */
    public function forecast(array $data, int $days): array
    {
        // Simple moving average + linear regression
        $values = array_values($data);
        $dates = array_keys($data);

        if (count($values) < 5) {
            return [
                'predictions' => [],
                'confidence' => 50,
                'error' => 'Not enough data (minimum 5 days required)',
                'seasonal_factor' => 1.0,
                'note' => 'Insufficient data for seasonal adjustment.'
            ];
        }

        // Calculate simple moving average
        $window = min(7, count($values));
        $avg = array_sum(array_slice($values, -$window)) / $window;

        // Calculate trend
        $trend = $this->calculateTrend($values);

        // Generate base predictions (without seasonal factor)
        $predictions = [];
        for ($i = 1; $i <= $days; $i++) {
            $predictions[] = [
                'date' => now()->addDays($i)->format('Y-m-d'),
                'value' => round($avg + ($trend * $i), 2),
            ];
        }

        // ✅ Apply Seasonal Factor (Nepal-specific)
        $seasonalFactor = $this->getSeasonalFactor();
        foreach ($predictions as &$prediction) {
            $prediction['value'] = round($prediction['value'] * $seasonalFactor, 2);
        }

        // Calculate confidence
        $confidence = $this->calculateConfidence($values, $avg, $trend);

        return [
            'predictions' => $predictions,
            'confidence' => $confidence,
            'average' => $avg,
            'trend' => $trend,
            'seasonal_factor' => $seasonalFactor,
            'note' => $this->getSeasonalNote($seasonalFactor),
        ];
    }

    /**
     * Calculate linear trend from historical values.
     */
    protected function calculateTrend(array $values): float
    {
        $n = count($values);
        if ($n < 2) return 0;

        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $x = $i + 1;
            $y = $values[$i];
            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }

        $denominator = ($n * $sumX2) - ($sumX * $sumX);
        if ($denominator == 0) return 0;

        return (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
    }

    /**
     * Calculate confidence level based on data variance.
     */
    protected function calculateConfidence(array $values, float $avg, float $trend): int
    {
        $n = count($values);
        if ($n < 2) return 50;

        $mean = array_sum($values) / $n;
        $variance = 0;
        foreach ($values as $v) {
            $variance += pow($v - $mean, 2);
        }
        $variance /= $n;
        $stdDev = sqrt($variance);

        // If std deviation is high, confidence is low
        $confidence = max(50, min(95, 95 - ($stdDev / ($mean + 1)) * 50));
        return (int) $confidence;
    }

    /**
     * ✅ Get seasonal factor based on current date (Nepal-specific).
     */
    protected function getSeasonalFactor(): float
    {
        $today = now();
        $month = $today->month;
        $day = $today->day;

        // Dashain (usually October)
        if ($month == 10 && $day >= 10 && $day <= 25) {
            return 1.4;  // 40% increase
        }

        // Tihar (usually November)
        if ($month == 11 && $day >= 1 && $day <= 15) {
            return 1.3;  // 30% increase
        }

        // Month-end (last 3 days)
        if ($day >= 28) {
            return 1.2;  // 20% increase (salary spending)
        }

        // Saturday (bazar day)
        if ($today->isSaturday()) {
            return 1.15;  // 15% increase
        }

        return 1.0;  // Normal
    }

    /**
     * ✅ Get a human-readable note for the seasonal factor.
     */
    protected function getSeasonalNote(float $factor): string
    {
        if ($factor >= 1.4) return "🪁 Dashain peak season - high demand expected.";
        if ($factor >= 1.3) return "🪔 Tihar festival - moderate increase expected.";
        if ($factor >= 1.2) return "📆 Month-end - salary spending boost expected.";
        if ($factor >= 1.15) return "📅 Saturday - market day boost expected.";
        return "📊 Normal season - no festival impact.";
    }
}