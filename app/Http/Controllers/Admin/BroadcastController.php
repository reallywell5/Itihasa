<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastController extends Controller
{
    public function index()
    {
        $broadcasts = Broadcast::with('creator')
            ->latest()
            ->paginate(15);

        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    public function create()
    {
        return view('admin.broadcasts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'urgency' => 'required|in:info,warning,danger,success',
            'target_role' => 'required|in:all,admin,staff',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['created_by_user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        Broadcast::create($validated);

        return redirect()->route('admin.broadcasts.index')
            ->with('success', 'Pengumuman / Broadcast resmi berhasil diterbitkan.');
    }

    public function edit(Broadcast $broadcast)
    {
        return view('admin.broadcasts.edit', compact('broadcast'));
    }

    public function update(Request $request, Broadcast $broadcast)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'urgency' => 'required|in:info,warning,danger,success',
            'target_role' => 'required|in:all,admin,staff',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        $broadcast->update($validated);

        return redirect()->route('admin.broadcasts.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Broadcast $broadcast)
    {
        $broadcast->delete();

        return redirect()->route('admin.broadcasts.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function toggle(Broadcast $broadcast)
    {
        $broadcast->update([
            'is_active' => ! $broadcast->is_active,
        ]);

        $statusText = $broadcast->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengumuman berhasil {$statusText}.");
    }
}
