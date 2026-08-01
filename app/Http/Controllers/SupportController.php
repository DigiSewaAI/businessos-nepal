<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\Organization;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['organization', 'user'])->latest()->paginate(20);
        return view('admin.support.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['organization', 'user', 'replies'])->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,closed',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
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