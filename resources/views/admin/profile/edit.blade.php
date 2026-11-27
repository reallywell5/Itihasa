@extends('layouts.app')

@section('title', 'Profil dan Pengaturan Akun')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">
                Pengaturan Profil Saya
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-0.5">
                Kelola identitas akun, alamat email, dan pembaruan kata sandi keamanan Anda.
            </p>
        </div>

        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $user->isSuperAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
            {{ $user->isSuperAdmin() ? 'Super Admin' : 'Admin Museum' }}
        </span>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-xs font-semibold space-y-1">
            <p class="font-bold">Terjadi kesalahan pada data yang dimasukkan:</p>
            <ul class="list-disc list-inside space-y-0.5 font-normal">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- USER SUMMARY CARD --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col sm:flex-row items-center sm:items-start gap-5">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl {{ $user->isSuperAdmin() ? 'bg-purple-600' : 'bg-blue-600' }} text-white flex items-center justify-center font-black text-2xl sm:text-3xl shadow-md shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        <div class="space-y-1.5 text-center sm:text-left flex-1 min-w-0">
            <h2 class="text-lg font-bold text-slate-800 truncate">
                {{ $user->name }}
            </h2>
            <p class="text-xs text-slate-500 font-mono">
                {{ $user->email }}
            </p>

            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1 text-[11px]">
                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 font-semibold">
                    ID Akun: USR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                </span>

                @if($user->isSuperAdmin())
                    <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold">
                        Hak Akses: Pengawasan Global Seluruh Museum
                    </span>
                @elseif($user->museum)
                    <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold">
                        Penugasan: {{ $user->museum->name }}
                    </span>
                @endif

                <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold">
                    Terdaftar sejak {{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}
                </span>
            </div>
        </div>
    </div>

    {{-- EDIT PROFILE FORM --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 sm:p-7">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">
                    Informasi Identitas
                </h3>
                <p class="text-[11px] text-slate-400">
                    Perbarui nama lengkap dan alamat email aktif yang digunakan untuk masuk ke sistem.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alamat Email Login <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none font-mono">
                </div>
            </div>

            <div class="border-b border-slate-100 pt-3 pb-3">
                <h3 class="text-sm font-bold text-slate-800">
                    Keamanan dan Kata Sandi
                </h3>
                <p class="text-[11px] text-slate-400">
                    Biarkan kolom di bawah ini kosong jika Anda tidak berencana mengubah kata sandi lama.
                </p>
            </div>

            <div>
                <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Kata Sandi Saat Ini (Diperlukan jika ingin mengubah kata sandi)
                </label>
                <input type="password"
                       id="current_password"
                       name="current_password"
                       placeholder="Masukkan kata sandi lama Anda"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kata Sandi Baru
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Minimal 8 karakter"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Konfirmasi Kata Sandi Baru
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Ulangi kata sandi baru"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                    Kembali ke Dashboard
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl {{ $user->isSuperAdmin() ? 'bg-purple-600 hover:bg-purple-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-bold text-xs shadow-sm transition">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

