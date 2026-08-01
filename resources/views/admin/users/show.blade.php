@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>User Information</strong></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><th>ID</th><td>{{ $user->id }}</td></tr>
                        <tr><th>Name</th><td>{{ $user->name }}</td></tr>
                        <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                        <tr><th>Organization</th><td>{{ $user->organization->name ?? 'N/A' }}</td></tr>
                        <tr><th>Branch</th><td>{{ $user->branch->name ?? 'N/A' }}</td></tr>
                        <tr><th>Roles</th>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr><th>Email Verified</th><td>{{ $user->email_verified_at ? 'Yes' : 'No' }}</td></tr>
                        <tr><th>Created</th><td>{{ $user->created_at->format('Y-m-d H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Permissions</strong></div>
                <div class="card-body">
                    @if($user->permissions->count())
                        <ul class="list-group">
                            @foreach($user->permissions as $perm)
                                <li class="list-group-item">{{ $perm->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No direct permissions. Permissions inherited from roles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection