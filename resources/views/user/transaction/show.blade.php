@extends('layouts.user')

@section('title', $transaction?->booking?->museum?->name ?? 'Detail Transaksi')

@section('content')

@php
    $museum = $transaction->booking->museum;
@endphp

<section class="max-w-5xl mx-auto py-6 sm:py-10">

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-6 lg:gap-8 items-start">

        {{-- LEFT --}}
        <div>
            {{-- IMAGE --}}
            <div class="relative h-52 sm:h-72 w-full overflow-hidden rounded-2xl border border-[#EADBC8] shadow-sm mb-4">
                <img src="{{ $museum->image
                    ? asset('storage/' . $museum->image)
                    : asset('images/default-museum.jpg') }}"
                     alt="{{ $museum->name }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- DETAIL --}}
            <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm mb-6">
                <div class="mb-4">
                    <span class="text-[10px] uppercase tracking-wider text-[#B88A44] font-bold">
                        Detail Transaksi
                    </span>
                    <h1 class="text-xl sm:text-2xl font-bold text-[#102A43] mt-0.5">
                        {{ $museum->name }}
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $museum->address }}
                    </p>
                </div>

                {{-- TRANSACTION INFO --}}
                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Kode Invoice</span>
                        <span class="font-mono font-bold text-[#102A43]">
                            {{ $transaction->invoice_code }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Tanggal Kunjungan</span>
                        <span class="font-bold text-[#102A43]">
                            {{ $transaction->booking->visit_date ? \Carbon\Carbon::parse($transaction->booking->visit_date)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Metode Pembayaran</span>
                        <span class="font-bold text-[#102A43] capitalize">
                            {{ str_replace('_', ' ', $transaction->payment_method) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Status Pembayaran</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold
                            {{ $transaction->payment_status == 'paid' ? 'bg-emerald-100 text-emerald-800' : ($transaction->payment_status == 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    </div>

                    @if ($transaction->booking->is_rombongan)
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                            <span>Tipe Booking</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                Rombongan ({{ $transaction->booking->jumlah_anggota }} orang)
                            </span>
                        </div>
                    @endif

                    <div class="pt-2">
                        <p class="text-[10px] uppercase font-bold text-[#B88A44] mb-2">Rincian Pembelian Tiket</p>
                        <div class="space-y-1.5 mb-3 bg-[#F9F7F2] p-3 rounded-xl border border-[#EADBC8]">
                            @foreach($transaction->booking->ticket_items ?? [] as $item)
                                <div class="flex justify-between items-center text-xs">
                                    <div>
                                        <span class="font-semibold text-[#102A43]">{{ $item['ticket_name'] ?? 'Tiket' }}</span>
                                        <span class="text-slate-400 font-normal"> x{{ $item['qty'] ?? 1 }}</span>
                                    </div>
                                    <span class="font-bold text-[#102A43]">
                                        @if(($item['subtotal'] ?? 0) > 0)
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        @else
                                            {{ $item['qty'] ?? 1 }} tiket
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                            <span class="text-slate-600 font-semibold">Total Pembayaran</span>
                            <span class="font-extrabold text-[#B88A44] text-base">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MANIFES ROMBONGAN --}}
            @if ($transaction->booking->is_rombongan && !empty($transaction->booking->manifest))
                <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-bold text-[#102A43]">
                            Manifes Rombongan
                        </h2>
                        <span class="text-xs font-semibold text-[#B88A44]">
                            {{ count($transaction->booking->manifest) }} anggota
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        @foreach ($transaction->booking->manifest as $index => $nama)
                            <div class="flex items-center gap-2 py-1.5 px-2 bg-slate-50 rounded-lg">
                                <span class="w-5 h-5 shrink-0 rounded-full bg-[#F6F1E8] text-[#B88A44] text-[10px] font-bold flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-slate-700 truncate">{{ $nama }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT --}}
        <div>
            <div class="sticky top-28 space-y-4">
                <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm">
                    <h2 class="text-sm font-bold text-[#102A43] mb-3">
                        Aksi Tiket
                    </h2>

                    <a href="{{ route('user.ticket', $transaction->id) }}"
                       class="w-full flex justify-center py-2.5 rounded-xl bg-[#102A43] text-white font-bold text-xs hover:bg-[#0c2238] transition shadow">
                        Lihat QR Ticket
                    </a>

                    <a href="{{ route('user.profile') }}"
                       class="mt-2 w-full flex justify-center py-2.5 rounded-xl border border-[#EADBC8] text-slate-600 font-semibold text-xs hover:bg-slate-50 transition">
                        Kembali ke Profil
                    </a>
                </div>
            </div>
        </div>

    </div>

</section>

@endsection
