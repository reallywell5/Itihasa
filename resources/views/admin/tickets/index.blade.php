@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Ticket Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Manajemen Tiket
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Kelola kategori tiket, harga, dan slot pengunjung museum.
            </p>
        </div>

        <a href="{{ route('tickets.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
            Tambah Tiket
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
            <p class="text-sm text-slate-400 mb-2">Total Tiket</p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ method_exists($tickets, 'total') ? $tickets->total() : $tickets->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 mb-2">Total Slot</p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ $tickets->sum('slot') }}
            </h2>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">Rata-rata Harga</p>
            <h2 class="text-2xl font-bold">
                Rp {{ number_format($tickets->avg('price') ?? 0, 0, ',', '.') }}
            </h2>
        </div>

    </div>

    {{-- SEARCH --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h2 class="text-lg font-bold text-slate-800">
                Daftar Tiket per Museum
            </h2>

            <p class="text-sm text-slate-400">
                Kategori tiket dikelompokkan berdasarkan museum.
            </p>
        </div>

        <form method="GET" class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari tiket..."
                   class="bg-transparent outline-none text-sm w-full text-slate-600">
        </form>

    </div>

    {{-- GROUPED BY MUSEUM --}}
    @php
        $groupedTickets = $tickets->groupBy(function ($ticket) {
            return $ticket->museum->name ?? 'Tanpa Museum';
        });
    @endphp

    @forelse ($groupedTickets as $museumName => $museumTickets)

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

            {{-- CARD HEADER PER MUSEUM --}}
            <div class="px-6 py-5 border-b border-blue-50 flex items-center gap-4">

                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold uppercase">
                    {{ strtoupper(substr($museumName, 0, 2)) }}
                </div>

                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        {{ $museumName }}
                    </h2>

                    <p class="text-sm text-slate-400">
                        {{ $museumTickets->count() }} kategori tiket
                    </p>
                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-[700px] w-full">

                    <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                        <tr>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Slot</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">

                        @foreach ($museumTickets as $ticket)

                        <tr class="hover:bg-blue-50/30 transition">

                            {{-- CATEGORY --}}
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">
                                    {{ $ticket->ticket_name }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    TKT-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                </p>
                            </td>

                            {{-- PRICE --}}
                            <td class="px-6 py-4 font-bold text-slate-800">
                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            </td>

                            {{-- SLOT --}}
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold">
                                    {{ $ticket->slot }} Pax
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                                       class="px-4 py-2 rounded-xl bg-yellow-50 text-yellow-600 text-sm font-semibold hover:bg-yellow-100 transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('tickets.destroy', $ticket->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus tiket ini?')">
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

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @empty

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-16 text-center text-slate-400">
            Belum ada tiket.
        </div>

    @endforelse

    {{-- PAGINATION --}}
    @if(method_exists($tickets, 'links'))
        <div>
            {{ $tickets->links() }}
        </div>
    @endif

</div>
@endsection
