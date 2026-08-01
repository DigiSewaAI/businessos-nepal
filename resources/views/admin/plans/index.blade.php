@extends('layouts.admin')

@section('title', 'Plans')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Subscription Plans</h1>
        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Plan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price (NPR)</th>
                            <th>Duration</th>
                            <th>Users</th>
                            <th>Products</th>
                            <th>Branches</th>
                            <th>Active</th>
                            <th>Subscribers</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td><strong>{{ $plan->name }}</strong></td>
                            <td>{{ number_format($plan->price, 2) }}</td>
                            <td>{{ $plan->duration_months }} months</td>
                            <td>{{ $plan->max_users }}</td>
                            <td>{{ $plan->max_products }}</td>
                            <td>{{ $plan->max_branches }}</td>
                            <td>
                                <span class="badge bg-{{ $plan->is_active ? 'success' : 'danger' }}">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td><span class="badge bg-info">{{ $plan->subscriptions_count }}</span></td>
                            <td>
                                <a href="{{ route('admin.plans.show', $plan->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this plan?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="text-center">No plans found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $plans->links() }}
        </div>
    </div>
</div>
@endsection