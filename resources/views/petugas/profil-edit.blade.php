@extends('layouts.petugas')

@section('title', 'Edit Profil Petugas')

@section('content')
<div class="max-w-2xl mx-auto space-y-4 sm:space-y-5">

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- HEADER --}}
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <a href="{{ route('petugas.profil') }}" class="text-xs text-emerald-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                    ← Kembali ke Profil
                </a>
                <h1 class="text-lg sm:text-xl font-bold text-slate-800">
                    Perbarui Profil Petugas
                </h1>
                <p class="text-xs text-slate-400">
                    Ubah nama, email akun, atau perbarui kata sandi.
                </p>
            </div>

            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                Staff Loket
            </span>
        </div>

        {{-- FORM --}}
        <form action="{{ route('petugas.profil.update') }}" method="POST" class="p-4 sm:p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- NAME --}}
            <div>
                <label for="name" class="block text-xs font-bold text-slate-600 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $petugas->name) }}"
                       required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs text-slate-800 transition @error('name') border-red-300 @enderror"
                       placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label for="email" class="block text-xs font-bold text-slate-600 mb-1.5">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $petugas->email) }}"
                       required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs text-slate-800 transition @error('email') border-red-300 @enderror"
                       placeholder="Masukkan email">
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD NOTICE --}}
            <div class="p-3 bg-amber-50/60 border border-amber-100 rounded-xl">
                <p class="text-[11px] text-amber-800">
                    💡 <em>Kosongkan kolom kata sandi di bawah jika tidak ingin mengubah password akun.</em>
                </p>
            </div>

            {{-- PASSWORD GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Password Baru
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs text-slate-800 transition @error('password') border-red-300 @enderror"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs text-slate-800 transition"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <a href="{{ route('petugas.profil') }}"
                   class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow hover:bg-emerald-700 transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
