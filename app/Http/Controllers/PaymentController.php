<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['organization', 'subscription'])->latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with(['organization', 'subscription'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function create()
    {
        return view('admin.payments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'transaction_id' => 'nullable|string|max:255',
            'status' => 'required|string|in:pending,completed,failed,refunded',
        ]);

        $payment = Payment::create($request->all());
        return redirect()->route('admin.payments.index')->with('success', "Payment #{$payment->id} recorded.");
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.payments.edit', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $request->validate([
            'status' => 'required|string|in:pending,completed,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $payment->update($request->only(['status', 'transaction_id']));
        return redirect()->route('admin.payments.index')->with('success', "Payment #{$payment->id} updated.");
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted.');
    }
}