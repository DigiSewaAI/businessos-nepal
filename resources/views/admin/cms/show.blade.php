@extends('layouts.admin')

@section('title', 'CMS Page: ' . $page->title)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $page->title }}</h1>
        <div>
            <a href="{{ route('admin.cms.edit', $page->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.cms.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5>Content</h5>
                    <div class="bg-light p-3 rounded">
                        {!! nl2br(e($page->content)) !!}
                    </div>
                    @if($page->meta_description)
                        <hr>
                        <p><strong>Meta Description:</strong> {{ $page->meta_description }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><strong>Page Information</strong></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><th>ID</th><td>{{ $page->id }}</td></tr>
                        <tr><th>Slug</th><td><code>{{ $page->slug }}</code></td></tr>
                        <tr><th>Status</th>
                            <td>
                                <span class="badge bg-{{ $page->is_published ? 'success' : 'danger' }}">
                                    {{ $page->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Created</th><td>{{ $page->created_at->format('Y-m-d H:i') }}</td></tr>
                        <tr><th>Updated</th><td>{{ $page->updated_at->format('Y-m-d H:i') }}</td></tr>
                    </table>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header"><strong>Preview</strong></div>
                <div class="card-body">
                    <a href="/{{ $page->slug }}" target="_blank" class="btn btn-success w-100">
                        <i class="fas fa-external-link-alt"></i> View Public Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection