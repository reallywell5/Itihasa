@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 pb-2">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold tracking-wider text-indigo-600 uppercase">
                <span>Dashboard</span>
                <span class="text-zinc-300">/</span>
                <span class="text-zinc-500">Tickets</span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 mt-1">
                Manajemen Tiket
            </h1>

            <p class="text-sm text-zinc-500 mt-1.5">
                Kelola kategori tiket masuk, harga tiket, dan jumlah slot pengunjung museum.
            </p>
        </div>

        <a href="{{ route('tickets.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-800 shadow-sm transition">
            <span class="text-lg leading-none">+</span>
            Tambah Tiket
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm shadow-sm">
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-sm">

        <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900">
                    Daftar Tiket
                </h2>

                <p class="text-xs text-zinc-500 mt-0.5">
                    Menampilkan total
                    <span class="font-semibold text-zinc-700">
                        {{ method_exists($tickets, 'total') ? $tickets->total() : count($tickets) }}
                    </span>
                    data tiket.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 text-zinc-500 text-xs font-bold uppercase tracking-wider border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3.5">Detail Museum</th>
                        <th class="px-6 py-3.5">Kategori Tiket</th>
                        <th class="px-6 py-3.5">Harga Tiket</th>
                        <th class="px-6 py-3.5">Jumlah Tiket</th>
                        <th class="px-6 py-3.5 text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100">
                    @forelse ($tickets as $ticket)
                        <tr class="hover:bg-zinc-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-zinc-100 text-zinc-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($ticket->museum->name ?? 'M', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-semibold text-zinc-900">
                                            {{ $ticket->museum->name ?? '-' }}
                                        </p>

                                        <p class="text-xs text-zinc-400 mt-0.5">
                                            TKT-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-zinc-600">
                                {{ $ticket->ticket_name }}
                            </td>

                            <td class="px-6 py-4 text-zinc-900 font-semibold">
                                Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-zinc-100 text-zinc-800 text-xs font-medium border border-zinc-200">
                                    {{ $ticket->slot }} Pax
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition"
                                       title="Edit">
                                        ✎
                                    </a>

                                    <form action="{{ route('tickets.destroy', $ticket->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus tiket ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-red-600 hover:bg-red-50 transition"
                                                title="Hapus">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-zinc-50 border border-zinc-100 rounded-2xl flex items-center justify-center mx-auto text-zinc-400 shadow-sm mb-4">
                                    🎟️
                                </div>

                                <h3 class="text-base font-bold text-zinc-900">
                                    Belum Ada Tiket
                                </h3>

                                <p class="text-sm text-zinc-500 mt-1">
                                    Tambahkan data tiket pertama ke dalam sistem.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    @if(method_exists($tickets, 'links'))
        <div>
            {{ $tickets->links() }}
        </div>
    @endif

</div>
@endsection
