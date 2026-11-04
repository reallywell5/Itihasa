@extends('layouts.app')

@section('title', auth()->user()->isSuperAdmin() ? 'Direktori Museum' : 'Profil Museum')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                Direktori & Manajemen Museum
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                {{ auth()->user()->isSuperAdmin() ? 'Pantau seluruh museum mitra terdaftar di Indonesia, kontrol status operasional, serta kelola akun penanggung jawab masing-masing destinasi.' : 'Kelola informasi profil, fasilitas, jam buka, dan galeri museum Anda.' }}
            </p>
        </div>

        @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('museums.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 text-white text-xs font-bold shadow hover:bg-purple-700 transition shrink-0">
                <span>+ Tambah Museum Baru</span>
            </a>
        @endif
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-xl px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Museum</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ $totalMuseums ?? (method_exists($museums, 'total') ? $museums->total() : $museums->count()) }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Mitra Terdaftar</p>
        </div>

        <div class="bg-white rounded-xl border border-emerald-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-emerald-600 uppercase">Aktif Buka</p>
            <h2 class="text-2xl font-extrabold text-emerald-700 mt-1">
                {{ $totalActive ?? 0 }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Reservasi normal</p>
        </div>

        <div class="bg-white rounded-xl border border-amber-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-amber-600 uppercase">Renovasi / Tutup</p>
            <h2 class="text-2xl font-extrabold text-amber-700 mt-1">
                {{ $totalRenovation ?? 0 }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Pemeliharaan berkala</p>
        </div>

        <div class="bg-white rounded-xl border border-red-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-red-600 uppercase">Nonaktif</p>
            <h2 class="text-2xl font-extrabold text-red-700 mt-1">
                {{ $totalInactive ?? 0 }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Kemitraan ditangguhkan</p>
        </div>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('museums.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    🔍
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama museum, alamat, atau kota..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition"
                >
            </div>

            <select name="category" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                <option value="">Semua Kategori</option>
                <option value="museum" {{ request('category') == 'museum' ? 'selected' : '' }}>Museum</option>
                <option value="seni" {{ request('category') == 'seni' ? 'selected' : '' }}>Seni</option>
                <option value="budaya" {{ request('category') == 'budaya' ? 'selected' : '' }}>Budaya</option>
                <option value="alam" {{ request('category') == 'alam' ? 'selected' : '' }}>Alam</option>
                <option value="religius" {{ request('category') == 'religius' ? 'selected' : '' }}>Religius</option>
            </select>

            <select name="status" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>● Aktif Buka</option>
                <option value="renovation" {{ request('status') == 'renovation' ? 'selected' : '' }}>⚠️ Renovasi / Tutup</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>❌ Nonaktif</option>
            </select>

            <button type="submit"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
                Filter
            </button>

            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('museums.index') }}"
                   class="px-3 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- TABLE MUSEUM --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-4 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">
                Direktori Museum ({{ method_exists($museums, 'total') ? $museums->total() : $museums->count() }})
            </span>
            <span class="text-[11px] text-slate-400">
                Menampilkan halaman {{ method_exists($museums, 'currentPage') ? $museums->currentPage() : 1 }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Museum</th>
                        <th class="px-4 py-3">Admin Penanggung Jawab</th>
                        <th class="px-4 py-3">Jam Operasional</th>
                        <th class="px-4 py-3 text-center">Tiket & Ulasan</th>
                        <th class="px-4 py-3 text-center">Status Operasional</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($museums as $museum)
                        @php
                            $mStatus = $museum->status ?? 'active';
                            $statusBadge = match($mStatus) {
                                'renovation' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'inactive' => 'bg-red-100 text-red-700 border-red-200',
                                default => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            };
                            $statusText = match($mStatus) {
                                'renovation' => '⚠️ Renovasi / Tutup',
                                'inactive' => '❌ Nonaktif',
                                default => '● Aktif Buka',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            {{-- MUSEUM INFO --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        <img src="{{ $museum->image ? asset('storage/' . $museum->image) : asset('images/default-museum.jpg') }}"
                                             alt="{{ $museum->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <div class="flex items-center gap-1.5 mb-0.5">
                                            <span class="px-1.5 py-0.2 rounded bg-purple-50 text-purple-700 font-bold text-[9px] uppercase border border-purple-100">
                                                {{ ucfirst($museum->category ?? 'Museum') }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-mono">
                                                #M-{{ str_pad($museum->id, 3, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                        <h3 class="font-bold text-slate-800 text-xs truncate">
                                            {{ $museum->name }}
                                        </h3>
                                        <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                            📍 {{ $museum->address }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- ADMIN PENANGGUNG JAWAB --}}
                            <td class="px-4 py-3.5">
                                @if($museum->adminUser)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($museum->adminUser->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 text-xs">{{ $museum->adminUser->name }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $museum->adminUser->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                                        ⚠️ Belum Ada Admin
                                    </span>
                                @endif
                            </td>

                            {{-- JAM OPERASIONAL --}}
                            <td class="px-4 py-3.5 text-slate-600">
                                <span class="font-mono text-xs font-semibold">{{ $museum->opening_time ?? '08:00' }} - {{ $museum->closing_time ?? '16:00' }}</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">WIB</span>
                            </td>

                            {{-- STATISTIK TIKET & ULASAN --}}
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px]">
                                        🎟️ {{ $museum->tickets_count ?? 0 }} Varian
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 font-bold text-[10px] border border-amber-200">
                                        ⭐ {{ $museum->reviews_count ?? 0 }}
                                    </span>
                                </div>
                            </td>

                            {{-- STATUS OPERASIONAL (FITUR C) --}}
                            <td class="px-4 py-3.5 text-center">
                                @if(auth()->user()->isSuperAdmin())
                                    <form action="{{ route('admin.museums.status', $museum->id) }}" method="POST">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()"
                                                class="text-[11px] font-bold rounded-lg px-2 py-1 border transition {{ $statusBadge }}">
                                            <option value="active" {{ $mStatus === 'active' ? 'selected' : '' }}>● Aktif Buka</option>
                                            <option value="renovation" {{ $mStatus === 'renovation' ? 'selected' : '' }}>⚠️ Renovasi / Tutup</option>
                                            <option value="inactive" {{ $mStatus === 'inactive' ? 'selected' : '' }}>❌ Nonaktif</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusBadge }}">
                                        {{ $statusText }}
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('museum.detail', $museum->id) }}"
                                       target="_blank"
                                       title="Lihat Halaman Publik"
                                       class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">
                                        👁️ Publik
                                    </a>

                                    <a href="{{ route('museums.edit', $museum->id) }}"
                                       title="Edit Data Museum"
                                       class="px-2.5 py-1 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 text-[11px] font-bold transition">
                                        ✏️ Edit
                                    </a>

                                    @if(auth()->user()->isSuperAdmin())
                                        <form action="{{ route('museums.destroy', $museum->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus data museum ini? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="Hapus Museum"
                                                    class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-[11px] font-bold transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-xs text-slate-400">
                                Belum ada museum yang sesuai dengan pencarian atau filter Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($museums, 'links'))
            <div class="p-4 border-t border-slate-100">
                {{ $museums->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
