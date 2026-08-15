@extends('layouts.petugas')

@section('title', 'Pengunjung Hari Ini')

@section('content')
<div class="space-y-4 sm:space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold mb-1.5">
            👥 Monitoring Tamu • Data Gate Hari Ini
        </span>
        <h1 class="text-lg sm:text-xl font-bold text-slate-800">
            Daftar Pengunjung Hari Ini
        </h1>
        <p class="text-xs text-slate-400 mt-0.5">
            Pantau seluruh data pengunjung yang telah berhasil memindai tiket dan masuk ke area museum.
        </p>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Tamu</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ $totalVisitors }}
            </h2>
            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Semua kategori</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-blue-600 uppercase">Dewasa</p>
            <h2 class="text-2xl font-extrabold text-blue-700 mt-1">
                {{ $adultCount }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Tiket reguler</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-emerald-600 uppercase">Pelajar / Mahasiswa</p>
            <h2 class="text-2xl font-extrabold text-emerald-700 mt-1">
                {{ $studentCount }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Tiket edukasi</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-purple-600 uppercase">Anak-anak</p>
            <h2 class="text-2xl font-extrabold text-purple-700 mt-1">
                {{ $childCount }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Tiket anak</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div x-data="{ search: '' }" class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="p-3.5 sm:p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-bold text-slate-800">
                    Tamu yang Telah Masuk
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Data tervalidasi real-time dari gate scanner.
                </p>
            </div>

            {{-- SEARCH BAR --}}
            <div class="relative w-full sm:w-64">
                <input type="text"
                       x-model="search"
                       placeholder="Cari nama / kode invoice..."
                       class="w-full pl-8 pr-3 py-1.5 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600">
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                </svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Pengunjung</th>
                        <th class="px-4 py-3">Kode Invoice</th>
                        <th class="px-4 py-3">Rincian Tiket</th>
                        <th class="px-4 py-3">Jam Masuk</th>
                        <th class="px-4 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($transactions as $transaction)
                        <tr x-show="!search || '{{ strtolower($transaction->booking->user->name ?? '') }}'.includes(search.toLowerCase()) || '{{ strtolower($transaction->invoice_code) }}'.includes(search.toLowerCase())"
                            class="hover:bg-slate-50/70 transition align-middle">
                            <td class="px-4 py-3 font-bold text-slate-800">
                                {{ $transaction->booking->user->name ?? 'Pengunjung' }}
                                <span class="block text-[10px] text-slate-400 font-normal">
                                    {{ $transaction->booking->museum->name ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-[11px] text-slate-600">
                                {{ $transaction->invoice_code }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="space-y-0.5">
                                    @foreach($transaction->booking->ticket_items ?? [] as $item)
                                        <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-semibold mr-1">
                                            {{ $item['ticket_name'] ?? 'Tiket' }} (x{{ $item['qty'] ?? 1 }})
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-500 whitespace-nowrap">
                                {{ $transaction->used_at ? $transaction->used_at->format('H:i') . ' WIB' : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                    ✓ Sudah Masuk
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-xs text-slate-400">
                                Belum ada data pengunjung yang masuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
