<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Museum;
use Illuminate\Http\Request;

class TicketWebController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('museum')->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $museums = Museum::all();

        return view('admin.tickets.create', [
            'museums' => $museums,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'museum_id' => 'required|exists:museums,id',
            'ticket_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'slot' => 'required|integer|min:1',
        ]);

        Ticket::create($validated);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket berhasil ditambahkan');
    }

    public function edit(Ticket $ticket)
    {
        $museums = Museum::all();

        return view('admin.tickets.edit', [
            'ticket' => $ticket,
            'museums' => $museums,
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'museum_id' => 'required|exists:museums,id',
            'ticket_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'slot' => 'required|integer|min:1',
        ]);

        $ticket->update($validated);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil diperbarui!');
    }

    public function show(string $id)
    {
        // Jika tidak sengaja mengarah ke GET, lempar langsung ke fungsi update atau kembalikan ke form edit
        return redirect()->route('tickets.edit', $id);
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        if (! $ticket) {
            $ticketId = $request->input('ticket_id') ?? $request->route('ticket');
            $ticket = Ticket::findOrFail($ticketId);
        }

        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket berhasil dihapus');
    }
}
