@extends('layouts.app')

@section('title', 'Edit Profil Pengguna')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-8">

    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900 transition">
            ← Kembali ke Daftar
        </a>

        <h1 class="text-2xl font-bold text-zinc-900 mt-4">
            Edit Profil Pengguna
        </h1>

        <p class="text-sm text-zinc-500 mt-1">
            Perbarui data akun pengguna.
        </p>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-5">

                <div>
                    <label for="name" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                        Nama Lengkap
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                        Alamat Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                        Hak Akses / Role
                    </label>

                    <select
                        id="role"
                        name="role"
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                            User
                        </option>

                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>

                    @error('role')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl">
                    <p class="text-xs text-zinc-500 leading-relaxed">
                        Kosongkan password jika tidak ingin mengganti password lama.
                    </p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                        Password Baru
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                        Konfirmasi Password Baru
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

            </div>

            <div class="px-6 py-5 bg-zinc-50 border-t border-zinc-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-zinc-200 text-zinc-700 text-sm font-semibold hover:bg-zinc-100 transition">
                    Kembali
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
