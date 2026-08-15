<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MuseumWebController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $museums = Museum::query()
            ->when(! $user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('id', $user->museum_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.museums.index', compact('museums'));
    }

    public function create()
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat menambahkan museum baru.');
        }

        return view('admin.museums.create');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat menambahkan museum baru.');
        }

        $validated = $this->validateMuseum($request);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if ($file->isValid()) {
                $path = $file->store('museums', 'public');
                $validated['image'] = $path;
            } else {
                return back()
                    ->withErrors(['image' => 'File gambar tidak valid atau rusak.'])
                    ->withInput();
            }
        }

        $validated['operational_hours'] = $this->normalizeSchedule($request->input('operational_hours', []));

        Museum::create($validated);

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil ditambahkan');
    }

    public function edit(Museum $museum)
    {
        $user = auth()->user();
        if (! $user->isSuperAdmin() && $museum->id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit museum ini.');
        }

        return view('admin.museums.edit', compact('museum'));
    }

    public function update(Request $request, Museum $museum)
    {
        $user = auth()->user();
        if (! $user->isSuperAdmin() && $museum->id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit museum ini.');
        }

        $validated = $this->validateMuseum($request);

        if ($request->hasFile('image')) {
            if ($museum->image && Storage::disk('public')->exists($museum->image)) {
                Storage::disk('public')->delete($museum->image);
            }

            $file = $request->file('image');
            if ($file->isValid()) {
                $path = $file->store('museums', 'public');
                $validated['image'] = $path;
            } else {
                return back()
                    ->withErrors(['image' => 'File gambar tidak valid atau rusak.'])
                    ->withInput();
            }
        }

        $validated['operational_hours'] = $this->normalizeSchedule($request->input('operational_hours', []));

        $museum->update($validated);

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil diperbarui');
    }

    public function destroy(Museum $museum)
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat menghapus museum.');
        }

        if ($museum->image && Storage::disk('public')->exists($museum->image)) {
            Storage::disk('public')->delete($museum->image);
        }

        $museum->delete();

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil dihapus');
    }

    private function validateMuseum(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:museum,seni,budaya,alam,religius',
            'address' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'facilities' => 'nullable|array',
            'facilities.*' => 'required|string|max:100',
            'operational_hours' => 'required|array',
            'operational_hours.*.sessions' => 'nullable|array|max:2',
            'operational_hours.*.sessions.*.open' => 'nullable|date_format:H:i',
            'operational_hours.*.sessions.*.close' => 'nullable|date_format:H:i|after:operational_hours.*.sessions.*.open',
        ]);
    }

    /**
     * Bersihkan input jadwal mentah dari form:
     * - checkbox 'closed' cuma terkirim kalau dicentang, jadi default-nya false
     * - buang sesi yang kosong (open/close gak keisi keduanya)
     * - pastikan semua 7 hari selalu ada di hasil akhir, meski gak ada di input
     */
    private function normalizeSchedule(array $rawSchedule): array
    {
        $normalized = [];

        foreach (Museum::dayKeys() as $dayKey) {
            $dayInput = $rawSchedule[$dayKey] ?? [];
            $isClosed = isset($dayInput['closed']);

            $sessions = [];
            if (! $isClosed) {
                foreach (($dayInput['sessions'] ?? []) as $session) {
                    $open = $session['open'] ?? null;
                    $close = $session['close'] ?? null;

                    if ($open && $close) {
                        $sessions[] = ['open' => $open, 'close' => $close];
                    }
                }
            }

            // Kalau ternyata gak ada sesi valid sama sekali (semua kosong), anggap libur
            if (empty($sessions)) {
                $isClosed = true;
            }

            $normalized[$dayKey] = [
                'closed' => $isClosed,
                'sessions' => $isClosed ? [] : array_slice($sessions, 0, 2),
            ];
        }

        return $normalized;
    }
}
