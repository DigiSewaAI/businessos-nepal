<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\Accounting\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index()
    {
        $accounts = Account::where('organization_id', auth()->user()->organization_id)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('code')
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $parentAccounts = Account::where('organization_id', auth()->user()->organization_id)
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get();

        return view('accounts.create', compact('parentAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'opening_balance' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $this->accountService->create($validated);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        return view('accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        $parentAccounts = Account::where('organization_id', auth()->user()->organization_id)
            ->whereNull('parent_id')
            ->where('id', '!=', $account->id)
            ->orderBy('code')
            ->get();

        return view('accounts.edit', compact('account', 'parentAccounts'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:accounts,code,' . $account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'is_active' => 'nullable|boolean',
        ]);

        $this->accountService->update($account, $validated);

        return redirect()->route('accounts.index')->with('success', 'Account updated.');
    }

    public function destroy(Account $account)
    {
        $this->accountService->delete($account);
        return redirect()->route('accounts.index')->with('success', 'Account deleted.');
    }
}