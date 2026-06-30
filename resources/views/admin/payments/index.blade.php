@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex justify-between items-center">
        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Payment Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800">
                Manajemen Pembayaran
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Kelola seluruh data pembayaran tiket museum.
            </p>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400">Total Payment</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $payments->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400">Paid Payment</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $payments->where('payment_status', 'paid')->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400">Total Revenue</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                Rp {{ number_format($payments->where('payment_status','paid')->sum('amount'), 0, ',', '.') }}
            </h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">Transaction</th>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Paid At</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">
                    @forelse($payments as $payment)

                        <tr class="hover:bg-blue-50/40">

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                #{{ $payment->transaction?->id ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $payment->transaction?->invoice_code ?? '-' }}
                            </td>

                            <td class="px-6 py-4 font-bold text-slate-800">
                                Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $payment->payment_method ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                @if($payment->payment_status == 'paid')
                                    <span class="px-3 py-1 rounded-xl bg-green-50 text-green-600 text-xs font-semibold">
                                        Paid
                                    </span>
                                @elseif($payment->payment_status == 'pending')
                                    <span class="px-3 py-1 rounded-xl bg-yellow-50 text-yellow-600 text-xs font-semibold">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-xl bg-red-50 text-red-600 text-xs font-semibold">
                                        Failed
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('payments.show', $payment->id) }}"
                                   class="px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition">
                                    Detail
                                </a>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                Belum ada data pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>
@endsection
