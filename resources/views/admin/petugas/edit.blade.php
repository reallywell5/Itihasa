@extends('layouts.app')

@section('title', 'Edit Petugas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-zinc-900">Edit Petugas</h1>
        <p class="text-sm text-zinc-500 mt-1">
            Ubah data akun petugas yang sudah terdaftar.
        </p>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl p-6 shadow-sm">
        <!-- 1. Menambahkan route update dan menyertakan ID petugas -->
        <form action="{{ route('admin.petugas.update', $petugas->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-2">
                    Nama Petugas
                </label>
                <!-- 2. Menggunakan data dinamis $petugas->name dan old() -->
                <input type="text"
                       name="name"
                       value="{{ old('name', $petugas->name) }}"
                       required
                       class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-2">
                    Email
                </label>
                <!-- 3. Menggunakan data dinamis $petugas->email dan old() -->
                <input type="email"
                       name="email"
                       value="{{ old('email', $petugas->email) }}"
                       required
                       class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-2">
                    Password Baru
                </label>
                <input type="password"
                       name="password"
                       placeholder="Kosongkan jika tidak ingin mengubah password"
                       class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
            </div>

            <!-- 4. Menambahkan Konfirmasi Password untuk berjaga-jaga jika admin ingin mereset password -->
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <input type="password"
                       name="password_confirmation"
                       placeholder="Ulangi password baru"
                       class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100">
                <a href="{{ route('admin.petugas.index') }}"
                   class="px-4 py-2 rounded-xl border border-zinc-200 text-sm font-medium text-zinc-600 hover:bg-zinc-100 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition">
                    Update Petugas
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
