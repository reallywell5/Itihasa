@extends('layouts.user')

@section('title', 'Edit Profil')

@section('content')

<section class="max-w-xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="text-center mb-6">
        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-[#102A43] text-white flex items-center justify-center text-2xl font-bold shadow mb-3">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        <h1 class="text-xl sm:text-2xl font-bold text-[#102A43]">
            Perbarui Profil Akun
        </h1>

        <p class="text-slate-500 text-xs mt-1">
            Ubah nama lengkap, email, atau perbarui kata sandi akun kamu.
        </p>
    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-2xl border border-[#EADBC8] shadow-sm p-4 sm:p-6">

        <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- NAMA --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#EADBC8] text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#EADBC8] text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD NOTICE --}}
            <div class="p-3 bg-amber-50/60 border border-amber-100 rounded-xl">
                <p class="text-[11px] text-amber-800">
                    💡 <em>Kosongkan kolom kata sandi di bawah jika tidak ingin mengubah password akun.</em>
                </p>
            </div>

            {{-- PASSWORD GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Password Baru
                    </label>
                    <input type="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#EADBC8] text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Konfirmasi Password
                    </label>
                    <input type="password"
                        name="password_confirmation"
                        placeholder="Ulangi password baru"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#EADBC8] text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <a href="{{ route('user.profile') }}"
                   class="px-4 py-2 rounded-xl border border-[#EADBC8] text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-2 rounded-xl bg-[#102A43] text-white font-bold text-xs hover:bg-[#0c2238] transition shadow">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</section>

@endsection
