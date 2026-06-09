@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-zinc-900">
                Detail Pembayaran
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Informasi lengkap pembayaran transaksi.
            </p>
        </div>

        <a href="{{ route('payments.index') }}"
           class="px-4 py-2 rounded-lg border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 transition">

            Kembali

        </a>

    </div>

    <!-- Card -->
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Transaction -->
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Transaction ID
                </p>

                <h2 class="mt-2 text-lg font-bold text-zinc-900">
                    #{{ $payment->transaction->id ?? '-' }}
                </h2>
            </div>

            <!-- Method -->
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Payment Method
                </p>

                <h2 class="mt-2 text-lg font-semibold text-zinc-900">
                    {{ $payment->payment_method }}
                </h2>
            </div>

            <!-- Amount -->
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Amount
                </p>

                <h2 class="mt-2 text-lg font-bold text-zinc-900">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </h2>
            </div>

            <!-- Status -->
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Payment Status
                </p>

                <div class="mt-3">

                    @if($payment->payment_status == 'paid')

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                            Paid
                        </span>

                    @elseif($payment->payment_status == 'pending')

                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                            Pending
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            Failed
                        </span>

                    @endif

                </div>
            </div>

            <!-- Paid At -->
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Paid At
                </p>

                <p class="mt-2 text-sm text-zinc-700">

                    {{ $payment->paid_at
                        ? $payment->paid_at->format('d M Y H:i')
                        : '-'
                    }}

                </p>
            </div>

            <!-- Created At -->
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Created At
                </p>

                <p class="mt-2 text-sm text-zinc-700">
                    {{ $payment->created_at->format('d M Y H:i') }}
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
