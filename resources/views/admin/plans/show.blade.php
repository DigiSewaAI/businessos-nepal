@extends('layouts.admin')

@section('title', 'Plan Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Plan: {{ $plan->name }}</h1>
        <div>
            <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Plan Information</strong></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><th>ID</th><td>{{ $plan->id }}</td></tr>
                        <tr><th>Name</th><td>{{ $plan->name }}</td></tr>
                        <tr><th>Price</th><td>NPR {{ number_format($plan->price, 2) }}</td></tr>
                        <tr><th>Duration</th><td>{{ $plan->duration_months }} months</td></tr>
                        <tr><th>Max Users</th><td>{{ $plan->max_users }}</td></tr>
                        <tr><th>Max Products</th><td>{{ $plan->max_products }}</td></tr>
                        <tr><th>Max Branches</th><td>{{ $plan->max_branches }}</td></tr>
                        <tr><th>Status</th>
                            <td>
                                <span class="badge bg-{{ $plan->is_active ? 'success' : 'danger' }}">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Created</th><td>{{ $plan->created_at->format('Y-m-d H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Features</strong></div>
                <div class="card-body">
                    @if(!empty($plan->features) && is_array($plan->features))
                        <ul class="list-group">
                            @foreach($plan->features as $key => $value)
                                <li class="list-group-item">
                                    <strong>{{ ucfirst($key) }}:</strong> 
                                    @if(is_bool($value))
                                        {{ $value ? '✅ Yes' : '❌ No' }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No features defined.</p>
                    @endif
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header"><strong>Subscriptions ({{ $plan->subscriptions->count() }})</strong></div>
                <div class="card-body">
                    @if($plan->subscriptions->count())
                        <ul class="list-group">
                            @foreach($plan->subscriptions->take(5) as $sub)
                                <li class="list-group-item">
                                    {{ $sub->organization->name ?? 'N/A' }}
                                    <span class="badge bg-{{ $sub->status === 'active' ? 'success' : 'warning' }}">
                                        {{ $sub->status }}
                                    </span>
                                </li>
                            @endforeach
                            @if($plan->subscriptions->count() > 5)
                                <li class="list-group-item text-muted">... and {{ $plan->subscriptions->count() - 5 }} more</li>
                            @endif
                        </ul>
                    @else
                        <p class="text-muted">No active subscriptions on this plan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection