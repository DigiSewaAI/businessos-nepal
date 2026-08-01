@extends('layouts.admin')

@section('title', 'Organization Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Organization: {{ $organization->name }}</h1>
        <div>
            <a href="{{ route('admin.organizations.edit', $organization->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.organizations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Basic Information</strong></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><th>ID</th><td>{{ $organization->id }}</td></tr>
                        <tr><th>Name</th><td>{{ $organization->name }}</td></tr>
                        <tr><th>Email</th><td>{{ $organization->email ?? '-' }}</td></tr>
                        <tr><th>Phone</th><td>{{ $organization->phone ?? '-' }}</td></tr>
                        <tr><th>Address</th><td>{{ $organization->address ?? '-' }}</td></tr>
                        <tr><th>Subdomain</th><td>{{ $organization->subdomain ?? '-' }}</td></tr>
                        <tr><th>Created</th><td>{{ $organization->created_at->format('Y-m-d H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Statistics</strong></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><th>Total Users</th><td>{{ $organization->users->count() }}</td></tr>
                        <tr><th>Total Branches</th><td>{{ $organization->branches->count() }}</td></tr>
                        <tr><th>Subscription</th>
                            <td>
                                @if($organization->subscription)
                                    {{ $organization->subscription->plan->name ?? 'N/A' }}
                                    <span class="badge bg-{{ $organization->subscription->status === 'active' ? 'success' : 'danger' }}">
                                        {{ $organization->subscription->status }}
                                    </span>
                                @else
                                    <span class="text-muted">No subscription</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection