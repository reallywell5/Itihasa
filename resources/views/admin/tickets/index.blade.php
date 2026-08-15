@extends('layouts.app')

@section('title', auth()->user()->isSuperAdmin() ? 'Pemantauan Tiket' : 'Kelola Tiket')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border {{ auth()->user()->isSuperAdmin() ? 'border-purple-100' : 'border-blue-100' }} p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if(auth()->user()->isSuperAdmin())
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[11px] font-bold mb-2">
                    👑 Super Admin • Pemantauan Tiket Seluruh Museum
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold mb-2">
                    🏛 Admin • {{ auth()->user()->museum?->name ?? 'Museum' }}
                </span>
            @endif
            <h1 class="text-xl font-bold text-slate-800">
                Katalog Tiket Masuk
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                {{ auth()->user()->isSuperAdmin() ? 'Memonitor seluruh kategori tiket, kuota per hari, dan penetapan harga tiket di setiap museum.' : 'Kelola kategori tiket, atur harga, dan sesuaikan kuota slot tiket untuk museum Anda.' }}
            </p>
        </div>

        @if(! auth()->user()->isSuperAdmin())
            <a href="{{ route('tickets.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-bold shadow hover:bg-blue-700 transition shrink-0">
                <span>+ Tambah Tiket Baru</span>
            </a>
        @endif
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 text-emerald-700 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Varian Tiket</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ method_exists($tickets, 'total') ? $tickets->total() : $tickets->count() }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Kategori tiket aktif</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Kuota / Sesi</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ number_format($tickets->sum('slot')) }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Slot pengunjung per hari</p>
        </div>

        <div class="{{ auth()->user()->isSuperAdmin() ? 'bg-purple-600' : 'bg-blue-600' }} rounded-xl p-4 text-white shadow-sm flex flex-col justify-between">
            <p class="text-[11px] font-semibold {{ auth()->user()->isSuperAdmin() ? 'text-purple-200' : 'text-blue-100' }} uppercase">Rata-Rata Harga Tiket</p>
            <h2 class="text-2xl font-extrabold mt-1">
                Rp {{ number_format($tickets->avg('price') ?? 0, 0, ',', '.') }}
            </h2>
            <p class="text-[10px] {{ auth()->user()->isSuperAdmin() ? 'text-purple-200' : 'text-blue-100' }} mt-0.5">Rata-rata nominal per tiket</p>
        </div>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="bg-white rounded-xl border border-slate-100 p-3.5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="text-xs text-slate-500 font-medium">
            Daftar Tiket {{ auth()->user()->isSuperAdmin() ? 'Seluruh Museum' : 'Museum ' . (auth()->user()->museum?->name ?? '') }}
        </div>

        <form method="GET" class="flex items-center gap-2">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama tiket atau museum..."
                   class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-slate-800">
            @if(request('search'))
                <a href="{{ route('tickets.index') }}" class="px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs text-slate-500 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- GROUPED BY MUSEUM --}}
    @php
        $groupedTickets = $tickets->groupBy(function ($ticket) {
            return $ticket->museum->name ?? 'Tanpa Museum';
        });
    @endphp

    @forelse ($groupedTickets as $museumName => $museumTickets)
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm">🏛</span>
                    <h3 class="text-xs font-bold text-slate-800">
                        {{ $museumName }}
                    </h3>
                </div>
                <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-[10px] font-bold text-slate-600">
                    {{ $museumTickets->count() }} Kategori
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-white text-slate-400 font-bold uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Kategori Tiket</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="px-4 py-3 text-center">Kuota / Slot</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach ($museumTickets as $ticket)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $ticket->ticket_name }}
                                    <span class="block text-[10px] text-slate-400 font-normal">
                                        ID: TKT-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-extrabold text-slate-800">
                                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold text-[10px]">
                                        {{ $ticket->slot }} Orang
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if(! auth()->user()->isSuperAdmin())
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('tickets.edit', $ticket->id) }}"
                                               class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('tickets.destroy', $ticket->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus kategori tiket ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-[11px] transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-medium italic">
                                            Read-only
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-100 p-12 text-center text-xs text-slate-400 shadow-sm">
            Belum ada tiket terdaftar.
        </div>
    @endforelse

</div>
@endsection
