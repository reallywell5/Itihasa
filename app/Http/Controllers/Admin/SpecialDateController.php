<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\MuseumSpecialDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialDateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $museum = null;
        if (! $isSuperAdmin) {
            $museum = Museum::find($user->museum_id);
            if (! $museum) {
                abort(403, 'Akun Anda belum terhubung dengan museum manapun.');
            }
        }

        $specialDates = MuseumSpecialDate::with('museum')
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->where('museum_id', $user->museum_id);
            })
            ->orderBy('date', 'asc')
            ->paginate(15);

        $museums = $isSuperAdmin ? Museum::orderBy('name')->get() : collect();

        return view('admin.special-dates.index', compact('specialDates', 'museum', 'museums', 'isSuperAdmin'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $museumId = $isSuperAdmin ? $request->museum_id : $user->museum_id;

        if (! $museumId) {
            return back()->withErrors(['museum_id' => 'Museum wajib dipilih.'])->withInput();
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'is_closed' => 'nullable|boolean',
            'quota_override' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ], [
            'date.required' => 'Tanggal wajib diisi.',
            'date.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
        ]);

        $isClosed = $request->boolean('is_closed', true);

        MuseumSpecialDate::updateOrCreate(
            [
                'museum_id' => $museumId,
                'date' => $request->date,
            ],
            [
                'is_closed' => $isClosed,
                'quota_override' => $request->quota_override,
                'notes' => $request->notes,
            ]
        );

        return redirect()
            ->route('admin.special-dates.index')
            ->with('success', 'Jadwal khusus / tanggal tutup berhasil disimpan.');
    }

    public function destroy(MuseumSpecialDate $specialDate)
    {
        $user = Auth::user();

        if (! $user->isSuperAdmin() && $specialDate->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus jadwal museum ini.');
        }

        $specialDate->delete();

        return redirect()
            ->route('admin.special-dates.index')
            ->with('success', 'Jadwal khusus berhasil dihapus.');
    }
}
