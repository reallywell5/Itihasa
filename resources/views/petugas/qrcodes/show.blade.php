@extends('layouts.petugas')

@section('title', 'Detail QR Code Tiket')

@section('content')
<div class="max-w-md mx-auto space-y-4 sm:space-y-5">

    <div class="flex items-center justify-between">
        <a href="{{ route('petugas.qrcodes.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-emerald-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar</span>
        </a>

        <button onclick="window.print()"
                type="button"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak</span>
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8 shadow-sm text-center space-y-5">

        <div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold mb-2">
                🛡 {{ $transaction->booking->museum->name ?? 'Museum' }}
            </span>
            <h1 class="text-base sm:text-lg font-bold text-slate-800">
                {{ $transaction->booking->user->name ?? 'Pengunjung' }}
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Kunjungan: {{ $transaction->booking->visit_date ? \Carbon\Carbon::parse($transaction->booking->visit_date)->translatedFormat('d F Y') : '-' }}
            </p>
        </div>

        {{-- QR CODE VIEW --}}
        <div class="flex justify-center p-4 bg-slate-50 rounded-2xl border border-slate-100 inline-block mx-auto shadow-inner">
            {!! QrCode::size(200)->generate($transaction->invoice_code) !!}
        </div>

        <div>
            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Nomor Invoice</span>
            <p class="font-mono text-sm font-bold text-slate-800 tracking-wider">
                {{ $transaction->invoice_code }}
            </p>
        </div>

        <div>
            @if($transaction->used_at)
                <span class="inline-flex px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                    ✓ Sudah Digunakan ({{ $transaction->used_at->format('d M Y, H:i') }} WIB)
                </span>
            @elseif($transaction->payment_status !== 'paid')
                <span class="inline-flex px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">
                    ● Menunggu Pembayaran
                </span>
            @else
                <span class="inline-flex px-3.5 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200">
                    ● Tiket Aktif / Belum Dipakai
                </span>
            @endif
        </div>

        {{-- RINCIAN VARIAN --}}
        <div class="pt-4 border-t border-slate-100 text-left text-xs space-y-1.5">
            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Rincian Tiket:</p>
            @foreach($transaction->booking->ticket_items ?? [] as $item)
                <div class="flex justify-between text-slate-700">
                    <span>{{ $item['ticket_name'] ?? 'Tiket' }} x{{ $item['qty'] ?? 1 }}</span>
                    <span class="font-semibold text-slate-900">
                        @if(($item['subtotal'] ?? 0) > 0)
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        @else
                            {{ $item['qty'] ?? 1 }} Tiket
                        @endif
                    </span>
                </div>
            @endforeach
        </div>

    </div>

</div>
@endsection
