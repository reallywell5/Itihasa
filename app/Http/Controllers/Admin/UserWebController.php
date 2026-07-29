<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserWebController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // supaya search/filter tetap ada saat pindah halaman

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,staff,visitor',
        ]);

        // PENTING: password wajib di-hash sebelum disimpan.
        // Jika model User TIDAK punya cast 'password' => 'hashed',
        // tanpa baris ini password akan tersimpan dalam bentuk plain text.
        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan');
    }

    public function show(User $user)
    {
        $user->load('transactions');

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:admin,staff,visitor',
        ]);

        // Cegah admin mengubah role dirinya sendiri jadi bukan admin
        // (supaya tidak tidak sengaja menendang diri sendiri keluar dari akses admin)
        if ($user->id === auth()->id() && $data['role'] !== 'admin') {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak bisa mengubah role akun Anda sendiri.');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        // Cegah admin menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Cegah penghapusan admin terakhir yang tersisa
        if ($user->role === 'admin') {
            $totalAdmin = User::where('role', 'admin')->count();

            if ($totalAdmin <= 1) {
                return redirect()
                    ->route('users.index')
                    ->with('error', 'Tidak bisa menghapus admin terakhir. Sistem wajib punya minimal 1 admin.');
            }
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna berhasil dihapus');
    }
}
