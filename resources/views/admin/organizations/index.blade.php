@extends('layouts.admin')

@section('title', 'Organizations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Organizations</h1>
        <a href="{{ route('admin.organizations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Organization
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
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Users</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizations as $org)
                        <tr>
                            <td>{{ $org->id }}</td>
                            <td><strong>{{ $org->name }}</strong></td>
                            <td>{{ $org->email ?? '-' }}</td>
                            <td>{{ $org->phone ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $org->users_count }}</span></td>
                            <td>{{ $org->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.organizations.show', $org->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.organizations.edit', $org->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.organizations.destroy', $org->id) }}" method="POST" style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this organization?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No organizations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $organizations->links() }}
        </div>
    </div>
</div>
@endsection