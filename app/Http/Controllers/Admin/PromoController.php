<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PromoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $promos = Promo::with('museum')
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('museum_id', $user->museum_id)
                        ->orWhereNull('museum_id');
                });
            })
            ->latest()
            ->paginate(15);

        $museums = $isSuperAdmin ? Museum::orderBy('name')->get() : collect();

        return view('admin.promos.index', compact('promos', 'museums', 'isSuperAdmin'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $museumId = $isSuperAdmin ? $request->museum_id : $user->museum_id;

        $request->validate([
            'code' => 'required|string|max:20',
            'title' => 'required|string|max:100',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|integer|min:1',
            'min_total_price' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:1',
            'quota' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'code.required' => 'Kode promo wajib diisi.',
            'title.required' => 'Judul promo wajib diisi.',
            'discount_value.required' => 'Nilai diskon wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
        ]);

        $code = Str::upper(preg_replace('/[^A-Za-z0-9]/', '', $request->code));

        // Check uniqueness per museum
        $exists = Promo::where('code', $code)
            ->where(function ($q) use ($museumId) {
                if ($museumId) {
                    $q->where('museum_id', $museumId);
                } else {
                    $q->whereNull('museum_id');
                }
            })->exists();

        if ($exists) {
            return back()->withErrors(['code' => "Kode promo \"{$code}\" sudah digunakan."])->withInput();
        }

        Promo::create([
            'museum_id' => $museumId,
            'code' => $code,
            'title' => $request->title,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_total_price' => $request->input('min_total_price', 0) ?? 0,
            'max_discount' => $request->max_discount,
            'quota' => $request->quota,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.promos.index')
            ->with('success', "Kode promo {$code} berhasil dibuat.");
    }

    public function toggle(Promo $promo)
    {
        $user = Auth::user();

        if (! $user->isSuperAdmin() && $promo->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah promo ini.');
        }

        $promo->update(['is_active' => ! $promo->is_active]);

        $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.promos.index')
            ->with('success', "Promo {$promo->code} berhasil {$status}.");
    }

    public function destroy(Promo $promo)
    {
        $user = Auth::user();

        if (! $user->isSuperAdmin() && $promo->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus promo ini.');
        }

        $promo->delete();

        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Kode promo berhasil dihapus.');
    }
}
