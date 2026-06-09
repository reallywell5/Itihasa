<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\Request;

class MuseumWebController extends Controller
{
    public function index()
    {
        $museums = Museum::latest()->paginate(10);

        return view('admin.museums.index', [
            'museums' => $museums,
        ]);
    }

    public function create()
    {
        return view('admin.museums.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('museums', 'public');
        }

        Museum::create($validated);

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil ditambahkan');
    }

    public function edit(Museum $museum)
    {
        return view('admin.museums.edit', [
            'museum' => $museum,
        ]);
    }

    public function update(Request $request, Museum $museum)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('museums', 'public');
        }

        $museum->update($validated);

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil diperbarui');
    }

    public function destroy(Museum $museum)
    {
        $museum->delete();

        return redirect()
            ->route('museums.index')
            ->with('success', 'Museum berhasil dihapus');
    }
}
