<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MuseumProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        if ($isSuperAdmin) {
            $museumId = $request->input('museum_id', Museum::first()?->id);
            $museum = Museum::find($museumId);
            $allMuseums = Museum::orderBy('name')->get();
        } else {
            $museum = Museum::find($user->museum_id);
            $allMuseums = collect();
        }

        if (! $museum) {
            abort(404, 'Data museum tidak ditemukan atau belum terhubung dengan akun Anda.');
        }

        return view('admin.museum.profile', compact('museum', 'allMuseums', 'isSuperAdmin'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $museumId = $isSuperAdmin ? $request->input('museum_id') : $user->museum_id;
        $museum = Museum::findOrFail($museumId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:museum,seni,budaya,alam,religius',
            'address' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:100',
            'operational_hours' => 'nullable|array',
        ], [
            'name.required' => 'Nama museum wajib diisi.',
            'category.required' => 'Kategori museum wajib dipilih.',
            'address.required' => 'Alamat museum wajib diisi.',
            'description.required' => 'Deskripsi museum wajib diisi.',
            'image.max' => 'Ukuran foto banner maksimal 3MB.',
        ]);

        if ($request->hasFile('image')) {
            if ($museum->image && Storage::disk('public')->exists($museum->image)) {
                Storage::disk('public')->delete($museum->image);
            }

            $path = $request->file('image')->store('museums', 'public');
            $validated['image'] = $path;
        }

        // Normalize facilities (filter null or empty)
        $facilities = array_values(array_filter($request->input('facilities', []), fn ($f) => ! empty(trim($f))));
        $validated['facilities'] = $facilities;

        // Normalize operational hours
        if ($request->has('operational_hours')) {
            $schedule = [];
            $dayKeys = Museum::dayKeys();

            foreach ($dayKeys as $dayKey) {
                $raw = $request->input("operational_hours.{$dayKey}", []);
                $isClosed = ! empty($raw['closed']);

                $sessions = [];
                if (! $isClosed && ! empty($raw['sessions']) && is_array($raw['sessions'])) {
                    foreach ($raw['sessions'] as $session) {
                        $open = trim($session['open'] ?? '');
                        $close = trim($session['close'] ?? '');

                        if ($open !== '' && $close !== '') {
                            $sessions[] = ['open' => $open, 'close' => $close];
                        }
                    }
                }

                $schedule[$dayKey] = [
                    'closed' => $isClosed || empty($sessions),
                    'sessions' => $sessions,
                ];
            }

            $validated['operational_hours'] = $schedule;
        }

        $museum->update($validated);

        return redirect()
            ->route('admin.museum.profile.edit', $isSuperAdmin ? ['museum_id' => $museum->id] : [])
            ->with('success', 'Profil dan fasilitas museum berhasil diperbarui.');
    }
}
