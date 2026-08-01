@extends('layouts.admin')

@section('title', 'Subscriptions')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Subscriptions</h1>
        <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Subscription
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
                            <th>Organization</th>
                            <th>Plan</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $sub)
                        <tr>
                            <td>#{{ $sub->id }}</td>
                            <td>{{ $sub->organization->name ?? 'N/A' }}</td>
                            <td>{{ $sub->plan->name ?? 'N/A' }}</td>
                            <td>{{ $sub->start_date->format('Y-m-d') }}</td>
                            <td>{{ $sub->end_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge bg-{{ $sub->status === 'active' ? 'success' : ($sub->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.subscriptions.show', $sub->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.subscriptions.edit', $sub->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $sub->id) }}" method="POST" style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this subscription?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No subscriptions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection