<?php
namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AI\Forecast;
use App\Models\Sale;
use App\Models\Purchase;
use App\Services\AI\ForecastingService;
use Illuminate\Http\Request;

class ForecastController extends Controller
{
    protected $forecastService;

    public function __construct(ForecastingService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    public function index()
    {
        $forecasts = Forecast::where('organization_id', auth()->user()->organization_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('ai.forecast', compact('forecasts'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'metric' => 'required|in:sales,purchases',
            'days' => 'required|integer|min:7|max:365',
        ]);

        $orgId = auth()->user()->organization_id;

        // Fetch historical data
        if ($request->metric === 'sales') {
            $data = Sale::where('organization_id', $orgId)
                ->where('status', 'completed')
                ->orderBy('created_at')
                ->get(['created_at', 'total'])
                ->groupBy(function($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->map(fn($group) => $group->sum('total'))
                ->toArray();
        } else {
            $data = Purchase::where('organization_id', $orgId)
                ->orderBy('created_at')
                ->get(['created_at', 'total'])
                ->groupBy(function($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->map(fn($group) => $group->sum('total'))
                ->toArray();
        }

        if (empty($data)) {
            return back()->with('error', 'Not enough data for forecasting.');
        }

        // Generate forecast
        $result = $this->forecastService->forecast($data, $request->days);

        // Save forecast
        $forecast = Forecast::create([
            'organization_id' => $orgId,
            'metric' => $request->metric,
            'predictions' => $result['predictions'],
            'confidence' => $result['confidence'] ?? 85,
            'forecast_date' => now(),
            'forecast_until' => now()->addDays($request->days),
        ]);

        return redirect()->route('ai.forecast')->with('success', 'Forecast generated successfully.');
    }
}