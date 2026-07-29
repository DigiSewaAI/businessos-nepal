<?php

namespace App\Http\Controllers;

use App\Services\Accounting\FinancialStatementService;
use App\Services\Accounting\LedgerService;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    protected $ledgerService;
    protected $financialService;

    public function __construct(LedgerService $ledgerService, FinancialStatementService $financialService)
    {
        $this->ledgerService = $ledgerService;
        $this->financialService = $financialService;
    }

    public function trialBalance()
    {
        $trialBalance = $this->ledgerService->getTrialBalance();
        return view('reports.trial-balance', compact('trialBalance'));
    }

    public function incomeStatement(Request $request)
    {
        $startDate = $request->start_date ?: now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?: now()->endOfMonth()->toDateString();

        $data = $this->financialService->getIncomeStatement($startDate, $endDate);
        return view('reports.income-statement', $data);
    }

    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->as_of_date ?: now()->toDateString();
        $data = $this->financialService->getBalanceSheet($asOfDate);
        return view('reports.balance-sheet', $data);
    }
}