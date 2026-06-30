@extends('layouts.user')

@section('title', 'Edit Profil')

@section('content')

<section class="max-w-3xl mx-auto px-6 lg:px-8 py-14">

    {{-- HEADER --}}
    <div class="text-center mb-10">

        <div class="w-24 h-24 mx-auto rounded-full bg-[#102A43] text-white flex items-center justify-center text-3xl font-bold shadow-lg mb-5">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        <h1 class="text-4xl font-bold text-[#102A43]">
            Edit Profil
        </h1>

        <p class="text-slate-500 mt-3">
            Perbarui informasi akun kamu di bawah ini.
        </p>

    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-[32px] border border-[#EADBC8] shadow-sm p-8">

        <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-6">

            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-semibold text-[#102A43] mb-2">
                    Nama Lengkap
                </label>

                <input type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-5 py-4 rounded-2xl border border-[#EADBC8] focus:outline-none focus:ring-2 focus:ring-[#B88A44]">

                @error('name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-[#102A43] mb-2">
                    Email
                </label>

                <input type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-5 py-4 rounded-2xl border border-[#EADBC8] focus:outline-none focus:ring-2 focus:ring-[#B88A44]">

                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD BARU --}}
            <div>
                <label class="block text-sm font-semibold text-[#102A43] mb-2">
                    Password Baru (Opsional)
                </label>

                <input type="password"
                    name="password"
                    placeholder="Kosongkan jika tidak ingin mengubah password"
                    class="w-full px-5 py-4 rounded-2xl border border-[#EADBC8] focus:outline-none focus:ring-2 focus:ring-[#B88A44]">

                @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div>
                <label class="block text-sm font-semibold text-[#102A43] mb-2">
                    Konfirmasi Password Baru
                </label>

                <input type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password baru"
                    class="w-full px-5 py-4 rounded-2xl border border-[#EADBC8] focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
            </div>

            {{-- BUTTONS --}}
            <div class="grid grid-cols-2 gap-4 pt-4">

                <a href="{{ route('user.profile') }}"
                   class="py-4 rounded-2xl border border-[#B88A44] text-[#B88A44] text-center font-semibold hover:bg-[#F6F1E8] transition">
                    Batal
                </a>

                <button type="submit"
                    class="py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</section>

@endsection
