@extends('layouts.user')

@section('title', $transaction?->booking?->museum?->name ?? 'Detail Transaksi')

@section('content')

@php
    $museum = $transaction->booking->museum;
@endphp

<section class="max-w-7xl mx-auto px-6 lg:px-8 py-14">

    <div class="grid lg:grid-cols-[1fr_380px] gap-10">

        {{-- LEFT --}}
        <div>

            {{-- IMAGE --}}
            <img src="{{ $museum->image
                ? asset('storage/' . $museum->image)
                : asset('images/default-museum.jpg') }}"
                 alt="{{ $museum->name }}"
                 class="w-full h-[500px] object-cover rounded-[32px] border border-[#EADBC8] shadow-xl mb-8">

            {{-- DETAIL --}}
            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8 shadow-sm mb-10">

                <div class="mb-6">
                    <p class="uppercase text-xs tracking-[0.35em] text-[#B88A44] font-bold mb-4">
                        Detail Transaksi
                    </p>

                    <h1 class="text-4xl font-bold text-[#102A43] mb-3">
                        {{ $museum->name }}
                    </h1>

                    <p class="text-slate-500">
                        {{ $museum->address }}
                    </p>
                </div>

                {{-- TRANSACTION INFO --}}
                <div class="space-y-5">

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Kode Invoice</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->invoice_code }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Tanggal Kunjungan</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->booking->visit_date }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Metode Pembayaran</span>
                        <span class="font-semibold text-[#102A43] capitalize">
                            {{ str_replace('_', ' ', $transaction->payment_method) }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Status Pembayaran</span>
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-sm font-semibold">
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Total Bayar</span>
                        <span class="font-bold text-[#B88A44] text-xl">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            <div class="sticky top-28 space-y-6">

                {{-- TICKET SUMMARY --}}
                <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8 shadow-sm">

                    <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                        Ringkasan Tiket
                    </h2>

                    <div class="space-y-4">

                        @if($transaction->booking->adult_qty > 0)
                        <div class="flex justify-between">
                            <span>Dewasa</span>
                            <span>{{ $transaction->booking->adult_qty }}</span>
                        </div>
                        @endif

                        @if($transaction->booking->student_qty > 0)
                        <div class="flex justify-between">
                            <span>Pelajar</span>
                            <span>{{ $transaction->booking->student_qty }}</span>
                        </div>
                        @endif

                        @if($transaction->booking->child_qty > 0)
                        <div class="flex justify-between">
                            <span>Anak-anak</span>
                            <span>{{ $transaction->booking->child_qty }}</span>
                        </div>
                        @endif

                    </div>

                    <a href="{{ route('user.ticket', $transaction->id) }}"
                       class="mt-8 w-full flex justify-center py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                        Lihat QR Ticket
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
