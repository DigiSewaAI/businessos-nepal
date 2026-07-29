<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Services\Restaurant\TableService;
use Illuminate\Http\Request;

class TableController extends Controller
{
    protected $tableService;

    public function __construct(TableService $tableService)
    {
        $this->tableService = $tableService;
    }

    public function index()
    {
        $tables = RestaurantTable::where('organization_id', auth()->user()->organization_id)
            ->orderBy('number')
            ->paginate(20);

        return view('restaurant.tables.index', compact('tables'));
    }

    public function layout()
    {
        $layout = $this->tableService->getTableLayout();
        return view('restaurant.tables.layout', compact('layout'));
    }

    public function create()
    {
        return view('restaurant.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'status' => 'nullable|in:available,occupied,reserved,unavailable',
        ]);

        $this->tableService->create($validated);
        return redirect()->route('restaurant.tables.index')->with('success', 'Table created.');
    }

    public function edit(RestaurantTable $table)
    {
        return view('restaurant.tables.edit', compact('table'));
    }

    public function update(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'status' => 'nullable|in:available,occupied,reserved,unavailable',
            'is_active' => 'nullable|boolean',
        ]);

        $this->tableService->update($table, $validated);
        return redirect()->route('restaurant.tables.index')->with('success', 'Table updated.');
    }

    public function toggleStatus(RestaurantTable $table, $status)
    {
        $this->tableService->toggleStatus($table, $status);
        return redirect()->back()->with('success', 'Table status updated.');
    }

    public function generateQR(RestaurantTable $table)
    {
        app(QRService::class)->generateQR($table);
        return redirect()->back()->with('success', 'QR code generated.');
    }
}