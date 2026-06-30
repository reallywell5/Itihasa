@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Transaction Detail
            </p>

            <h1 class="text-2xl font-bold text-slate-800">
                Detail Transaksi
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Informasi lengkap transaksi tiket museum.
            </p>
        </div>

        <a href="{{ route('transactions.index') }}"
           class="px-4 py-2 rounded-xl border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 transition">
            Kembali
        </a>

    </div>

    {{-- HERO --}}
    <div class="bg-white rounded-3xl border border-blue-100 shadow-sm p-6 flex items-center justify-between">

        <div>
            <p class="text-sm text-slate-400 mb-2">
                Invoice Code
            </p>

            <h2 class="text-2xl font-bold text-slate-800">
                {{ $transaction->invoice_code ?? 'TRX-'.$transaction->id }}
            </h2>
        </div>

        <div>
            @if($transaction->payment_status == 'paid')
                <span class="px-4 py-2 rounded-full bg-green-50 text-green-600 text-sm font-semibold">
                    Paid
                </span>

            @elseif($transaction->payment_status == 'pending')
                <span class="px-4 py-2 rounded-full bg-yellow-50 text-yellow-600 text-sm font-semibold">
                    Pending
                </span>

            @else
                <span class="px-4 py-2 rounded-full bg-red-50 text-red-600 text-sm font-semibold">
                    Failed
                </span>
            @endif
        </div>

    </div>

    {{-- DETAIL --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- LEFT --}}
        <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-8">

            <h3 class="text-lg font-bold text-slate-800 mb-6">
                Informasi Pembeli
            </h3>

            <div class="space-y-5">

                <div>
                    <p class="text-xs uppercase font-bold text-zinc-400">
                        Nama Pengunjung
                    </p>
                    <p class="mt-2 text-lg font-bold text-zinc-900">
                        {{ $transaction->user?->name ?? 'Guest' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase font-bold text-zinc-400">
                        Email
                    </p>
                    <p class="mt-2 text-sm text-zinc-700">
                        {{ $transaction->user?->email ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase font-bold text-zinc-400">
                        Waktu Transaksi
                    </p>
                    <p class="mt-2 text-sm text-zinc-700">
                        {{ $transaction->created_at?->format('d M Y • H:i') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase font-bold text-zinc-400">
                        Total Pembayaran
                    </p>
                    <p class="mt-2 text-2xl font-bold text-blue-600">
                        Rp {{ number_format($transaction->total_amount ?? 0, 0, ',', '.') }}
                    </p>
                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-8">

            <h3 class="text-lg font-bold text-slate-800 mb-6">
                QR Code Tiket
            </h3>

            @if($transaction->payment_status == 'paid')

                <div class="flex flex-col items-center">

                    <div class="bg-white p-4 rounded-2xl border border-zinc-200 shadow-sm mb-4">
                        {!! QrCode::size(220)->generate($transaction->invoice_code) !!}
                    </div>

                    <p class="text-xs font-mono text-zinc-500">
                        {{ $transaction->invoice_code }}
                    </p>

                </div>

            @else

                <div class="h-full flex flex-col items-center justify-center text-center py-12">

                    <div class="w-20 h-20 rounded-3xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-3xl mb-4">
                        🎫
                    </div>

                    <p class="text-sm font-semibold text-slate-700">
                        QR Code Belum Aktif
                    </p>

                    <p class="text-xs text-slate-400 mt-2 max-w-xs">
                        QR Code akan tersedia setelah pembayaran berhasil.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>
@endsection
