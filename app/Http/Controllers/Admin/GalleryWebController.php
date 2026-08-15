<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\MuseumGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryWebController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $galleries = MuseumGallery::with('museum')
            ->when(! $isSuperAdmin, function ($query) use ($user) {
                $query->where('museum_id', $user->museum_id);
            })
            ->orderBy('museum_id')
            ->orderBy('order')
            ->get();

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki akses pemantauan data. Pengelolaan galeri dilakukan oleh Admin Museum.');
        }

        $museums = Museum::where('id', $user->museum_id)->get();

        return view('admin.galleries.create', compact('museums'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki akses pemantauan data. Pengelolaan galeri dilakukan oleh Admin Museum.');
        }

        $validated = $request->validate([
            'image' => 'required|image|max:2048',
            'caption' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $path = $request->file('image')->store('museum_galleries', 'public');

        MuseumGallery::create([
            'museum_id' => $user->museum_id,
            'image_path' => $path,
            'caption' => $validated['caption'] ?? null,
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()->route('galleries.index')->with('success', 'Foto galeri berhasil ditambahkan');
    }

    public function destroy(MuseumGallery $gallery)
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            abort(403, 'Super Admin hanya memiliki akses pemantauan data. Pengelolaan galeri dilakukan oleh Admin Museum.');
        }

        if ($gallery->museum_id !== $user->museum_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus foto museum ini.');
        }

        Storage::disk('public')->delete($gallery->image_path);
        $gallery->delete();

        return redirect()->route('galleries.index')->with('success', 'Foto galeri berhasil dihapus');
    }
}
