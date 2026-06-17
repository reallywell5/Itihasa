@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                User Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Manajemen Pengguna
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Kelola data akun pengunjung, email pengguna, hak akses, dan riwayat registrasi sistem.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </a>

    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <div class="bg-white border border-blue-100 rounded-2xl p-4 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                ✓
            </div>

            <p class="text-sm font-semibold text-slate-700">
                {{ session('success') }}
            </p>
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Total Pengguna
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                {{ method_exists($users, 'total') ? $users->total() : count($users) }}
            </h2>

            <p class="text-sm text-blue-600 font-semibold mt-2">
                Akun terdaftar
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Status Sistem
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                Aktif
            </h2>

            <p class="text-sm text-blue-600 font-semibold mt-2">
                Monitoring pengguna berjalan
            </p>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">
                User Database
            </p>

            <h2 class="text-2xl font-bold">
                Account Control
            </h2>

            <p class="text-sm text-blue-100 mt-2">
                Kelola seluruh akun pengguna sistem.
            </p>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Pengguna
                </h2>

                <p class="text-sm text-slate-400">
                    Menampilkan seluruh akun pengguna yang terdaftar.
                </p>
            </div>

            <div class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">

                <svg class="w-4 h-4 text-blue-600 mr-2"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                </svg>

                <input type="text"
                       placeholder="Search user..."
                       class="bg-transparent outline-none text-sm w-full text-slate-600">
            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Profil Pengguna</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Tanggal Gabung</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse ($users as $user)

                    <tr class="hover:bg-blue-50/40 transition">

                        {{-- PROFILE --}}
                        <td class="px-6 py-4">

                            <div class="flex items-center gap-4">

                                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm uppercase">
                                    {{ strtoupper(substr($user->name,0,2)) }}
                                </div>

                                <div>
                                    <p class="font-bold text-slate-800">
                                        {{ $user->name }}
                                    </p>

                                    <p class="text-xs text-slate-400 mt-0.5">
                                        USR-{{ str_pad($user->id,5,'0',STR_PAD_LEFT) }}
                                    </p>
                                </div>

                            </div>

                        </td>

                        {{-- EMAIL --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $user->email }}
                        </td>

                        {{-- ROLE --}}
                        <td class="px-6 py-4">

                            @if($user->role === 'admin')

                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    Admin
                                </span>

                            @elseif($user->role === 'petugas')

                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    Petugas
                                </span>

                            @else

                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    User
                                </span>

                            @endif

                        </td>

                        {{-- DATE --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $user->created_at?->format('d M Y') ?? '-' }}
                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">

                            <div class="flex justify-end items-center gap-2">

                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">

                                    <svg class="w-4 h-4"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>

                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">

                                        <svg class="w-4 h-4"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v2H9V5a1 1 0 011-1z"/>
                                        </svg>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">

                            <div class="w-16 h-16 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-center mx-auto text-blue-600 shadow-sm mb-4">

                                <svg class="w-8 h-8"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>

                            </div>

                            <h3 class="text-base font-bold text-slate-800">
                                Belum Ada Pengguna
                            </h3>

                            <p class="text-sm text-slate-400 mt-1">
                                Sistem belum mendeteksi akun pengguna yang terdaftar.
                            </p>

                            <a href="{{ route('users.create') }}"
                               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition mt-5">
                                Tambah Pengguna
                            </a>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- PAGINATION --}}
    @if(method_exists($users,'links'))
        <div class="bg-white rounded-2xl border border-blue-100 p-4 shadow-sm">
            {{ $users->links() }}
        </div>
    @endif

</div>
@endsection
