@extends('layouts.admin')

@section('title', 'Backups')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Backups</h1>
        <form action="{{ route('admin.backups.create') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Backup
            </button>
        </form>
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
                            <th>#</th>
                            <th>File Name</th>
                            <th>Size</th>
                            <th>Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backupInfo as $index => $backup)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><code>{{ $backup['name'] }}</code></td>
                            <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                            <td>{{ date('Y-m-d H:i', $backup['modified']) }}</td>
                            <td>
                                <a href="{{ route('admin.backups.download', $backup['name']) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <form action="{{ route('admin.backups.destroy', $backup['name']) }}" method="POST" style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this backup?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No backups found. <a href="{{ route('admin.backups.create') }}">Create your first backup now.</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection