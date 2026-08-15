@extends('layouts.app')

@section('title', 'Tambah Petugas Loket')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.petugas.index') }}" class="text-xs text-blue-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Daftar Petugas
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Tambah Petugas Gate Baru
            </h1>
            <p class="text-xs text-slate-400">
                Daftarkan akun staf yang bertugas memindai dan memvalidasi tiket masuk di loket/gate.
            </p>
        </div>

        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
            Admin • {{ Auth::user()?->museum?->name ?? 'Museum' }}
        </span>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('admin.petugas.store') }}" method="POST">
            @csrf

            <div class="p-6 space-y-4">

                {{-- ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- INFORMASI PENUGASAN OTOMATIS --}}
                <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        🏛
                    </div>
                    <div>
                        <p class="text-xs font-bold text-blue-900">
                            Penugasan Otomatis: {{ Auth::user()?->museum?->name ?? 'Museum Anda' }}
                        </p>
                        <p class="text-[11px] text-blue-700 mt-0.5">
                            Akun petugas yang dibuat di sini secara otomatis terdaftar khusus untuk memvalidasi tiket masuk di <strong>{{ Auth::user()?->museum?->name ?? 'museum Anda' }}</strong>.
                        </p>
                    </div>
                </div>

                {{-- NAME --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Nama Lengkap Petugas <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Rian Hidayat"
                        required
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                </div>

                {{-- EMAIL --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Alamat Email Login <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Contoh: petugas.gate@itihasa.com"
                        required
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 font-mono"
                    >
                </div>

                {{-- PASSWORD --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-600 mb-1.5">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-600 mb-1.5">
                            Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi kata sandi"
                            required
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                    </div>
                </div>

            </div>

            {{-- ACTION --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <a href="{{ route('admin.petugas.index') }}"
                   class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold shadow hover:bg-blue-700 transition">
                    Simpan Akun Petugas
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
