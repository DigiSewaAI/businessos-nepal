<?php

namespace App\Http\Controllers;

use App\Models\Cashbook;
use App\Services\CashbookService;
use Illuminate\Http\Request;

class CashbookController extends Controller
{
    public function index(CashbookService $cashbookService)
    {
        $cashbook = $cashbookService->getTodayCashbook();
        $cashbook = $cashbookService->updateCashbook();

        $history = Cashbook::where('branch_id', auth()->user()->branch_id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('cashbook.index', compact('cashbook', 'history'));
    }

    public function close(Request $request, CashbookService $cashbookService)
    {
        $request->validate([
            'physical_count' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $cashbook = $cashbookService->closeCashbook(
            $request->physical_count,
            $request->notes
        );

        return redirect()->route('cashbook.index')->with('success', 'Cashbook closed successfully!');
    }
}