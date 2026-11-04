<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $adminMuseumId = $user->museum_id;

        $query = User::where('role', 'staff')
            ->when(! $isSuperAdmin, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
            })
            ->when($isSuperAdmin && $request->filled('museum_id'), function ($q) use ($request) {
                $q->where('museum_id', $request->museum_id);
            });

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $totalPetugas = (clone $query)->count();
        $petugas = $query->with('museum')->latest()->paginate(10)->withQueryString();
        $museums = $isSuperAdmin ? Museum::orderBy('name')->get() : collect();

        return view('admin.petugas.index', compact('petugas', 'totalPetugas', 'museums', 'isSuperAdmin'));
    }

    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $museums = $isSuperAdmin ? Museum::orderBy('name')->get() : collect();
        $museum = $user->museum;

        return view('admin.petugas.create', compact('museum', 'museums', 'isSuperAdmin'));
    }

    public function store(Request $request)
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];

        if ($isSuperAdmin) {
            $rules['museum_id'] = 'required|exists:museums,id';
        }

        $request->validate($rules, [
            'museum_id.required' => 'Museum penugasan wajib dipilih untuk akun Petugas.',
            'museum_id.exists' => 'Museum yang dipilih tidak valid.',
        ]);

        $museumId = $isSuperAdmin ? $request->museum_id : auth()->user()->museum_id;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'museum_id' => $museumId,
        ]);

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Akun petugas lapangan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $adminMuseumId = $user->museum_id;

        $petugas = User::where('role', 'staff')
            ->when(! $isSuperAdmin, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
            })
            ->findOrFail($id);

        return redirect()->route('admin.petugas.edit', $petugas->id);
    }

    public function edit($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $adminMuseumId = $user->museum_id;

        $petugas = User::where('role', 'staff')
            ->when(! $isSuperAdmin, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
            })
            ->findOrFail($id);

        $museums = $isSuperAdmin ? Museum::orderBy('name')->get() : collect();
        $museum = $isSuperAdmin ? $petugas->museum : $user->museum;

        return view('admin.petugas.edit', compact('petugas', 'museum', 'museums', 'isSuperAdmin'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $adminMuseumId = $user->museum_id;

        $petugas = User::where('role', 'staff')
            ->when(! $isSuperAdmin, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
            })
            ->findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$petugas->id,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        if ($isSuperAdmin) {
            $rules['museum_id'] = 'required|exists:museums,id';
        }

        $request->validate($rules, [
            'museum_id.required' => 'Museum penugasan wajib dipilih.',
            'museum_id.exists' => 'Museum yang dipilih tidak valid.',
        ]);

        $petugas->name = $request->name;
        $petugas->email = $request->email;

        if ($isSuperAdmin) {
            $petugas->museum_id = $request->museum_id;
        }

        if ($request->filled('password')) {
            $petugas->password = Hash::make($request->password);
        }

        $petugas->save();

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $adminMuseumId = $user->museum_id;

        $petugas = User::where('role', 'staff')
            ->when(! $isSuperAdmin, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
            })
            ->findOrFail($id);

        $petugas->delete();

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Akun petugas berhasil dihapus.');
    }
}
