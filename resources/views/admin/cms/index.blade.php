@extends('layouts.admin')

@section('title', 'CMS Pages')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>CMS Pages</h1>
        <a href="{{ route('admin.cms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Page
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
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Published</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td><strong>{{ $page->title }}</strong></td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td>
                                <span class="badge bg-{{ $page->is_published ? 'success' : 'danger' }}">
                                    {{ $page->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $page->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.cms.show', $page->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.cms.edit', $page->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.cms.destroy', $page->id) }}" method="POST" style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this page?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">No pages found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection