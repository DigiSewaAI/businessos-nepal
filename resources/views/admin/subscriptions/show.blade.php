@extends('layouts.admin')

@section('title', 'Subscription Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Subscription #{{ $subscription->id }}</h1>
        <div>
            <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Subscription Information</strong></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><th>ID</th><td>#{{ $subscription->id }}</td></tr>
                        <tr><th>Organization</th><td>{{ $subscription->organization->name ?? 'N/A' }}</td></tr>
                        <tr><th>Plan</th><td>{{ $subscription->plan->name ?? 'N/A' }}</td></tr>
                        <tr><th>Price</th><td>NPR {{ number_format($subscription->plan->price ?? 0, 2) }}</td></tr>
                        <tr><th>Start Date</th><td>{{ $subscription->start_date->format('Y-m-d') }}</td></tr>
                        <tr><th>End Date</th><td>{{ $subscription->end_date->format('Y-m-d') }}</td></tr>
                        <tr><th>Days Remaining</th>
                            <td>
                                @if($subscription->status === 'active')
                                    {{ now()->diffInDays($subscription->end_date, false) > 0 ? now()->diffInDays($subscription->end_date) . ' days' : 'Expired' }}
                                @else
                                    {{ ucfirst($subscription->status) }}
                                @endif
                            </td>
                        </tr>
                        <tr><th>Status</th>
                            <td>
                                <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Quick Actions</strong></div>
                <div class="card-body">
                    @if($subscription->status !== 'active')
                        <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="active">
                            <input type="hidden" name="plan_id" value="{{ $subscription->plan_id }}">
                            <input type="hidden" name="end_date" value="{{ $subscription->end_date->format('Y-m-d') }}">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-check"></i> Activate
                            </button>
                        </form>
                    @endif
                    @if($subscription->status === 'active')
                        <form action="{{ route('admin.subscriptions.update', $subscription->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <input type="hidden" name="plan_id" value="{{ $subscription->plan_id }}">
                            <input type="hidden" name="end_date" value="{{ $subscription->end_date->format('Y-m-d') }}">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times"></i> Cancel Subscription
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @if($subscription->payments->count())
            <div class="card mt-3">
                <div class="card-header"><strong>Payments ({{ $subscription->payments->count() }})</strong></div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($subscription->payments as $payment)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>NPR {{ number_format($payment->amount, 2) }}</span>
                                <span>{{ $payment->created_at->format('Y-m-d') }}</span>
                                <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ $payment->status }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection