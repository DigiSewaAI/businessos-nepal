@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Ticket #{{ $ticket->id }}: {{ $ticket->subject }}</h1>
        <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><strong>Ticket Details</strong></div>
                <div class="card-body">
                    <p><strong>From:</strong> {{ $ticket->user->name ?? 'N/A' }} ({{ $ticket->user->email ?? 'N/A' }})</p>
                    <p><strong>Organization:</strong> {{ $ticket->organization->name ?? 'N/A' }}</p>
                    <p><strong>Message:</strong></p>
                    <div class="bg-light p-3 rounded">
                        {{ $ticket->message }}
                    </div>
                    <hr>
                    <p><strong>Created:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                    @if($ticket->assigned_to)
                        <p><strong>Assigned to:</strong> {{ $ticket->assignee->name ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><strong>Update Status</strong></div>
                <div class="card-body">
                    <form action="{{ route('admin.support.update', $ticket->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection