@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 pb-2">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold tracking-wider text-indigo-600 uppercase">
                <span>Dashboard</span>
                <span class="text-zinc-300">/</span>
                <span class="text-zinc-500">Pembayaran</span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 mt-1">
                Data Pembayaran
            </h1>

            <p class="text-sm text-zinc-500 mt-1.5">
                Kelola data pembayaran tiket museum.
            </p>
        </div>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-sm">

        <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900">
                    Daftar Pembayaran
                </h2>

                <p class="text-xs text-zinc-500 mt-0.5">
                    Menampilkan total <span class="font-semibold text-zinc-700">{{ $payments->count() }}</span> data pembayaran.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 text-zinc-500 text-xs font-bold uppercase tracking-wider border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3.5">Transaksi</th>
                        <th class="px-6 py-3.5">Metode</th>
                        <th class="px-6 py-3.5">Total Pembayaran</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-6 py-3.5 text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-zinc-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-zinc-100 text-zinc-700 flex items-center justify-center font-bold text-sm">
                                        P
                                    </div>

                                    <div>
                                        <p class="font-semibold text-zinc-900">
                                            Transaksi #{{ $payment->transaction->id ?? '-' }}
                                        </p>

                                        <p class="text-xs text-zinc-400 mt-0.5">
                                            PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-zinc-600">
                                {{ $payment->payment_method }}
                            </td>

                            <td class="px-6 py-4 text-zinc-900 font-semibold">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                @if($payment->payment_status == 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Paid
                                    </span>
                                @elseif($payment->payment_status == 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-xs font-medium border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Failed
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-zinc-600">
                                {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('payments.show', $payment->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition"
                                   title="Detail">
                                    👁
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-zinc-50 border border-zinc-100 rounded-2xl flex items-center justify-center mx-auto text-zinc-400 shadow-sm mb-4">
                                    💳
                                </div>

                                <h3 class="text-base font-bold text-zinc-900">
                                    Belum Ada Pembayaran
                                </h3>

                                <p class="text-sm text-zinc-500 mt-1">
                                    Data pembayaran tiket akan tampil di halaman ini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
