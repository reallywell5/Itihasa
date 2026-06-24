@extends('layouts.app')

@section('title', 'Tambah Museum')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8 space-y-8">

    <div class="flex flex-col gap-1 pb-2">
        <div class="flex items-center gap-2 text-xs font-semibold tracking-wider text-indigo-600 uppercase">
            <a href="{{ route('museums.index') }}" class="hover:text-indigo-700 transition">Museum</a>
            <span class="text-zinc-300">/</span>
            <span class="text-zinc-500">Tambah Data</span>
        </div>

        <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 mt-1">
            Tambah Museum
        </h1>

        <p class="text-sm text-zinc-500">
            Tambahkan data museum baru beserta informasi lokasi, foto, dan jam operasional.
        </p>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('museums.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 sm:p-8 space-y-6">
                {{-- Tampilkan semua error --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="name" class="block text-sm font-semibold text-zinc-700 mb-1.5">Nama Museum</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama museum"
                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-900"
                    >
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-zinc-700 mb-1.5">Alamat</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        placeholder="Masukkan alamat museum"
                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-900"
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-zinc-700 mb-1.5">Deskripsi</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Masukkan deskripsi museum"
                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-900"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-semibold text-zinc-700 mb-2">Foto Museum</label>
                    <input
                        id="image"
                        type="file"
                        name="image"
                        class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-700 bg-white focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-900"
                    >
                    <p class="text-xs text-zinc-400 mt-1">Format: PNG, JPG, JPEG. Maksimal 2MB.</p>
                    @error('image')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="opening_time" class="block text-sm font-semibold text-zinc-700 mb-1.5">Jam Buka</label>
                        <input
                            id="opening_time"
                            type="time"
                            name="opening_time"
                            value="{{ old('opening_time') }}"
                            class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-900"
                        >
                        @error('opening_time')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="closing_time" class="block text-sm font-semibold text-zinc-700 mb-1.5">Jam Tutup</label>
                        <input
                            id="closing_time"
                            type="time"
                            name="closing_time"
                            value="{{ old('closing_time') }}"
                            class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-900"
                        >
                        @error('closing_time')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="px-6 sm:px-8 py-5 bg-zinc-50 border-t border-zinc-100 flex items-center justify-end gap-3">
                <a href="{{ route('museums.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-zinc-200 text-zinc-700 text-sm font-semibold hover:bg-zinc-100 transition">
                    Kembali
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition">
                    Simpan Museum
                </button>
            </div>
        </form>
    </div>

</div>
@endsection