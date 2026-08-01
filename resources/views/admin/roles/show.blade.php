@extends('layouts.admin')

@section('title', 'Role Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Role: {{ $role->name }}</h1>
        <div>
            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Role Information</strong></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><th>ID</th><td>{{ $role->id }}</td></tr>
                        <tr><th>Name</th><td><strong>{{ $role->name }}</strong></td></tr>
                        <tr><th>Guard Name</th><td>{{ $role->guard_name }}</td></tr>
                        <tr><th>Created</th><td>{{ $role->created_at->format('Y-m-d H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><strong>Permissions ({{ $role->permissions->count() }})</strong></div>
                <div class="card-body">
                    @if($role->permissions->count())
                        <div class="row">
                            @foreach($role->permissions->groupBy('module') as $module => $perms)
                            <div class="col-12 mb-2">
                                <strong>{{ ucfirst($module) }}</strong>
                                <ul class="list-group">
                                    @foreach($perms as $perm)
                                        <li class="list-group-item">{{ $perm->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No permissions assigned to this role.</p>
                    @endif
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header"><strong>Users with this Role ({{ $role->users->count() }})</strong></div>
                <div class="card-body">
                    @if($role->users->count())
                        <ul class="list-group">
                            @foreach($role->users->take(10) as $user)
                                <li class="list-group-item">{{ $user->name }} <small class="text-muted">({{ $user->email }})</small></li>
                            @endforeach
                            @if($role->users->count() > 10)
                                <li class="list-group-item text-muted">... and {{ $role->users->count() - 10 }} more</li>
                            @endif
                        </ul>
                    @else
                        <p class="text-muted">No users assigned to this role.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection