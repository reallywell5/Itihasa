@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Transaction Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Riwayat Transaksi
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Kelola seluruh data transaksi tiket museum yang dilakukan oleh pengunjung melalui aplikasi.
            </p>
        </div>

        <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16 8V6a4 4 0 10-8 0v2M5 8h14l1 12H4L5 8z"/>
            </svg>
        </div>

    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Total Transaksi
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                {{ $transactions->count() }}
            </h2>

            <p class="text-sm text-blue-600 font-semibold mt-2">
                Data transaksi tercatat
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Total Pendapatan
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                Rp {{ number_format($transactions->sum('total_price'), 0, ',', '.') }}
            </h2>

            <p class="text-sm text-blue-600 font-semibold mt-2">
                Akumulasi transaksi
            </p>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">
                Status Sistem
            </p>

            <h2 class="text-2xl font-bold">
                Aktif
            </h2>

            <p class="text-sm text-blue-100 mt-2">
                Monitoring transaksi berjalan normal.
            </p>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Transaksi
                </h2>

                <p class="text-sm text-slate-400">
                    Menampilkan seluruh data transaksi tiket museum.
                </p>
            </div>

            <div class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">

                <svg class="w-4 h-4 text-blue-600 mr-2"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                </svg>

                <input type="text"
                       placeholder="Search transaction..."
                       class="bg-transparent outline-none text-sm w-full text-slate-600">
            </div>

        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Transaksi</th>
                        <th class="px-6 py-4">Pengunjung</th>
                        <th class="px-6 py-4">Total Harga</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse ($transactions as $transaction)

                        <tr class="hover:bg-blue-50/40 transition">

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">

                                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                        TR
                                    </div>

                                    <div>
                                        <p class="font-bold text-slate-800">
                                            TRX-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-0.5">
                                            {{ $transaction->created_at?->format('d M Y') ?? '-' }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $transaction->user?->name ?? 'Guest' }}
                            </td>

                            <td class="px-6 py-4 font-bold text-slate-800">
                                Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $transaction->created_at?->format('d M Y, H:i') ?? '-' }} WIB
                            </td>

                            <td class="px-6 py-4">

                                @if($transaction->status == 'paid')

                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        Paid
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-right">

                                <a href="{{ route('transactions.show', $transaction->id) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                   title="Detail">

                                    <svg class="w-4 h-4"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/>
                                    </svg>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">

                                <div class="w-16 h-16 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-center mx-auto text-blue-600 shadow-sm mb-4">

                                    <svg class="w-8 h-8"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M16 8V6a4 4 0 10-8 0v2M5 8h14l1 12H4L5 8z"/>
                                    </svg>

                                </div>

                                <h3 class="text-base font-bold text-slate-800">
                                    Belum Ada Transaksi
                                </h3>

                                <p class="text-sm text-slate-400 mt-1">
                                    Riwayat transaksi tiket akan tampil di halaman ini.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

    @if(method_exists($transactions, 'links'))
        <div class="bg-white rounded-2xl border border-blue-100 p-4 shadow-sm">
            {{ $transactions->links() }}
        </div>
    @endif

</div>
@endsection
