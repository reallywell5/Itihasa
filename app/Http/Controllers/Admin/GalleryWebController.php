<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\MuseumGallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryWebController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $query = MuseumGallery::with('museum')
            ->when(! $isSuperAdmin, function ($q) use ($user) {
                $q->where('museum_id', $user->museum_id);
            })
            ->when($isSuperAdmin && $request->filled('museum_id'), function ($q) use ($request) {
                $q->where('museum_id', $request->museum_id);
            })
            ->orderBy('museum_id')
            ->orderBy('order');

        $galleries = $query->paginate(12)->withQueryString();
        $museums = $isSuperAdmin ? Museum::orderBy('name')->get() : collect();

        return view('admin.galleries.index', compact('galleries', 'museums', 'isSuperAdmin'));
    }

    public function create(): View|RedirectResponse
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        if ($isSuperAdmin) {
            $museums = Museum::orderBy('name')->get();
            if ($museums->isEmpty()) {
                return redirect()->route('galleries.index')
                    ->with('error', 'Belum ada museum yang terdaftar. Tambahkan museum terlebih dahulu.');
            }
        } else {
            $museum = $user->museum_id ? Museum::find($user->museum_id) : null;
            if (! $museum) {
                return redirect()->route('galleries.index')
                    ->with('error', 'Akun Admin Anda belum terhubung dengan data museum. Silakan hubungi Super Admin.');
            }
            $museums = collect([$museum]);
        }

        return view('admin.galleries.create', compact('museums', 'isSuperAdmin'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $rules = [
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'caption' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ];

        if ($isSuperAdmin) {
            $rules['museum_id'] = 'required|exists:museums,id';
        }

        $validated = $request->validate($rules, [
            'museum_id.required' => 'Museum wajib dipilih untuk foto galeri.',
            'museum_id.exists' => 'Museum yang dipilih tidak valid.',
            'image.required' => 'Berkas foto wajib diunggah.',
            'image.image' => 'Berkas yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran foto maksimal 3MB.',
        ]);

        $museumId = $isSuperAdmin ? (int) $validated['museum_id'] : $user->museum_id;

        if (! $museumId) {
            return redirect()->route('galleries.index')
                ->with('error', 'Akun Anda belum terhubung dengan museum manapun.');
        }

        $path = $request->file('image')->store('museum_galleries', 'public');

        MuseumGallery::create([
            'museum_id' => $museumId,
            'image_path' => $path,
            'caption' => $validated['caption'] ?? null,
            'order' => (int) ($validated['order'] ?? 0),
        ]);

        return redirect()->route('galleries.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function destroy(MuseumGallery $gallery): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->isSuperAdmin() && $gallery->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus foto museum ini.');
        }

        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('galleries.index')->with('success', 'Foto galeri berhasil dihapus.');
    }
}
