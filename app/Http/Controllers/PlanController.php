<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')->paginate(20);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:plans',
            'price' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1|max:60',
            'max_users' => 'required|integer|min:1',
            'max_products' => 'required|integer|min:0',
            'max_branches' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $plan = Plan::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration_months' => $request->duration_months,
            'max_users' => $request->max_users,
            'max_products' => $request->max_products,
            'max_branches' => $request->max_branches,
            'features' => $request->features ?? [],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.plans.index')->with('success', "Plan '{$plan->name}' created.");
    }

    public function show($id)
    {
        $plan = Plan::with('subscriptions.organization')->findOrFail($id);
        return view('admin.plans.show', compact('plan'));
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:plans,name,' . $plan->id,
            'price' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1|max:60',
            'max_users' => 'required|integer|min:1',
            'max_products' => 'required|integer|min:0',
            'max_branches' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $plan->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration_months' => $request->duration_months,
            'max_users' => $request->max_users,
            'max_products' => $request->max_products,
            'max_branches' => $request->max_branches,
            'features' => $request->features ?? [],
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.plans.index')->with('success', "Plan '{$plan->name}' updated.");
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        if ($plan->subscriptions()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete plan with active subscriptions.');
        }
        $name = $plan->name;
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', "Plan '{$name}' deleted.");
    }
}