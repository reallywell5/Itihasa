@extends('layouts.app')

@section('title', 'Manajemen Petugas Loket')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-blue-100 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold mb-1.5">
                🏛 Admin • {{ Auth::user()?->museum?->name ?? 'Museum' }}
            </span>
            <h1 class="text-xl font-bold text-slate-800">
                Daftar Petugas Gate & Loket
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Kelola akun petugas lapangan yang bertugas memindai dan memvalidasi tiket masuk di <strong>{{ Auth::user()?->museum?->name ?? 'museum Anda' }}</strong>.
            </p>
        </div>

        <a href="{{ route('admin.petugas.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-bold shadow hover:bg-blue-700 transition shrink-0">
            <span>+ Tambah Petugas Baru</span>
        </a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-xl px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Petugas</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ $totalPetugas ?? (method_exists($petugas, 'total') ? $petugas->total() : count($petugas)) }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Akun staf aktif</p>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-blue-600 uppercase">Penugasan</p>
            <h2 class="text-sm font-bold text-slate-800 mt-1 line-clamp-1">
                {{ Auth::user()?->museum?->name ?? 'Museum' }}
            </h2>
            <p class="text-[10px] text-blue-500 mt-0.5">Otoritas lokal museum</p>
        </div>

        <div class="bg-white rounded-xl border border-emerald-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-emerald-600 uppercase">Akses Sistem</p>
            <h2 class="text-sm font-bold text-emerald-700 mt-1">
                Scanner QR & Validasi Manual
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Gate masuk pengunjung</p>
        </div>
    </div>

    {{-- TABLE CONTAINER --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Daftar Petugas Terdaftar
                </h2>
                <p class="text-[11px] text-slate-400">
                    Petugas hanya dapat memindai tiket milik museum ini.
                </p>
            </div>

            <form method="GET" action="{{ route('admin.petugas.index') }}" class="flex items-center gap-2 max-w-xs w-full">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau email..."
                    class="w-full px-3 py-1.5 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-600"
                >
                <button type="submit"
                        class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition shrink-0">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100 text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Petugas</th>
                        <th class="px-4 py-3">Email Login</th>
                        <th class="px-4 py-3">Museum Penugasan</th>
                        <th class="px-4 py-3">Terdaftar</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($petugas as $i => $item)
                        <tr class="hover:bg-blue-50/30 transition align-middle">
                            <td class="px-4 py-3 font-semibold text-slate-400">
                                {{ method_exists($petugas, 'firstItem') && $petugas->firstItem() ? $petugas->firstItem() + $i : $i + 1 }}
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-800">
                                {{ $item->name }}
                                <span class="block text-[10px] text-slate-400 font-normal">
                                    ID: STF-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 font-mono">
                                {{ $item->email }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                    🛡 {{ $item->museum?->name ?? (Auth::user()?->museum?->name ?? 'Museum') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.petugas.edit', $item->id) }}"
                                       class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-[11px] font-bold transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.petugas.destroy', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun petugas {{ $item->name }}?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-[11px] font-bold transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs">
                                Belum ada akun petugas untuk museum ini. Klik tombol <strong>+ Tambah Petugas Baru</strong> untuk mendaftarkan staf gate Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($petugas, 'hasPages') && $petugas->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">
                {{ $petugas->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
