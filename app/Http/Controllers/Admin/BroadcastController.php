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
        // Auto-nonaktifkan seluruh broadcast yang telah melewati batas tanggal dan jam tayang
        Broadcast::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['is_active' => false]);

        $broadcasts = Broadcast::with('creator')
            ->withCount('reads')
            ->latest()
            ->paginate(15);

        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    public function create()
    {
        return view('admin.broadcasts.create');
    }

    public function show(Broadcast $broadcast)
    {
        $broadcast->load('creator');

        return view('admin.broadcasts.show', compact('broadcast'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'category' => 'nullable|string|in:operasional,kebijakan,pemeliharaan,tiket,darurat,umum',
            'urgency' => 'required|in:info,warning,danger,success',
            'display_type' => 'required|in:bell,banner,popup',
            'target_role' => 'required|in:all,super_admin,admin,staff,visitor,user',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validated['target_role'] === 'user') {
            $validated['target_role'] = 'visitor';
        }
        $validated['category'] = $validated['category'] ?? 'umum';
        $validated['created_by_user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;
        $validated['expires_at'] = ! empty($validated['expires_at']) ? $validated['expires_at'] : null;

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
            'category' => 'nullable|string|in:operasional,kebijakan,pemeliharaan,tiket,darurat,umum',
            'urgency' => 'required|in:info,warning,danger,success',
            'display_type' => 'required|in:bell,banner,popup',
            'target_role' => 'required|in:all,super_admin,admin,staff,visitor,user',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validated['target_role'] === 'user') {
            $validated['target_role'] = 'visitor';
        }
        $validated['category'] = $validated['category'] ?? 'umum';
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;
        $validated['expires_at'] = ! empty($validated['expires_at']) ? $validated['expires_at'] : null;

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
        // Proteksi: cegah aktivasi jika masa berlaku sudah kedaluwarsa
        if (! $broadcast->is_active && $broadcast->isExpired()) {
            return back()->with('error', 'Pengumuman ini tidak dapat diaktifkan kembali karena telah melewati masa berlaku. Silakan perpanjang tanggal dan jam kedaluwarsa melalui menu Edit terlebih dahulu.');
        }

        $broadcast->update([
            'is_active' => ! $broadcast->is_active,
        ]);

        $statusText = $broadcast->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengumuman berhasil {$statusText}.");
    }
}
