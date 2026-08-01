@extends('layouts.admin')

@section('title', 'Ticket Details')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.support.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center shadow-lg shadow-rose-200">
                            <i class="fa-solid fa-ticket text-white text-sm"></i>
                        </span>
                        Ticket #{{ $ticket->id }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $ticket->subject }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.support.edit', $ticket->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.support.destroy', $ticket->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Delete this ticket?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-circle-info text-rose-500 mr-2"></i> Ticket Information</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Organization</span>
                            <span class="text-sm font-medium text-gray-800">{{ $ticket->organization->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Created By</span>
                            <span class="text-sm font-medium text-gray-800">{{ $ticket->user->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Status</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium 
                                {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-700' : 
                                   ($ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 
                                   ($ticket->status === 'resolved' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600')) }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-sm text-gray-500">Priority</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium 
                                {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-700' : 
                                   ($ticket->priority === 'high' ? 'bg-orange-100 text-orange-700' : 
                                   ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600')) }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Assigned To</span>
                            <span class="text-sm font-medium text-gray-800">{{ $ticket->assignedTo->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-message text-blue-500 mr-2"></i> Message</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->message }}</p>
                        <p class="text-xs text-gray-400 mt-3">Created: {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-clock text-blue-500 mr-2"></i> Timeline</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="text-sm text-gray-600 border-l-2 border-blue-300 pl-3">
                            <p class="font-medium">Created</p>
                            <p class="text-xs text-gray-400">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($ticket->updated_at != $ticket->created_at)
                            <div class="text-sm text-gray-600 border-l-2 border-gray-300 pl-3">
                                <p class="font-medium">Last Updated</p>
                                <p class="text-xs text-gray-400">{{ $ticket->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                        @if($ticket->status === 'resolved' || $ticket->status === 'closed')
                            <div class="text-sm text-emerald-600 border-l-2 border-emerald-300 pl-3">
                                <p class="font-medium">Resolved/Closed</p>
                                <p class="text-xs text-gray-400">{{ $ticket->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection