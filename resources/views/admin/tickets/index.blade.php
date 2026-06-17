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
                Kelola kategori tiket masuk, harga tiket, dan jumlah slot pengunjung museum.
            </p>
        </div>

        <a href="{{ route('tickets.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Tiket
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
                Total Tiket
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ method_exists($tickets, 'total') ? $tickets->total() : count($tickets) }}
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                Data tiket tercatat
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Status Tiket
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                Aktif
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                Tiket siap dikelola
            </p>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">
                Quick Action
            </p>
            <h2 class="text-2xl font-bold">
                Tambah Tiket
            </h2>
            <p class="text-sm text-blue-100 mt-2">
                Input tiket baru ke sistem.
            </p>
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
                    Menampilkan seluruh kategori tiket museum.
                </p>
            </div>

            <div class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">
                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                </svg>

                <input type="text"
                       placeholder="Search ticket..."
                       class="bg-transparent outline-none text-sm w-full text-slate-600">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Detail Museum</th>
                        <th class="px-6 py-4">Kategori Tiket</th>
                        <th class="px-6 py-4">Harga Tiket</th>
                        <th class="px-6 py-4">Jumlah Tiket</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">
                    @forelse ($tickets as $ticket)
                        <tr class="hover:bg-blue-50/40 transition">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($ticket->museum->name ?? 'M', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-bold text-slate-800">
                                            {{ $ticket->museum->name ?? '-' }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-0.5">
                                            TKT-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $ticket->ticket_name }}
                            </td>

                            <td class="px-6 py-4 text-slate-800 font-bold">
                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    {{ $ticket->slot }} Pax
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('tickets.destroy', $ticket->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus tiket ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                                title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
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
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 010 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 010-4V7a2 2 0 012-2z"/>
                                    </svg>
                                </div>

                                <h3 class="text-base font-bold text-slate-800">
                                    Belum Ada Tiket
                                </h3>

                                <p class="text-sm text-slate-400 mt-1">
                                    Tambahkan data tiket pertama ke dalam sistem.
                                </p>

                                <a href="{{ route('tickets.create') }}"
                                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition mt-5">
                                    Tambah Tiket
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    @if(method_exists($tickets, 'links'))
        <div class="bg-white rounded-2xl border border-blue-100 p-4 shadow-sm">
            {{ $tickets->links() }}
        </div>
    @endif

</div>
@endsection
