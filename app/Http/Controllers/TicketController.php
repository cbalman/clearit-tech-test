<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Document;
use App\Models\User; // Needed for fetching agents
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification; // Facade to send notifications to collections
use App\Notifications\NewTicketNotification; // New notification for agents
use App\Notifications\DocumentRequestedNotification; // New notification for users

class TicketController extends Controller
{
    /**
     * Agent Dashboard: Displays all open/in progress tickets.
     */
    public function agentIndex()
    {
        // Agent can view all open and in-progress tickets
        $tickets = Ticket::with(['creator', 'agent'])
            ->whereIn('status', ['new', 'in_progress'])
            ->latest()
            ->paginate(15);

        return view('agent.dashboard', compact('tickets'));
    }

    /**
     * User Dashboard: Displays only the user's tickets.
     */
    public function userIndex()
    {
        // User can only view tickets they created
        $tickets = Ticket::with(['agent'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('user.dashboard', compact('tickets'));
    }

    /**
     * Shows the form for creating a new ticket.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Stores a new ticket created by a User.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validatedData = $request->validate([
            'ticket_name' => 'required|string|max:255',
            'ticket_type' => 'required|in:1,2,3',
            'mode_of_transport' => 'required|in:air,land,sea',
            'product' => 'required|string',
            'country_origin' => 'required|string',
            'country_destination' => 'required|string',
        ]);

        // 2. Ticket Creation
        $ticket = Ticket::create(array_merge($validatedData, [
            'user_id' => auth()->id(),
            'status' => 'new', // Initial status
        ]));

        // 3. Notification (Requirement 4): Notify available agents
        $agents = User::where('role', 'agent')->get();
        Notification::send($agents, new NewTicketNotification($ticket));

        return redirect()->route('user.dashboard')->with('success', 'Ticket created and agents have been notified.');
    }

    /**
     * Displays the specified resource.
     */
    public function show(Ticket $ticket)
    {
        // Authorization: Ensure only the creator or an agent can view it
        if (Auth::user()->role !== 'agent' && Auth::id() !== $ticket->user_id) {
            abort(403, 'Unauthorized to view this ticket.');
        }

        // Load related documents for display
        $ticket->load('documents');

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Agent requests additional documentation from the user.
     * (Completes Documentation Exchange logic)
     * @param Ticket $ticket
     */
    public function requestDocument(Ticket $ticket)
    {
        // Authorization: Only agents can request documents
        if (Auth::user()->role !== 'agent') {
            abort(403, 'Unauthorized. Only agents can request documents.');
        }

        // 1. Update ticket status and assign the agent if it's new
        if ($ticket->status === 'new') {
            $ticket->status = 'in_progress';
            $ticket->agent_id = Auth::id(); // Assign the agent who is reviewing it
            $ticket->save();
        }

        // 2. Send notification to the user (creator of the ticket)
        $ticket->creator->notify(new DocumentRequestedNotification($ticket));

        return back()->with('success', 'Document request sent successfully to the user.');
    }

    /**
     * Method for the AGENT to update the ticket status.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        // Authorization: Only agents can update the status
        if (Auth::user()->role !== 'agent') {
            abort(403, 'Only agents can update the status.');
        }

        $request->validate([
            'status' => 'required|in:new,in_progress,completed',
        ]);

        $ticket->status = $request->status;
        $ticket->save();

        // Notification (Requirement 4): Notify the user about the status change
        // $ticket->creator->notify(new TicketStatusUpdatedNotification($ticket)); // Requires creating this notification class

        return back()->with('success', 'Ticket status updated to ' . $request->status);
    }

    /**
     * Allows the user/agent to upload a document to an existing ticket.
     * (Requirement 3: Documentation Exchange)
     */
    public function uploadDocument(Request $request, Ticket $ticket)
    {
        // Authorization: Allow the creator or an agent
        if (Auth::id() !== $ticket->user_id && Auth::user()->role !== 'agent') {
            abort(403, 'Unauthorized to upload documents to this ticket.');
        }

        // 1. Validation
        $request->validate([
            'document_file' => 'required|file|max:5000|mimes:pdf,jpg,png,doc,docx',
            'is_requested' => 'nullable|boolean'
        ]);

        // 2. File Processing
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');

            // Store in 'storage/app/public/documents/{ticket_id}'
            $filePath = $file->store("documents/{$ticket->id}", 'public');

            // 3. Register in the database
            $document = Document::create([
                'ticket_id' => $ticket->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'requested_by_agent' => $request->boolean('is_requested'),
            ]);

            // 4. Notification (Notify the agent/user of the upload)
            // ...

            return back()->with('success', 'Document uploaded and registered successfully.');
        }

        return back()->with('error', 'Error processing the file.');
    }
}
