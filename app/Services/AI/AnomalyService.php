<?php
namespace App\Services\AI;

use Illuminate\Support\Collection;

class AnomalyService
{
    public function detectSalesAnomalies(Collection $sales): array
    {
        $anomalies = [];
        if ($sales->isEmpty()) {
            return $anomalies;
        }

        $totals = $sales->pluck('total')->toArray();
        $mean = array_sum($totals) / count($totals);
        $stdDev = $this->calculateStdDev($totals, $mean);

        foreach ($sales as $sale) {
            $zScore = $this->calculateZScore($sale->total, $mean, $stdDev);
            if (abs($zScore) > 2.5) {
                $anomalies[] = [
                    'type' => 'sales',
                    'id' => $sale->id,
                    'value' => $sale->total,
                    'mean' => round($mean, 2),
                    'z_score' => round($zScore, 2),
                    'priority' => abs($zScore) > 4 ? 'high' : 'medium',
                    'message' => "Unusual sale detected: Rs. " . number_format($sale->total, 2),
                    'created_at' => $sale->created_at,
                ];
            }
        }

        return $anomalies;
    }

    public function detectExpenseAnomalies(Collection $expenses): array
    {
        $anomalies = [];
        if ($expenses->isEmpty()) {
            return $anomalies;
        }

        $amounts = $expenses->pluck('amount')->toArray();
        $mean = array_sum($amounts) / count($amounts);
        $stdDev = $this->calculateStdDev($amounts, $mean);

        foreach ($expenses as $expense) {
            $zScore = $this->calculateZScore($expense->amount, $mean, $stdDev);
            if (abs($zScore) > 2.5) {
                $anomalies[] = [
                    'type' => 'expense',
                    'id' => $expense->id,
                    'value' => $expense->amount,
                    'mean' => round($mean, 2),
                    'z_score' => round($zScore, 2),
                    'priority' => abs($zScore) > 4 ? 'high' : 'medium',
                    'message' => "Unusual expense detected: Rs. " . number_format($expense->amount, 2),
                    'created_at' => $expense->created_at,
                ];
            }
        }

        return $anomalies;
    }

    protected function calculateStdDev(array $values, float $mean): float
    {
        if (count($values) < 2) return 1;
        $sum = 0;
        foreach ($values as $v) {
            $sum += pow($v - $mean, 2);
        }
        return sqrt($sum / count($values));
    }

    protected function calculateZScore(float $value, float $mean, float $stdDev): float
    {
        if ($stdDev == 0) return 0;
        return ($value - $mean) / $stdDev;
    }
} 
