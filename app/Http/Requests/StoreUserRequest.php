<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Mengizinkan request ini dijalankan.
     */
    public function authorize(): bool
    {
        return true; // Wajib di-set ke true agar bisa digunakan
    }

    /**
     * Aturan validasi data saat membuat user baru.
     */
    public function rules(): array
    {
        return [
            // Nama wajib diisi, berupa teks, maksimal 255 karakter
            'name' => ['required', 'string', 'max:255'],

            // Email wajib diisi, format valid, unik di tabel users, maksimal 255 karakter
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],

            // Password wajib diisi, minimal 8 karakter, dan wajib dicocokkan dengan input 'password_confirmation'
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Role wajib diisi dan harus bernilai 'admin' atau 'user'
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
            'email.unique' => 'Alamat email ini sudah terdaftar di sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Pilihan role tidak valid.',
        ];
    }
}
