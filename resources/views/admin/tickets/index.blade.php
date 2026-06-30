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

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Tiket
                </h2>

                <p class="text-sm text-slate-400">
                    Semua kategori tiket museum.
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

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">Museum</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Slot</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse ($tickets as $ticket)

                    <tr class="hover:bg-blue-50/30 transition">

                        {{-- MUSEUM --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">

                                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold uppercase">
                                    {{ strtoupper(substr($ticket->museum->name ?? 'M', 0, 2)) }}
                                </div>

                                <div>
                                    <p class="font-bold text-slate-800">
                                        {{ $ticket->museum->name ?? '-' }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        TKT-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        {{-- CATEGORY --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $ticket->ticket_name }}
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

                    @empty

                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            Belum ada tiket.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- PAGINATION --}}
    @if(method_exists($tickets, 'links'))
        <div>
            {{ $tickets->links() }}
        </div>
    @endif

</div>
@endsection
