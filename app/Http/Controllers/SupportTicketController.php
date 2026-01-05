<?php
namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportTicketAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupportTicketController extends Controller
{
    //
    public function index()
    {
        $title   = "Support Tickets";
        $tickets = SupportTicket::all();
        return view('dashboard.pages.support_tickets.index', compact('tickets', 'title'));
    }

    public function create()
    {
        $title = "Create Support Ticket";
        $mode  = 'create';

        return view('dashboard.pages.support_tickets.manage', compact('title', 'mode'));
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'type'          => 'required|in:issue,complaint,dispute,suggestion,other',
            'description'   => 'required|string',
            'attachments'   => 'nullable|array|max:5',
            'attachments.*' => 'file|max:2048',
        ]);

        DB::transaction(function () use ($validated, $request) {

            $ticket = SupportTicket::create([
                'user_id'     => Auth::id(),
                'title'       => $validated['title'],
                'type'        => $validated['type'],
                'description' => $validated['description'],
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {

                    $path = $file->store('support_tickets', 'public');

                    SupportTicketAttachments::create([
                        'support_ticket_id' => $ticket->id,
                        'file_path'         => $path,
                        'original_name'     => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('support.tickets')
            ->with('success', 'Ticket created successfully.');
    }

    public function edit(SupportTicket $ticket)
    {
        abort_if($ticket->user_id !== Auth::id(), 403);

        $title = 'Edit Support Ticket';
        $mode  = 'edit';

        return view('dashboard.pages.support_tickets.manage', compact('title', 'mode', 'ticket'));
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        abort_if($ticket->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'type'          => 'required|in:issue,complaint,dispute,suggestion,other',
            'description'   => 'required|string',
            'attachments'   => 'nullable|array|max:5',
            'attachments.*' => 'file|max:2048',
        ]);

        DB::transaction(function () use ($validated, $request, $ticket) {

            $ticket->update([
                'title'       => $validated['title'],
                'type'        => $validated['type'],
                'description' => $validated['description'],
            ]);

            if ($request->hasFile('attachments')) {

                $existingCount = $ticket->attachments()->count();
                $newCount      = count($request->file('attachments'));

                if (($existingCount + $newCount) > 5) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'attachments' => 'You can upload a maximum of 5 attachments per ticket.',
                    ]);
                }

                foreach ($request->file('attachments') as $file) {

                    $path = $file->store('support_tickets', 'public');

                    SupportTicketAttachments::create([
                        'support_ticket_id' => $ticket->id,
                        'file_path'         => $path,
                        'original_name'     => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('support.tickets')
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(SupportTicket $ticket)
    {
        abort_if($ticket->user_id !== Auth::id(), 403);

        DB::transaction(function () use ($ticket) {
            foreach ($ticket->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
                $attachment->delete();
            }
            $ticket->delete();
        });

        return redirect()->route('support.tickets')->with('success', 'Ticket deleted successfully.');
    }
}
