<?php
namespace App\Services\AI;

use App\Models\AI\AIAnalytics;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function log($data)
    {
        return AIAnalytics::create([
            'organization_id' => auth()->user()->organization_id,
            'user_id' => auth()->id(),
            'source' => $data['source'],
            'intent' => $data['intent'] ?? null,
            'query' => $data['query'],
            'response_time_ms' => $data['response_time_ms'] ?? null,
            'tokens_used' => $data['tokens_used'] ?? 0,
            'success' => $data['success'] ?? true,
            'error_message' => $data['error_message'] ?? null,
        ]);
    }

    public function getDashboardStats($orgId)
    {
        $totalQueries = AIAnalytics::where('organization_id', $orgId)->count();
        $successCount = AIAnalytics::where('organization_id', $orgId)
            ->where('success', true)
            ->count();

        return [
            'total_queries' => $totalQueries,
            'avg_response_time' => AIAnalytics::where('organization_id', $orgId)->avg('response_time_ms'),
            'success_rate' => $totalQueries > 0 ? round(($successCount / $totalQueries) * 100, 2) : 0,
            'by_source' => AIAnalytics::where('organization_id', $orgId)
                ->select('source', DB::raw('count(*) as total'))
                ->groupBy('source')
                ->get(),
            'daily_queries' => AIAnalytics::where('organization_id', $orgId)
                ->whereDate('created_at', now())
                ->count(),
        ];
    }
}