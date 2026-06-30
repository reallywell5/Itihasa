@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">
            Tambah Pengguna Baru
        </h1>

        <p class="text-sm text-zinc-500 mt-1">
            Tambahkan akun pengguna baru ke dalam sistem Itihasa.
        </p>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="p-6 space-y-5">

                {{-- ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- NAME --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-zinc-700 mb-2">
                        Nama Lengkap
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        required
                        class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
                    >
                </div>

                {{-- EMAIL --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-zinc-700 mb-2">
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        required
                        class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
                    >
                </div>

                {{-- ROLE --}}
                <div>
                    <label for="role" class="block text-sm font-semibold text-zinc-700 mb-2">
                        Role
                    </label>

                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
                    >
                        <option value="">Pilih Role</option>

                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>
                            Petugas
                        </option>

                        <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>
                            Visitor
                        </option>
                    </select>
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-zinc-700 mb-2">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                        class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
                    >
                </div>

                {{-- PASSWORD CONFIRMATION --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-zinc-700 mb-2">
                        Konfirmasi Password
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        required
                        class="w-full px-4 py-3 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
                    >
                </div>

            </div>

            {{-- ACTION --}}
            <div class="px-6 py-5 bg-zinc-50 border-t border-zinc-100 flex items-center justify-end gap-3">

                <a href="{{ route('users.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-zinc-200 text-zinc-700 text-sm font-semibold hover:bg-zinc-100 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition">
                    Simpan Pengguna
                </button>

            </div>

        </form>
    </div>

</div>
@endsection
