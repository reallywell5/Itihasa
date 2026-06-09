<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request; // Menggunakan Request standar bawaan Laravel

class UserWebController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    // Memperbaiki fungsi simpan data baru tanpa StoreUserRequest
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,petugas,user',
        ]);

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

    // Memperbaiki fungsi update yang error tadi tanpa UpdateUserRequest
    public function update(Request $request, User $user)
    {
        // Validasi data langsung di sini
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8', // Boleh kosong kalau tidak mau ganti password
            'role'     => 'required|in:admin,petugas,user',
        ]);

        // Jika password di form dikosongkan, hapus dari array agar tidak ikut terupdate jadi kosong
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            // Jika diisi, otomatis enkripsi password baru (opsional, tergantung setup Model Anda)
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna berhasil dihapus');
    }
}
