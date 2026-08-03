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
        $museums = Museum::query()
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
        return view('admin.museums.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'required|in:museum,seni,budaya,alam,religius',
            'address'       => 'required|string',
            'description'   => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'opening_time'  => 'required',
            'closing_time'  => 'required',
        ]);

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

        Museum::create($validated);

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil ditambahkan');
    }

    public function edit(Museum $museum)
    {
        return view('admin.museums.edit', compact('museum'));
    }

    public function update(Request $request, Museum $museum)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'required|in:museum,seni,budaya,alam,religius',
            'address'       => 'required|string',
            'description'   => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'opening_time'  => 'required',
            'closing_time'  => 'required',
        ]);

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

        $museum->update($validated);

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil diperbarui');
    }

    public function destroy(Museum $museum)
    {
        if ($museum->image && Storage::disk('public')->exists($museum->image)) {
            Storage::disk('public')->delete($museum->image);
        }

        $museum->delete();

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil dihapus');
    }
}
