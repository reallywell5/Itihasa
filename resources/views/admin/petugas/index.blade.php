@extends('layouts.app')

@section('title', 'Petugas')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Staff Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Akun Petugas
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Kelola akun petugas, hak akses operasional, dan validasi tiket museum.
            </p>
        </div>

        <a href="{{ route('admin.petugas.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
            Tambah Petugas
        </a>

    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-green-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Total Petugas
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ $petugas->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Role
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                Staff
            </h2>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">
                Status
            </p>
            <h2 class="text-2xl font-bold">
                Active
            </h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Petugas
                </h2>

                <p class="text-sm text-slate-400">
                    Semua akun petugas yang terdaftar.
                </p>
            </div>

            <form method="GET" class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari petugas..."
                       class="bg-transparent outline-none text-sm w-full text-slate-600">
            </form>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Terdaftar</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse ($petugas as $item)

                    <tr class="hover:bg-blue-50/30 transition">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">

                                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold uppercase">
                                    {{ strtoupper(substr($item->name, 0, 2)) }}
                                </div>

                                <div>
                                    <p class="font-bold text-slate-800">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        PTG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $item->email }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold">
                                {{ ucfirst($item->role) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $item->created_at->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 text-right">

                            <div class="flex justify-end gap-2">

                                <a href="{{ route('admin.petugas.edit', $item->id) }}"
                                   class="px-4 py-2 rounded-xl bg-yellow-50 text-yellow-600 text-sm font-semibold hover:bg-yellow-100 transition">
                                    Edit
                                </a>

                                <form action="{{ route('admin.petugas.destroy', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus petugas ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="px-4 py-2 rounded-xl bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            Belum ada data petugas.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection
