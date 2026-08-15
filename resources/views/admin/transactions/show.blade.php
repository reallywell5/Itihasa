@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('transactions.index') }}" class="text-xs text-blue-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Riwayat Transaksi
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Detail Transaksi #{{ $transaction->id }}
            </h1>
            <p class="text-xs text-slate-400">
                Invoice: <span class="font-bold text-slate-700">{{ $transaction->invoice_code ?? '-' }}</span>
            </p>
        </div>

        <a href="{{ route('transactions.index') }}"
           class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
            Kembali
        </a>
    </div>

    {{-- SUMMARY BANNER --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="text-[10px] uppercase font-bold text-slate-400">Total Pembayaran</span>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-0.5">
                Rp {{ number_format($transaction->total_amount ?? 0, 0, ',', '.') }}
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">
                Museum: <strong class="text-slate-700">{{ $transaction->booking->museum->name ?? '-' }}</strong>
            </p>
        </div>

        <div>
            @php
                $badge = match($transaction->payment_status) {
                    'paid' => 'bg-emerald-50 text-emerald-700',
                    'pending' => 'bg-amber-50 text-amber-700',
                    default => 'bg-red-50 text-red-700',
                };
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $badge }}">
                Status: {{ $transaction->payment_status }}
            </span>
        </div>
    </div>

    {{-- DETAIL INFO & QR --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- LEFT: DETAIL PEMBELI & ITEM --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                Informasi Pemesan
            </h3>

            <div class="space-y-3 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Nama Pengunjung</span>
                    <p class="mt-0.5 font-bold text-slate-800 text-sm">
                        {{ $transaction->booking->user->name ?? 'Tamu' }}
                    </p>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Email Akun</span>
                    <p class="mt-0.5 font-semibold text-slate-700">
                        {{ $transaction->booking->user->email ?? '-' }}
                    </p>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Waktu Transaksi</span>
                    <p class="mt-0.5 font-semibold text-slate-700">
                        {{ $transaction->created_at?->translatedFormat('d M Y • H:i:s') ?? '-' }}
                    </p>
                </div>

                {{-- RINCIAN TIKET --}}
                <div class="pt-2">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block mb-2">Rincian Tiket</span>
                    <div class="space-y-2 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        @foreach($transaction->booking->ticket_items ?? [] as $item)
                            <div class="flex justify-between items-center text-xs">
                                <div>
                                    <span class="font-bold text-slate-800">{{ $item['ticket_name'] ?? 'Tiket' }}</span>
                                    <span class="text-slate-400 font-normal"> × {{ $item['qty'] ?? 1 }}</span>
                                </div>
                                <span class="font-bold text-slate-800">
                                    Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: QR CODE TIKET --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">
                QR Code Masuk
            </h3>

            @if($transaction->payment_status === 'paid')
                <div class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-xl border border-slate-100 my-auto">
                    <div class="p-3 bg-white rounded-xl shadow-sm border border-slate-200">
                        {!! QrCode::size(180)->generate($transaction->invoice_code) !!}
                    </div>
                    <p class="text-[11px] font-mono text-slate-500 font-bold mt-3">
                        {{ $transaction->invoice_code }}
                    </p>
                    <div class="mt-2 text-center">
                        @if($transaction->used_at)
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold">
                                ✓ Sudah discan pada {{ $transaction->used_at->translatedFormat('d M Y, H:i') }}
                            </span>
                        @else
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">
                                Belum Digunakan
                            </span>
                        @endif
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center text-center p-8 bg-amber-50/50 rounded-xl border border-amber-100 my-auto">
                    <span class="text-3xl mb-2">⏳</span>
                    <p class="text-xs font-bold text-slate-700">QR Code Belum Aktif</p>
                    <p class="text-[10px] text-slate-400 mt-1 max-w-xs">
                        QR Code scan tiket akan otomatis aktif setelah pembayaran berstatus paid.
                    </p>
                </div>
            @endif

            <div class="text-center pt-2">
                <span class="text-[10px] text-slate-400">
                    Tiket terdaftar resmi di sistem Itihasa
                </span>
            </div>
        </div>

    </div>

</div>
@endsection
