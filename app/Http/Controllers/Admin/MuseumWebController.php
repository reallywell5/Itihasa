<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MuseumWebController extends Controller
{
    public function index()
    {
        $museums = Museum::latest()->paginate(10);
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
            'address'       => 'required|string',
            'description'   => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'opening_time'  => 'required',
            'closing_time'  => 'required',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
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