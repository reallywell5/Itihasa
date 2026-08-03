@extends('layouts.app')

@section('title', 'Museums')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Museum Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Manajemen Museum
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Kelola data museum, alamat, foto, dan jam operasional museum.
            </p>
        </div>

        <a href="{{ route('museums.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
            + Tambah Museum
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH & FILTER — taruh di atas tabel/daftar museum --}}
    <div class="bg-white rounded-2xl border border-zinc-200 p-4 mb-6">
        <form method="GET" action="{{ route('museums.index') }}" class="flex flex-col sm:flex-row gap-3">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau alamat museum..."
                class="flex-1 px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
            >

            <select name="category" class="px-4 py-2.5 border border-zinc-200 rounded-xl text-sm">
                <option value="">Semua Kategori</option>
                <option value="museum" {{ request('category') == 'museum' ? 'selected' : '' }}>Museum</option>
                <option value="seni" {{ request('category') == 'seni' ? 'selected' : '' }}>Seni</option>
                <option value="budaya" {{ request('category') == 'budaya' ? 'selected' : '' }}>Budaya</option>
                <option value="alam" {{ request('category') == 'alam' ? 'selected' : '' }}>Alam</option>
                <option value="religius" {{ request('category') == 'religius' ? 'selected' : '' }}>Religius</option>
            </select>

            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition">
                Cari
            </button>

            @if(request('search') || request('category'))
                <a href="{{ route('museums.index') }}"
                class="px-5 py-2.5 rounded-xl border border-zinc-200 text-zinc-700 text-sm font-semibold hover:bg-zinc-100 transition text-center">
                    Reset
                </a>
            @endif

        </form>
    </div>

    {{-- ... DAFTAR/TABEL MUSEUM YANG SUDAH ADA ... --}}

    {{-- PAGINATION — taruh di bawah daftar --}}
    <div class="mt-6">
        {{ $museums->links() }}
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400">Total Museum</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $museums->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400">Museum Aktif</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $museums->count() }}
            </h2>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100">Quick Action</p>
            <h2 class="text-2xl font-bold mt-2">
                Tambah Museum Baru
            </h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Museum
                </h2>

                <p class="text-sm text-slate-400">
                    Menampilkan semua museum yang tersedia.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full">

                <thead class="bg-blue-50 text-blue-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4 text-left">Museum</th>
                        <th class="px-6 py-4 text-left">Alamat</th>
                        <th class="px-6 py-4 text-left">Jam Operasional</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse($museums as $museum)
                    <tr class="hover:bg-blue-50/30 transition">

                        {{-- MUSEUM --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">

                                <img
                                    src="{{ $museum->image ? asset('storage/' . $museum->image) : asset('images/default-museum.jpg') }}"
                                    class="w-14 h-14 rounded-2xl object-cover border border-blue-100">

                                <div>
                                    <h3 class="font-bold text-slate-800">
                                        {{ $museum->name }}
                                    </h3>

                                    <p class="text-xs text-slate-400">
                                        ID: M-{{ str_pad($museum->id, 4, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        {{-- ADDRESS --}}
                        <td class="px-6 py-4 text-slate-500 max-w-sm">
                            {{ $museum->address }}
                        </td>

                        {{-- HOURS --}}
                        <td class="px-6 py-4 text-slate-600">
                            {{ $museum->opening_time }} - {{ $museum->closing_time }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-xs font-semibold">
                                Aktif
                            </span>
                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">

                                <a href="{{ route('museums.edit', $museum->id) }}"
                                   class="px-3 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold hover:bg-blue-600 hover:text-white transition">
                                    Edit
                                </a>

                                <form action="{{ route('museums.destroy', $museum->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin hapus museum ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="px-3 py-2 rounded-xl bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-600 hover:text-white transition">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty

                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            Belum ada data museum.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>
@endsection
