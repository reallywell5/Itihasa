@extends('layouts.user')

@section('title', 'Edit Profil')

@section('content')

<section class="max-w-xl mx-auto py-4 sm:py-8">

    {{-- HEADER --}}
    <div class="text-center pb-6 mb-8 border-b border-neutral-200">
        <div class="w-18 h-18 sm:w-20 sm:h-20 mx-auto rounded-full bg-[#1A1A1A] text-white flex items-center justify-center font-serif text-2xl font-semibold border-2 border-[#B8860B] mb-4">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[#1A1A1A] tracking-tight">
            Perbarui Profil Akun
        </h1>

        <p class="text-[#5A5A5A] text-xs sm:text-sm mt-1.5">
            Ubah nama lengkap, alamat email, atau perbarui kata sandi akun Anda.
        </p>
    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8">

        <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- NAMA --}}
            <div>
                <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                    Nama Lengkap <span class="text-[#8C3B2B]">*</span>
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
                @error('name')
                    <p class="text-[#8C3B2B] text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                    Alamat Email <span class="text-[#8C3B2B]">*</span>
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       required
                       class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
                @error('email')
                    <p class="text-[#8C3B2B] text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD NOTICE --}}
            <div class="p-3.5 bg-[#F8F7F4] border border-neutral-200 rounded-sm">
                <p class="text-xs text-[#5A5A5A]">
                    Kosongkan kolom kata sandi di bawah apabila Anda tidak bermaksud mengganti kata sandi akun.
                </p>
            </div>

            {{-- PASSWORD GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                        Kata Sandi Baru
                    </label>
                    <input type="password"
                           name="password"
                           placeholder="Minimal 8 karakter"
                           class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
                    @error('password')
                        <p class="text-[#8C3B2B] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                        Konfirmasi Kata Sandi
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           placeholder="Ulangi kata sandi baru"
                           class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200">
                <a href="{{ route('user.profile') }}"
                   class="px-5 py-2.5 rounded-sm border border-neutral-300 text-[#5A5A5A] text-xs font-semibold uppercase tracking-wider hover:bg-[#F8F7F4] transition duration-200">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</section>

@endsection
