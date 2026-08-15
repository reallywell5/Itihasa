@extends('layouts.user')

@section('title', 'Tiket Berhasil')

@section('content')

<section class="max-w-4xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="text-center mb-6 sm:mb-8">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-3 border border-emerald-200 shadow-sm">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
            ✓ Pembayaran Berhasil
        </span>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#102A43]">
            Tiket Digital Kamu Sudah Siap!
        </h1>

        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
            Transaksi sukses terverifikasi. Simpan QR Code ini dan tunjukkan kepada petugas saat tiba di museum.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[340px_1fr] gap-6 items-start">

        {{-- QR CODE BOX --}}
        <div class="bg-white rounded-2xl border border-[#EADBC8] p-5 text-center shadow-sm">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                QR Tiket Masuk
            </h2>

            <div class="w-56 h-56 mx-auto rounded-2xl bg-[#F9F7F2] border border-[#EADBC8] flex items-center justify-center p-3">
                {!! QrCode::size(190)->generate($transaction->invoice_code) !!}
            </div>

            <p class="mt-3 text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                Kode Invoice:
            </p>

            <h3 class="text-sm font-bold font-mono text-[#102A43] mt-0.5">
                {{ $transaction->invoice_code }}
            </h3>
        </div>

        {{-- DETAIL TIKET --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-5 shadow-sm">
                <h2 class="text-sm font-bold text-[#102A43] mb-3">
                    Ringkasan Transaksi
                </h2>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center py-1 border-b border-slate-100 text-slate-600">
                        <span>Museum Tujuan</span>
                        <span class="font-bold text-[#102A43]">{{ $transaction->booking->museum->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-slate-100 text-slate-600">
                        <span>Nama Pengunjung</span>
                        <span class="font-bold text-[#102A43]">{{ $transaction->booking->user->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-slate-100 text-slate-600">
                        <span>Waktu Pembayaran</span>
                        <span class="font-bold text-[#102A43]">{{ $transaction->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                    </div>

                    <div class="flex justify-between items-center py-1 border-b border-slate-100 text-slate-600">
                        <span>Status</span>
                        <span class="px-2 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-[10px]">
                            Lunas & Aktif
                        </span>
                    </div>

                    <div class="pt-2 border-b border-slate-100 pb-2 space-y-1">
                        <p class="text-[10px] uppercase font-bold text-[#B88A44]">Rincian Tiket:</p>
                        @foreach($transaction->booking->ticket_items ?? [] as $item)
                            <div class="flex justify-between text-slate-700">
                                <span>{{ $item['ticket_name'] ?? 'Tiket' }} x{{ $item['qty'] ?? 1 }}</span>
                                <span class="font-bold text-[#102A43]">
                                    @if(($item['subtotal'] ?? 0) > 0)
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    @else
                                        {{ $item['qty'] ?? 1 }} Qty
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-2 flex justify-between items-center">
                        <span class="font-bold text-slate-700">Total Dibayar</span>
                        <span class="font-extrabold text-[#B88A44] text-base">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('user.ticket', $transaction->id) }}"
                   class="py-2.5 rounded-xl bg-[#102A43] text-white font-bold text-xs text-center hover:bg-[#0c2238] transition shadow">
                    Lihat Tiket QR
                </a>

                <a href="{{ route('user.home') }}"
                   class="py-2.5 rounded-xl border border-[#B88A44] text-[#B88A44] font-bold text-xs text-center hover:bg-[#F6F1E8] transition">
                    Kembali Beranda
                </a>
            </div>
        </div>

    </div>

</section>

@endsection
