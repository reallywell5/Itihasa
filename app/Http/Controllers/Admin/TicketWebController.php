<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketWebController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $tickets = Ticket::with('museum')
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->where('museum_id', $user->museum_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ticket_name', 'like', "%{$search}%")
                        ->orWhereHas('museum', function ($mq) use ($search) {
                            $mq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->get();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki akses pemantauan data. Pengelolaan tiket dilakukan oleh Admin Museum.');
        }

        $museums = Museum::where('id', $user->museum_id)->get();

        return view('admin.tickets.create', [
            'museums' => $museums,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki akses pemantauan data. Pengelolaan tiket dilakukan oleh Admin Museum.');
        }

        $validated = $request->validate([
            'ticket_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'slot' => 'required|integer|min:1',
        ]);

        $validated['museum_id'] = $user->museum_id;

        Ticket::create($validated);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil ditambahkan untuk museum Anda.');
    }

    public function edit(Ticket $ticket)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki akses pemantauan data. Pengelolaan tiket dilakukan oleh Admin Museum.');
        }

        if ($ticket->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses ke tiket museum ini.');
        }

        $museums = Museum::where('id', $user->museum_id)->get();

        return view('admin.tickets.edit', [
            'ticket' => $ticket,
            'museums' => $museums,
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki akses pemantauan data. Pengelolaan tiket dilakukan oleh Admin Museum.');
        }

        if ($ticket->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses ke tiket museum ini.');
        }

        $validated = $request->validate([
            'ticket_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'slot' => 'required|integer|min:1',
        ]);

        $validated['museum_id'] = $user->museum_id;

        $ticket->update($validated);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil diperbarui!');
    }

    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        if (! $user->isSuperAdmin() && $ticket->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses ke tiket museum ini.');
        }

        return redirect()->route('tickets.index');
    }

    public function destroy(Ticket $ticket)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki akses pemantauan data. Pengelolaan tiket dilakukan oleh Admin Museum.');
        }

        if ($ticket->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses ke tiket museum ini.');
        }

        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }
}
