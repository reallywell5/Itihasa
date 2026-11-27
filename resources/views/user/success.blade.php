@extends('layouts.user')

@section('title', 'Tiket Berhasil')

@section('content')

<section class="max-w-4xl mx-auto py-4 sm:py-8">

    {{-- HEADER --}}
    <div class="text-center pb-8 mb-8 border-b border-neutral-200">
        <div class="w-14 h-14 rounded-sm bg-emerald-50 flex items-center justify-center mx-auto mb-4 border border-emerald-200">
            <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <span class="inline-block text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-700 mb-2">
            Pembayaran Terverifikasi
        </span>

        <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[#1A1A1A] tracking-tight">
            Tiket Digital Anda Sudah Siap
        </h1>

        <p class="text-xs sm:text-sm text-[#5A5A5A] mt-2 max-w-md mx-auto leading-relaxed">
            Simpan QR Code di bawah ini dan tunjukkan kepada petugas loket saat tiba di gerbang museum.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[340px_1fr] gap-8 items-start">

        {{-- QR CODE BOX --}}
        <div class="bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6 text-center">
            <h2 class="text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[0.2em] mb-4">
                QR Tiket Masuk
            </h2>

            <div class="w-56 h-56 mx-auto rounded-sm bg-white border border-neutral-200 flex items-center justify-center p-3">
                {!! QrCode::size(190)->generate($transaction->invoice_code) !!}
            </div>

            <p class="mt-4 text-[10px] text-[#5A5A5A] font-semibold uppercase tracking-widest">
                Kode Invoice
            </p>

            <h3 class="text-sm font-bold font-mono text-[#1A1A1A] mt-1">
                {{ $transaction->invoice_code }}
            </h3>
        </div>

        {{-- DETAIL TIKET --}}
        <div class="space-y-5">
            <div class="bg-white rounded-sm border border-neutral-200 p-6">
                <h2 class="font-serif text-xl font-semibold text-[#1A1A1A] mb-4 pb-3 border-b border-neutral-200">
                    Ringkasan Transaksi
                </h2>

                <div class="space-y-2.5 text-xs sm:text-sm">
                    <div class="flex justify-between items-center py-1.5 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Museum Tujuan</span>
                        <span class="font-semibold text-[#1A1A1A]">{{ $transaction->booking->museum->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Nama Pengunjung</span>
                        <span class="font-semibold text-[#1A1A1A]">{{ $transaction->booking->user->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Waktu Pembayaran</span>
                        <span class="font-semibold text-[#1A1A1A]">{{ $transaction->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Status</span>
                        <span class="px-2.5 py-0.5 rounded-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] uppercase tracking-wider">
                            Lunas &amp; Aktif
                        </span>
                    </div>

                    <div class="pt-2 border-b border-neutral-100 pb-3 space-y-1.5 text-xs">
                        <p class="text-[10px] uppercase tracking-wider font-semibold text-[#B8860B]">Rincian Tiket</p>
                        @foreach($transaction->booking->ticket_items ?? [] as $item)
                            <div class="flex justify-between text-[#1A1A1A]">
                                <span>{{ $item['ticket_name'] ?? 'Tiket' }} x{{ $item['qty'] ?? 1 }}</span>
                                <span class="font-semibold">
                                    @if(($item['subtotal'] ?? 0) > 0)
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    @else
                                        {{ $item['qty'] ?? 1 }} Qty
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 flex justify-between items-baseline">
                        <span class="font-semibold text-[#1A1A1A]">Total Dibayar</span>
                        <span class="font-serif font-semibold text-[#B8860B] text-xl">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('user.ticket', $transaction->id) }}"
                   class="py-3 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider text-center transition duration-300">
                    Lihat Tiket QR
                </a>

                <a href="{{ route('user.home') }}"
                   class="py-3 rounded-sm border border-neutral-300 text-[#1A1A1A] font-semibold text-xs uppercase tracking-wider text-center hover:bg-[#F8F7F4] transition duration-300">
                    Kembali Beranda
                </a>
            </div>
        </div>

    </div>

</section>

@endsection
