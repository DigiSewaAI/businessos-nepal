<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\KOTLog;
use App\Services\Restaurant\KOTService;
use Illuminate\Http\Request;

class KOTController extends Controller
{
    protected $kotService;

    public function __construct(KOTService $kotService)
    {
        $this->kotService = $kotService;
    }

    public function index()
    {
        $kots = KOTLog::with('order.table')
            ->where('organization_id', auth()->user()->organization_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('restaurant.kot.index', compact('kots'));
    }

    public function pending()
    {
        $kots = $this->kotService->getPendingKOTs();
        return view('restaurant.kitchen', compact('kots'));
    }

    public function markPrinted(KOTLog $kot)
    {
        $this->kotService->markAsPrinted($kot);
        return redirect()->back()->with('success', 'KOT marked as printed.');
    }
}