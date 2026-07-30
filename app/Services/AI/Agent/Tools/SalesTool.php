<?php
namespace App\Services\AI\Agent\Tools;

use App\Services\AI\Agent\Contracts\ToolInterface;
use App\Models\Sale;

class SalesTool implements ToolInterface
{
    public function getName(): string
    {
        return 'sales_summary';
    }

    public function getDescription(): string
    {
        return 'Get sales summary for today or specific date';
    }

    public function getParameters(): array
    {
        return [
            'date' => 'date|nullable',
        ];
    }

    public function execute(array $params): array
    {
        $orgId = auth()->user()->organization_id;
        $date = $params['date'] ?? now()->toDateString();

        $sales = Sale::where('organization_id', $orgId)
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->sum('total');

        $count = Sale::where('organization_id', $orgId)
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->count();

        return [
            'date' => $date,
            'total_sales' => $sales,
            'order_count' => $count,
        ];
    }
}