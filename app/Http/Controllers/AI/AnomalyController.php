<?php
namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AI\Insight;
use App\Models\Sale;
use App\Models\Expense;
use App\Services\AI\AnomalyService;
use Illuminate\Http\Request;

class AnomalyController extends Controller
{
    protected $anomalyService;

    public function __construct(AnomalyService $anomalyService)
    {
        $this->anomalyService = $anomalyService;
    }

    public function index()
    {
        $anomalies = Insight::where('organization_id', auth()->user()->organization_id)
            ->where('type', 'anomaly')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('ai.anomalies', compact('anomalies'));
    }

    public function check(Request $request)
    {
        $orgId = auth()->user()->organization_id;
        $days = $request->days ?? 7;

        // Check sales anomalies
        $sales = Sale::where('organization_id', $orgId)
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays($days))
            ->get(['id', 'total', 'created_at']);

        $salesAnomalies = $this->anomalyService->detectSalesAnomalies($sales);

        // Check expense anomalies
        $expenses = Expense::where('organization_id', $orgId)
            ->where('created_at', '>=', now()->subDays($days))
            ->get(['id', 'amount', 'created_at']);

        $expenseAnomalies = $this->anomalyService->detectExpenseAnomalies($expenses);

        // Save anomalies as insights
        $allAnomalies = array_merge($salesAnomalies, $expenseAnomalies);
        foreach ($allAnomalies as $anomaly) {
            Insight::create([
                'organization_id' => $orgId,
                'type' => 'anomaly',
                'data' => $anomaly,
                'priority' => $anomaly['priority'] ?? 'medium',
                'is_read' => false,
            ]);
        }

        return redirect()->route('ai.anomalies')->with('success', count($allAnomalies) . ' anomalies detected.');
    }

    public function markRead($id)
    {
        $insight = Insight::where('organization_id', auth()->user()->organization_id)
            ->findOrFail($id);
        $insight->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Marked as read.');
    }
}