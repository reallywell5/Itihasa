<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $adminMuseumId = auth()->user()->museum_id;

        $query = User::where('role', 'staff')
            ->when($adminMuseumId, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
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

        return view('admin.petugas.index', compact('petugas', 'totalPetugas'));
    }

    public function create()
    {
        $museum = auth()->user()->museum;

        return view('admin.petugas.create', compact('museum'));
    }

    public function store(Request $request)
    {
        $adminMuseumId = auth()->user()->museum_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'museum_id' => $adminMuseumId,
        ]);

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Akun petugas lapangan berhasil ditambahkan dan otomatis terikat ke museum Anda.');
    }

    public function edit($id)
    {
        $adminMuseumId = auth()->user()->museum_id;

        $petugas = User::where('role', 'staff')
            ->when($adminMuseumId, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
            })
            ->findOrFail($id);

        $museum = auth()->user()->museum;

        return view('admin.petugas.edit', compact('petugas', 'museum'));
    }

    public function update(Request $request, $id)
    {
        $adminMuseumId = auth()->user()->museum_id;

        $petugas = User::where('role', 'staff')
            ->when($adminMuseumId, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
            })
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$petugas->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $petugas->name = $request->name;
        $petugas->email = $request->email;

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
        $adminMuseumId = auth()->user()->museum_id;

        $petugas = User::where('role', 'staff')
            ->when($adminMuseumId, function ($q) use ($adminMuseumId) {
                $q->where('museum_id', $adminMuseumId);
            })
            ->findOrFail($id);

        $petugas->delete();

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Akun petugas berhasil dihapus.');
    }
}
