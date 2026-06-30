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
                Kelola seluruh akun pengguna, petugas, dan admin dalam sistem Itihasa.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition">
            Tambah Pengguna
        </a>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="bg-white border border-blue-100 rounded-2xl p-4 shadow-sm">
            <p class="text-sm font-semibold text-slate-700">
                {{ session('success') }}
            </p>
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">Total Pengguna</p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ method_exists($users, 'total') ? $users->total() : count($users) }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">Admin</p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ $users->where('role', 'admin')->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">Petugas</p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ $users->where('role', 'staff')->count() }}
            </h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50">
            <h2 class="text-lg font-bold text-slate-800">
                Daftar Pengguna
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Tanggal Gabung</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50/40">

                        {{-- NAME --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">

                                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold uppercase">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                                </div>

                                <div>
                                    <p class="font-bold text-slate-800">
                                        {{ $user->name }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        USR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
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
                                <span class="px-3 py-1 rounded-xl bg-red-50 text-red-600 text-xs font-semibold">
                                    Admin
                                </span>

                            @elseif($user->role === 'staff')
                                <span class="px-3 py-1 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold">
                                    Staff
                                </span>

                            @else
                                <span class="px-3 py-1 rounded-xl bg-green-50 text-green-600 text-xs font-semibold">
                                    Visitor
                                </span>
                            @endif

                        </td>

                        {{-- CREATED --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $user->created_at?->format('d M Y') }}
                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">

                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="px-3 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                                    Edit
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus akun ini?')"
                                            class="px-3 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty

                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400">
                            Belum ada pengguna terdaftar.
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
