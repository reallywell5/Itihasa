@extends('layouts.app')

@section('title', 'Tambah Petugas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-zinc-900">
            Tambah Petugas
        </h1>

        <p class="text-sm text-zinc-500 mt-1">
            Tambahkan akun petugas baru untuk validasi tiket museum.
        </p>
    </div>

    {{-- Form --}}
    <div class="bg-white border border-zinc-200 rounded-2xl p-6 shadow-sm">

        <!-- Penulisan Tag Form yang Benar -->
        <form action="{{ route('admin.petugas.store') }}" method="POST">
            @csrf

            <!-- Pembungkus elemen input agar berjarak rapi -->
            <div class="space-y-5 mb-6">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Nama Petugas
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Masukkan nama petugas"
                           required
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Masukkan email"
                           required
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Password
                    </label>

                    <input type="password"
                           name="password"
                           placeholder="Masukkan password"
                           required
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
                </div>

                {{-- Konfirmasi Password (Wajib untuk validasi 'confirmed' di controller) --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Konfirmasi Password
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           placeholder="Ulangi password"
                           required
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100">

                <a href="{{ route('admin.petugas.index') }}"
                   class="px-4 py-2 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-600 hover:bg-zinc-100 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition">
                    Simpan Petugas
                </button>

            </div>

        </form>
    </div>

</div>
@endsection
