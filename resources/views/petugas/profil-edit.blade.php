@extends('layouts.petugas')

@section('title', 'Edit Profil Petugas')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-3xl border border-blue-100 shadow-sm overflow-hidden">

        {{-- HEADER --}}
        <div class="px-8 pt-8 pb-6 border-b border-blue-50">
            <div class="flex items-center justify-between">

                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Update Profil Petugas
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        Perbarui informasi data diri dan akun Anda.
                    </p>
                </div>

                <a href="{{ route('petugas.profil') }}"
                class="px-4 py-2 rounded-xl border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 transition">
                    Kembali
                </a>

            </div>
        </div>

        {{-- FORM --}}
        <form action="{{ route('petugas.profil.update') }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            {{-- NAME --}}
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                    Nama Lengkap
                </label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $petugas->name) }}"
                       class="w-full px-5 py-3 rounded-2xl border border-zinc-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition @error('name') border-red-300 @enderror"
                       placeholder="Masukkan nama lengkap">

                @error('name')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">
                    Email
                </label>

                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $petugas->email) }}"
                       class="w-full px-5 py-3 rounded-2xl border border-zinc-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition @error('email') border-red-300 @enderror"
                       placeholder="Masukkan email">

                @error('email')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">
                    Password Baru <span class="text-slate-400 font-normal">(kosongkan jika tidak ingin mengubah)</span>
                </label>

                <input type="password"
                       id="password"
                       name="password"
                       class="w-full px-5 py-3 rounded-2xl border border-zinc-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition @error('password') border-red-300 @enderror"
                       placeholder="Masukkan password baru">

                @error('password')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD CONFIRMATION --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">
                    Konfirmasi Password
                </label>

                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="w-full px-5 py-3 rounded-2xl border border-zinc-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"
                       placeholder="Konfirmasi password baru">
            </div>

            {{-- BUTTONS --}}
            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                        class="px-6 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-sm">
                    Simpan Perubahan
                </button>

                <a href="{{ route('petugas.profil') }}"
                   class="px-6 py-3 rounded-2xl border border-zinc-200 text-zinc-700 font-semibold hover:bg-zinc-50 transition">
                    Batal
                </a>
            </div>

        </form>

    </div>
@endsection
