@extends('layouts.petugas')

@section('title', 'Pengunjung Hari Ini')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">

        <p class="text-sm font-semibold text-blue-600 mb-2">
            Visitor Monitoring
        </p>

        <h1 class="text-2xl font-bold text-slate-800">
            Pengunjung Hari Ini
        </h1>

        <p class="text-slate-500 mt-2">
            Monitoring data pengunjung yang telah melakukan scan tiket.
        </p>

    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Total Pengunjung</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $totalVisitors }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Dewasa</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $adultCount }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Pelajar</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $studentCount }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Anak-anak</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $childCount }}
            </h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div x-data="{ search: '' }" class="bg-white rounded-3xl border border-blue-100 overflow-hidden shadow-sm">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col md:flex-row md:items-center justify-between gap-4">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Pengunjung
                </h2>

                <p class="text-sm text-slate-400">
                    Pengunjung yang telah memindai tiket dan masuk area museum.
                </p>
            </div>

            {{-- SEARCH BAR --}}
            <div class="relative w-full md:w-72">
                <input type="text"
                       x-model="search"
                       placeholder="Cari nama atau invoice..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-blue-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                </svg>
            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-[900px] w-full">

                <thead class="bg-blue-50 text-blue-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4 text-left">Nama Pengunjung</th>
                        <th class="px-6 py-4 text-left">Kode Tiket</th>
                        <th class="px-6 py-4 text-left">Museum</th>
                        <th class="px-6 py-4 text-left">Rincian Tiket</th>
                        <th class="px-6 py-4 text-left">Jam Masuk</th>
                        <th class="px-6 py-4 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse($transactions as $transaction)

                    <tr x-show="!search || '{{ strtolower($transaction->booking->user->name ?? '') }}'.includes(search.toLowerCase()) || '{{ strtolower($transaction->invoice_code) }}'.includes(search.toLowerCase())"
                        class="hover:bg-blue-50/40">

                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $transaction->booking->user->name }}
                        </td>

                        <td class="px-6 py-4 text-slate-500 font-mono text-sm">
                            {{ $transaction->invoice_code }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->booking->museum->name }}
                        </td>

                        <td class="px-6 py-4 text-slate-700 text-sm">
                            <div class="space-y-1">
                                @foreach($transaction->booking->ticket_items as $item)
                                    <div>
                                        <span class="font-medium text-slate-800">{{ $item['ticket_name'] }}</span>: {{ $item['qty'] }}x
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->used_at->format('H:i') }} WIB
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-xl bg-green-50 text-green-600 text-xs font-semibold border border-green-100">
                                Sudah Masuk
                            </span>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-slate-400">
                            Belum ada pengunjung yang masuk.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
