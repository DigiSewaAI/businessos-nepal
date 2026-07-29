<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School\FeeCollection;
use App\Models\School\Student;
use App\Models\School\FeeStructure;
use App\Services\School\FeeService;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index()
    {
        $invoices = FeeCollection::with('student', 'feeStructure')
            ->where('organization_id', auth()->user()->organization_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('school.fees.index', compact('invoices'));
    }

    public function generate($studentId)
    {
        try {
            $invoices = $this->feeService->generateInvoices($studentId);
            return redirect()->route('school.fees.index')->with('success', 'Fee invoices generated!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pay(Request $request, FeeCollection $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0|max:' . $invoice->amount,
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->feeService->payInvoice($invoice, $validated);
            return redirect()->route('school.fees.index')->with('success', 'Payment recorded!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function summary($studentId)
    {
        $summary = $this->feeService->getFeeSummary($studentId);
        return view('school.fees.show', compact('summary'));
    }

    public function show(FeeCollection $invoice)
    {
        $invoice->load('student', 'feeStructure');
        return view('school.fees.show', compact('invoice'));
    }
}