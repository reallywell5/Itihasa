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
        $petugas = User::where('role', 'staff')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('admin.petugas.create');
    }

    public function edit($id)
    {
        $petugas = User::where('role', 'staff')->findOrFail($id);

        return view('admin.petugas.edit', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'staff',
        ]);

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Petugas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $petugas = User::where('role', 'staff')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $petugas->id,
            'password' => 'nullable|min:8|confirmed',
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
        // Pastikan hanya bisa hapus user dengan role 'staff' lewat controller ini,
        // supaya tidak bisa dipakai untuk menghapus admin/visitor secara tidak sengaja
        $petugas = User::where('role', 'staff')->findOrFail($id);

        $petugas->delete();

        return redirect()
            ->route('admin.petugas.index')
            ->with('success', 'Akun petugas berhasil dihapus');
    }
}
