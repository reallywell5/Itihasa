@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[11px] font-bold mb-2">
                👑 Super Admin • Manajemen Akun & Penugasan Museum
            </span>
            <h1 class="text-xl font-bold text-slate-800">
                Daftar Pengguna & Admin
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                Daftarkan akun Admin untuk masing-masing museum, atur staf petugas lapangan, serta pantau seluruh akun pengguna terdaftar.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 text-white text-xs font-bold shadow hover:bg-purple-700 transition shrink-0">
            <span>+ Tambah Akun Baru</span>
        </a>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-xl px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Akun</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ $totalUsers ?? (method_exists($users, 'total') ? $users->total() : count($users)) }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Semua peran sistem</p>
        </div>

        <div class="bg-white rounded-xl border border-purple-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-purple-600 uppercase">Super Admin</p>
            <h2 class="text-2xl font-extrabold text-purple-700 mt-1">
                {{ $totalSuperAdmin ?? 0 }}
            </h2>
            <p class="text-[10px] text-purple-400 mt-0.5">Pusat kontrol</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-blue-600 uppercase">Admin Museum</p>
            <h2 class="text-2xl font-extrabold text-blue-700 mt-1">
                {{ $totalAdmin ?? 0 }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Penanggung jawab</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-emerald-600 uppercase">Petugas Scan</p>
            <h2 class="text-2xl font-extrabold text-emerald-700 mt-1">
                {{ $totalStaff ?? 0 }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Staf gate operasional</p>
        </div>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-3.5 border-b border-slate-100">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row gap-2.5">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau email..."
                    class="flex-1 px-3 py-1.5 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"
                >

                <select name="role" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs">
                    <option value="">Semua Role</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin Museum</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Petugas</option>
                    <option value="visitor" {{ request('role') == 'visitor' ? 'selected' : '' }}>Pengunjung</option>
                </select>

                <button type="submit"
                        class="px-4 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-bold hover:bg-slate-700 transition">
                    Filter
                </button>

                @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}"
                       class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Nama Pengguna</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Peran & Museum Terkait</th>
                        <th class="px-4 py-3">Terdaftar</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition align-middle">
                            <td class="px-4 py-3 font-bold text-slate-800">
                                {{ $user->name }}
                                <span class="block text-[10px] text-slate-400 font-normal">
                                    ID: USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3">
                                @if($user->role === 'super_admin')
                                    <span class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-800 text-[10px] font-bold">
                                        👑 Super Admin
                                    </span>
                                @elseif($user->role === 'admin')
                                    <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800 text-[10px] font-bold">
                                        🏛 Admin Museum
                                    </span>
                                    <span class="block text-[10px] text-slate-500 mt-0.5">
                                        {{ $user->museum?->name ?? 'Belum terikat museum' }}
                                    </span>
                                @elseif($user->role === 'staff')
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                        🛡 Petugas Loket/Scan
                                    </span>
                                    <span class="block text-[10px] text-slate-500 mt-0.5">
                                        {{ $user->museum?->name ?? 'Belum terikat museum' }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold">
                                        👤 Pengunjung
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-400 text-[11px] whitespace-nowrap">
                                {{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus akun pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-[11px] transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-xs text-slate-400">
                                Tidak ada data pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($users,'links'))
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
