@extends('layouts.app')

@section('title', 'Museums')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 pb-2">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold tracking-wider text-indigo-600 uppercase">
                <span>Dashboard</span>
                <span class="text-zinc-300">/</span>
                <span class="text-zinc-500">Museum</span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 mt-1">
                Manajemen Museum
            </h1>

            <p class="text-sm text-zinc-500 mt-1.5">
                Kelola data entitas, alamat, dan jam operasional museum.
            </p>
        </div>

        <a href="{{ route('museums.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-800 shadow-sm transition">
            <span class="text-lg leading-none">+</span>
            Tambah Museum
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm shadow-sm">
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Data Table Container --}}
    <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-sm">

        <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900">
                    Daftar Museum
                </h2>

                <p class="text-xs text-zinc-500 mt-0.5">
                    Menampilkan total <span class="font-semibold text-zinc-700">{{ $museums->count() }}</span> data museum.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 text-zinc-500 text-xs font-bold uppercase tracking-wider border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3.5">Detail Museum</th>
                        <th class="px-6 py-3.5">Alamat</th>
                        <th class="px-6 py-3.5">Jam Operasional</th>
                        <th class="px-6 py-3.5 text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100">
                    @forelse($museums as $museum)
                        <tr class="hover:bg-zinc-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-zinc-100 text-zinc-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($museum->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-semibold text-zinc-900">
                                            {{ $museum->name }}
                                        </p>

                                        <p class="text-xs text-zinc-400 mt-0.5">
                                            M-{{ str_pad($museum->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-zinc-600 max-w-md">
                                {{ $museum->address ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                @if($museum->operational_hours)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-zinc-100 text-zinc-800 text-xs font-medium border border-zinc-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $museum->operational_hours }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        Belum diatur
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('museums.edit', $museum->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition"
                                       title="Edit">
                                        ✎
                                    </a>

                                    <form action="{{ route('museums.destroy', $museum->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus museum ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-red-600 hover:bg-red-50 transition"
                                                title="Hapus">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-zinc-50 border border-zinc-100 rounded-2xl flex items-center justify-center mx-auto text-zinc-400 shadow-sm mb-4">
                                    🏛️
                                </div>

                                <h3 class="text-base font-bold text-zinc-900">
                                    Belum Ada Arsip Museum
                                </h3>

                                <p class="text-sm text-zinc-500 mt-1">
                                    Mulai tambahkan museum pertama ke dalam sistem.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
