<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil dan pengaturan akun petugas (sama seperti admin & super admin).
     */
    public function index(): View
    {
        /** @var User $petugas */
        $petugas = Auth::user();

        return view('petugas.profil', [
            'user' => $petugas,
            'petugas' => $petugas,
        ]);
    }

    /**
     * Mengarahkan akses edit ke halaman profil terpadu.
     */
    public function edit(): RedirectResponse
    {
        return redirect()->route('petugas.profil');
    }

    /**
     * Memperbarui profil akun petugas (nama, email, kata sandi).
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        if (! empty($validated['password'])) {
            if (! empty($validated['current_password']) && ! Hash::check($validated['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah.'])
                    ->withInput();
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()
            ->route('petugas.profil')
            ->with('success', 'Profil dan informasi akun Anda berhasil diperbarui.');
    }
}
