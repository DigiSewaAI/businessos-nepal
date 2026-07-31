<?php

namespace App\Services\AI;

class IntentParser
{
    /**
     * Parse the user message and return structured intent.
     */
    public function parse(string $message): array
    {
        $lower = strtolower($message);

        return [
            'category'   => $this->detectCategory($lower),
            'action'     => $this->detectAction($lower),
            'timeframe'  => $this->detectTimeframe($lower),
            'entity'     => $this->detectEntity($lower),
            'parameters' => [],
        ];
    }

    /**
     * Detect the primary category (sales, inventory, financial, school, etc.)
     * 
     * 🔥 Enhanced with more keywords for accurate detection.
     */
    protected function detectCategory(string $msg): string
    {
        // ─── Sales / Revenue ──────────────────────────────────────────────
        if (preg_match('/sales|sell|revenue|income|bikri|bikri|bikri|bikri|bikri|bikri|bikri/i', $msg)) {
            return 'sales';
        }

        // ─── Inventory / Stock ────────────────────────────────────────────
        if (preg_match('/stock|inventory|product|item|samagri|baki|quantity|low stock/i', $msg)) {
            return 'inventory';
        }

        // ─── Financial (Profit / Loss / Expense) ────────────────────────
        if (preg_match('/profit|loss|expense|balance|cash|kharcha|laabh|ghata|budget/i', $msg)) {
            return 'financial';
        }

        // ─── School / Attendance / Students ─────────────────────────────
        if (preg_match('/student|fee|attendance|exam|class|vidhyarthi|school|attendence|teacher|grade/i', $msg)) {
            return 'school';
        }

        // ─── Restaurant / Orders / KOT ──────────────────────────────────
        if (preg_match('/order|menu|table|kitchen|restaurant|kot|waiter|bill|dining/i', $msg)) {
            return 'restaurant';
        }

        // ─── Forecasting / Prediction ───────────────────────────────────
        if (preg_match('/predict|forecast|projection|bhawi|anuman|estimate/i', $msg)) {
            return 'forecast';
        }

        // ─── Anomaly / Alerts ────────────────────────────────────────────
        if (preg_match('/anomaly|unusual|suspicious|alert|bigreko|abnormal|warning/i', $msg)) {
            return 'anomaly';
        }

        // ─── Default ─────────────────────────────────────────────────────
        return 'general';
    }

    /**
     * Detect the action (summary, compare, trend, etc.)
     */
    protected function detectAction(string $msg): string
    {
        if (preg_match('/how much|total|summary|overview|kati|report|detail/i', $msg)) {
            return 'summary';
        }
        if (preg_match('/top|best|highest|best|most|popular|leader/i', $msg)) {
            return 'top';
        }
        if (preg_match('/compare|vs|versus|bhanda|difference|diff/i', $msg)) {
            return 'compare';
        }
        if (preg_match('/trend|growth|decline|badhi|ghati|change|increase|decrease/i', $msg)) {
            return 'trend';
        }
        if (preg_match('/alert|warning|attention|savdhan|notify|notification/i', $msg)) {
            return 'alert';
        }
        return 'query';
    }

    /**
     * Detect timeframes like today, this week, last month, etc.
     */
    protected function detectTimeframe(string $msg): ?string
    {
        if (preg_match('/today|aja|aaja|aaja/i', $msg)) return 'today';
        if (preg_match('/yesterday|hijo|hijjo/i', $msg)) return 'yesterday';
        if (preg_match('/this week|yo hapta|yo saptaah/i', $msg)) return 'this_week';
        if (preg_match('/this month|yo mahina|yo maas/i', $msg)) return 'this_month';
        if (preg_match('/this quarter|yo trimas|yo taim/i', $msg)) return 'this_quarter';
        if (preg_match('/this year|yo barsa|yo saal/i', $msg)) return 'this_year';
        if (preg_match('/last week|geko hapta|pahilo hapta/i', $msg)) return 'last_week';
        if (preg_match('/last month|geko mahina|pahilo maas/i', $msg)) return 'last_month';
        if (preg_match('/last quarter|geko trimas|pahilo taim/i', $msg)) return 'last_quarter';
        if (preg_match('/last year|geko barsa|pahilo saal/i', $msg)) return 'last_year';
        return null;
    }

    /**
     * Detect the main entity (product, customer, employee, etc.)
     */
    protected function detectEntity(string $msg): ?string
    {
        if (preg_match('/product|product|samagri|item|goods|supplies/i', $msg)) return 'product';
        if (preg_match('/customer|grahak|client|buyer|purchaser/i', $msg)) return 'customer';
        if (preg_match('/employee|karmachari|staff|worker|personnel/i', $msg)) return 'employee';
        return null;
    }
}