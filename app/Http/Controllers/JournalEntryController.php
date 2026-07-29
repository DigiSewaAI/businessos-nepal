<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Services\Accounting\JournalService;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    protected $journalService;

    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'account_id']);
        $entries = $this->journalService->getJournalEntries($filters);
        $accounts = Account::where('organization_id', auth()->user()->organization_id)
            ->orderBy('code')
            ->get();

        return view('journal-entries.index', compact('entries', 'accounts'));
    }

    public function create()
    {
        $accounts = Account::where('organization_id', auth()->user()->organization_id)
            ->orderBy('code')
            ->get();

        return view('journal-entries.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
            'lines.*.description' => 'nullable|string',
        ]);

        try {
            $entry = $this->journalService->createEntry($validated, $validated['lines']);
            return redirect()->route('journal-entries.index')->with('success', 'Journal entry created.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load('lines.account', 'createdBy');
        return view('journal-entries.show', compact('journalEntry'));
    }
}