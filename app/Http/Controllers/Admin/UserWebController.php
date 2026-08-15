<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use App\Models\User;
use Illuminate\Http\Request;

class UserWebController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalSuperAdmin = User::where('role', 'super_admin')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalStaff = User::where('role', 'staff')->count();
        $totalVisitor = User::where('role', 'visitor')->count();

        $users = User::with('museum')
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
            ->withQueryString();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalSuperAdmin',
            'totalAdmin',
            'totalStaff',
            'totalVisitor'
        ));
    }

    public function create()
    {
        $museums = Museum::all();

        return view('admin.users.create', compact('museums'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,staff',
            'museum_id' => 'nullable|exists:museums,id',
        ]);

        if (in_array($data['role'], ['admin', 'staff']) && empty($data['museum_id'])) {
            return back()
                ->withErrors(['museum_id' => 'Museum penugasan wajib dipilih untuk akun Admin Museum dan Petugas.'])
                ->withInput();
        }

        if ($data['role'] === 'super_admin') {
            $data['museum_id'] = null;
        }

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan');
    }

    public function show(User $user)
    {
        $user->load(['transactions', 'museum']);

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        $museums = Museum::all();

        return view('admin.users.edit', [
            'user' => $user,
            'museums' => $museums,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,staff,visitor',
            'museum_id' => 'nullable|exists:museums,id',
        ]);

        // Cegah super admin mengubah role dirinya sendiri jadi bukan super_admin
        if ($user->id === auth()->id() && $data['role'] !== 'super_admin') {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak bisa mengubah role akun Anda sendiri.');
        }

        if (in_array($data['role'], ['admin', 'staff']) && empty($data['museum_id'])) {
            return back()
                ->withErrors(['museum_id' => 'Museum wajib dipilih untuk akun Admin Museum dan Petugas.'])
                ->withInput();
        }

        if (in_array($data['role'], ['super_admin', 'visitor'])) {
            $data['museum_id'] = null;
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
        // Cegah super admin menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Cegah penghapusan super_admin terakhir yang tersisa
        if ($user->role === 'super_admin') {
            $totalSuperAdmin = User::where('role', 'super_admin')->count();

            if ($totalSuperAdmin <= 1) {
                return redirect()
                    ->route('users.index')
                    ->with('error', 'Tidak bisa menghapus Super Admin terakhir. Sistem wajib punya minimal 1 Super Admin.');
            }
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna berhasil dihapus');
    }
}
