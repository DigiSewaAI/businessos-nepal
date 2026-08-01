<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['organization', 'user'])->latest()->paginate(20);
        return view('admin.support.index', compact('tickets'));
    }

    public function create()
    {
        $organizations = Organization::all();
        $users = User::all();
        return view('admin.support.create', compact('organizations', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|string|in:low,medium,high,urgent',
        ]);

        $ticket = SupportTicket::create([
            'organization_id' => $request->organization_id,
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        return redirect()->route('admin.support.index')->with('success', "Ticket #{$ticket->id} created.");
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['organization', 'user', 'assignedTo'])->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $users = User::all();
        return view('admin.support.edit', compact('ticket', 'users'));
    }

    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,closed',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update($request->only(['status', 'priority', 'assigned_to']));
        return redirect()->route('admin.support.index')->with('success', "Ticket #{$ticket->id} updated.");
    }

    public function destroy($id)
    {
        SupportTicket::findOrFail($id)->delete();
        return redirect()->route('admin.support.index')->with('success', 'Ticket deleted.');
    }
}