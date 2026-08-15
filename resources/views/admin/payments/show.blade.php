@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('payments.index') }}" class="text-xs text-blue-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Daftar Pembayaran
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Detail Pembayaran #{{ $payment->id }}
            </h1>
            <p class="text-xs text-slate-400">
                Invoice: <span class="font-bold text-slate-700">{{ $payment->transaction?->invoice_code ?? '-' }}</span>
            </p>
        </div>

        <a href="{{ route('payments.index') }}"
           class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400">Kode Invoice</span>
                <p class="mt-1 text-sm font-bold text-slate-800">
                    {{ $payment->transaction?->invoice_code ?? '-' }}
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400">Total Nominal</span>
                <p class="mt-1 text-base font-extrabold text-slate-800">
                    Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400">Museum Terkait</span>
                <p class="mt-1 text-xs font-bold text-slate-800">
                    🏛 {{ $payment->transaction?->booking?->museum?->name ?? '-' }}
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400">Nama Pengunjung</span>
                <p class="mt-1 text-xs font-bold text-slate-800">
                    👤 {{ $payment->transaction?->booking?->user?->name ?? 'Tamu' }}
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400">Metode Pembayaran</span>
                <p class="mt-1 text-xs font-bold text-slate-800">
                    {{ $payment->payment_method ?: 'QRIS' }}
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400">Status Pembayaran</span>
                <div class="mt-1">
                    @php
                        $badge = match($payment->payment_status) {
                            'paid' => 'bg-emerald-50 text-emerald-700',
                            'pending' => 'bg-amber-50 text-amber-700',
                            default => 'bg-red-50 text-red-700',
                        };
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $badge }}">
                        {{ $payment->payment_status }}
                    </span>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400">Waktu Pembayaran Berhasil</span>
                <p class="mt-1 text-xs font-semibold text-slate-700">
                    {{ $payment->paid_at ? $payment->paid_at->translatedFormat('d M Y, H:i:s') : '-' }}
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400">Waktu Dibuat</span>
                <p class="mt-1 text-xs font-semibold text-slate-700">
                    {{ $payment->created_at?->translatedFormat('d M Y, H:i:s') ?? '-' }}
                </p>
            </div>

        </div>
    </div>

</div>
@endsection
