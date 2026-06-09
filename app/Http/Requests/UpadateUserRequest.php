<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Mengizinkan request ini dijalankan.
     */
    public function authorize(): bool
    {
        return true; // Set ke true agar bisa digunakan
    }

    /**
     * Aturan validasi data saat mengedit profil user.
     */
    public function rules(): array
    {
        // Mengambil ID user dari parameter URL /admin/users/{user}
        $userId = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],

            // Wajib diisi, format email, unik di tabel users KECUALI untuk ID user ini sendiri
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],

            // Password bersifat opsional saat edit (nullable). Jika diisi, minimal 8 karakter dan wajib cocok.
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            'role' => ['required', 'string', 'in:admin,user'],
        ];
    }

    /**
     * Pesan error kustom dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password baru minimal harus terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Pilihan role tidak valid.',
        ];
    }
}
