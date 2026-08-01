@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Payment #{{ $payment->id }}</h1>
        <div>
            <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><strong>Payment Information</strong></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><th>ID</th><td>#{{ $payment->id }}</td></tr>
                        <tr><th>Organization</th><td>{{ $payment->organization->name ?? 'N/A' }}</td></tr>
                        <tr><th>Subscription</th>
                            <td>
                                @if($payment->subscription)
                                    {{ $payment->subscription->plan->name ?? 'N/A' }}
                                    ({{ $payment->subscription->organization->name ?? 'N/A' }})
                                @else
                                    <span class="text-muted">Not linked</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Amount</th><td><strong>NPR {{ number_format($payment->amount, 2) }}</strong></td></tr>
                        <tr><th>Payment Method</th><td>{{ ucfirst($payment->payment_method) }}</td></tr>
                        <tr><th>Transaction ID</th><td>{{ $payment->transaction_id ?? '-' }}</td></tr>
                        <tr><th>Status</th>
                            <td>
                                <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Date</th><td>{{ $payment->created_at->format('Y-m-d H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><strong>Quick Actions</strong></div>
                <div class="card-body">
                    @if($payment->status === 'pending')
                        <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-check"></i> Mark as Completed
                            </button>
                        </form>
                        <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="failed">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times"></i> Mark as Failed
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection