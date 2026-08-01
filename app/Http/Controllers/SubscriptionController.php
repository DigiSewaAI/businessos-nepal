<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Plan;
use App\Models\Organization;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['organization', 'plan'])->latest()->paginate(20);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $organizations = Organization::all();
        $plans = Plan::where('is_active', true)->get();
        return view('admin.subscriptions.create', compact('organizations', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string|in:active,expired,cancelled,pending',
        ]);

        $subscription = Subscription::create($request->all());
        return redirect()->route('admin.subscriptions.index')->with('success', "Subscription #{$subscription->id} created.");
    }

    public function show($id)
    {
        $subscription = Subscription::with(['organization', 'plan'])->findOrFail($id);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $organizations = Organization::all();
        $plans = Plan::where('is_active', true)->get();
        return view('admin.subscriptions.edit', compact('subscription', 'organizations', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string|in:active,expired,cancelled,pending',
        ]);

        $subscription->update($request->only(['plan_id', 'end_date', 'status']));
        return redirect()->route('admin.subscriptions.index')->with('success', "Subscription #{$subscription->id} updated.");
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted.');
    }
}