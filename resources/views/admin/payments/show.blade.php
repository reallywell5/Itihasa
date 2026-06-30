@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Detail Pembayaran
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Informasi lengkap pembayaran.
            </p>
        </div>

        <a href="{{ route('payments.index') }}"
           class="px-4 py-2 rounded-xl border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">Transaction ID</p>
                <h2 class="mt-2 text-lg font-bold text-zinc-900">
                    #{{ $payment->transaction?->id ?? '-' }}
                </h2>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">Invoice Code</p>
                <h2 class="mt-2 text-lg font-semibold text-zinc-900">
                    {{ $payment->transaction?->invoice_code ?? '-' }}
                </h2>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">Payment Method</p>
                <h2 class="mt-2 text-lg font-semibold text-zinc-900">
                    {{ $payment->payment_method ?? '-' }}
                </h2>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">Amount</p>
                <h2 class="mt-2 text-lg font-bold text-green-600">
                    Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}
                </h2>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">Status</p>

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
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">Paid At</p>
                <p class="mt-2 text-sm text-zinc-700">
                    {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">Created At</p>
                <p class="mt-2 text-sm text-zinc-700">
                    {{ $payment->created_at?->format('d M Y H:i') ?? '-' }}
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
