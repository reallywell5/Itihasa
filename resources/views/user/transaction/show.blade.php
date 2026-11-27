@extends('layouts.user')

@section('title', $transaction?->booking?->museum?->name ?? 'Detail Transaksi')

@section('content')

@php
    $museum = $transaction->booking->museum;
@endphp

<section class="max-w-5xl mx-auto py-4 sm:py-8">

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-8 items-start">

        {{-- LEFT --}}
        <div>
            {{-- IMAGE --}}
            <div class="relative h-56 sm:h-72 w-full overflow-hidden rounded-sm border border-neutral-200 bg-neutral-100 mb-6">
                <img src="{{ $museum->image
                    ? asset('storage/' . $museum->image)
                    : asset('images/institutional/hero-museum.jpg') }}"
                     alt="{{ $museum->name }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- DETAIL --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8 mb-6">
                <div class="pb-5 mb-5 border-b border-neutral-200">
                    <span class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold">
                        Detail Transaksi
                    </span>
                    <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[#1A1A1A] mt-1">
                        {{ $museum->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-[#5A5A5A] mt-1">
                        {{ $museum->address }}
                    </p>
                </div>

                {{-- TRANSACTION INFO --}}
                <div class="space-y-3 text-xs sm:text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Kode Invoice</span>
                        <span class="font-mono font-semibold text-[#1A1A1A]">
                            {{ $transaction->invoice_code }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Tanggal Kunjungan</span>
                        <span class="font-semibold text-[#1A1A1A]">
                            {{ $transaction->booking->visit_date ? \Carbon\Carbon::parse($transaction->booking->visit_date)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Metode Pembayaran</span>
                        <span class="font-semibold text-[#1A1A1A] capitalize">
                            {{ str_replace('_', ' ', $transaction->payment_method) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Status Pembayaran</span>
                        <span class="px-2.5 py-0.5 rounded-sm text-[10px] font-semibold uppercase tracking-wider
                            {{ $transaction->payment_status == 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($transaction->payment_status == 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-rose-50 text-[#8C3B2B] border border-rose-200') }}">
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    </div>

                    @if ($transaction->booking->is_rombongan)
                        <div class="flex justify-between items-center py-2 border-b border-neutral-100 text-[#5A5A5A]">
                            <span>Tipe Booking</span>
                            <span class="px-2.5 py-0.5 rounded-sm text-[10px] font-semibold bg-[#F8F7F4] text-[#1A1A1A] border border-neutral-200">
                                Rombongan ({{ $transaction->booking->jumlah_anggota }} orang)
                            </span>
                        </div>
                    @endif

                    <div class="pt-3">
                        <p class="text-[10px] uppercase tracking-wider font-semibold text-[#B8860B] mb-2">Rincian Pembelian Tiket</p>
                        <div class="space-y-1.5 mb-4 bg-[#F8F7F4] p-4 rounded-sm border border-neutral-200 text-xs">
                            @foreach($transaction->booking->ticket_items ?? [] as $item)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-semibold text-[#1A1A1A]">{{ $item['ticket_name'] ?? 'Tiket' }}</span>
                                        <span class="text-[#5A5A5A]"> x{{ $item['qty'] ?? 1 }}</span>
                                    </div>
                                    <span class="font-semibold text-[#1A1A1A]">
                                        @if(($item['subtotal'] ?? 0) > 0)
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        @else
                                            {{ $item['qty'] ?? 1 }} tiket
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-between items-baseline pt-3 border-t border-neutral-200">
                            <span class="text-[#1A1A1A] font-semibold text-sm">Total Pembayaran</span>
                            <span class="font-serif font-semibold text-[#B8860B] text-xl">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MANIFES ROMBONGAN --}}
            @if ($transaction->booking->is_rombongan && !empty($transaction->booking->manifest))
                <div class="bg-white rounded-sm border border-neutral-200 p-6 mb-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-neutral-200">
                        <h2 class="font-serif text-lg font-semibold text-[#1A1A1A]">
                            Manifes Rombongan
                        </h2>
                        <span class="text-xs font-semibold text-[#B8860B]">
                            {{ count($transaction->booking->manifest) }} anggota
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        @foreach ($transaction->booking->manifest as $index => $nama)
                            <div class="flex items-center gap-2.5 py-2 px-3 bg-[#F8F7F4] border border-neutral-200 rounded-sm">
                                <span class="font-semibold text-[#B8860B] w-5 shrink-0">{{ $index + 1 }}.</span>
                                <span class="text-[#1A1A1A] truncate">{{ $nama }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT ACTIONS --}}
        <div>
            <div class="sticky top-28 bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6">
                <h2 class="font-serif text-xl font-semibold text-[#1A1A1A] mb-4 pb-3 border-b border-neutral-200">
                    Aksi Tiket
                </h2>

                <a href="{{ route('user.ticket', $transaction->id) }}"
                   class="w-full flex justify-center py-3 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300">
                    Lihat QR Ticket
                </a>

                <a href="{{ route('user.profile') }}"
                   class="mt-2.5 w-full flex justify-center py-3 rounded-sm border border-neutral-300 text-[#1A1A1A] font-semibold text-xs uppercase tracking-wider hover:bg-white transition duration-300">
                    Kembali ke Profil
                </a>
            </div>
        </div>

    </div>

</section>

@endsection
