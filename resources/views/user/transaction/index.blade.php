@extends('layouts.user')

@section('title', 'Pembayaran')

@section('content')

<section class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

    <form method="POST" action="{{ route('user.payment.process', $booking->id) }}">
        @csrf

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-10">

            <a href="{{ route('user.booking', $booking->museum->id) }}"
                class="flex items-center gap-2 text-[#102A43] font-medium hover:text-[#B88A44] transition">
                ← Kembali
            </a>

            <h1 class="text-3xl font-bold text-[#102A43]">
                Pembayaran
            </h1>

            <div></div>
        </div>

        {{-- STEP --}}
        <div class="flex justify-center mb-14">
            <div class="flex items-center gap-8">

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold">
                        ✓
                    </div>
                    <span class="mt-3 text-sm font-semibold text-[#102A43]">
                        PESAN TIKET
                    </span>
                </div>

                <div class="w-28 h-[2px] bg-[#102A43]"></div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full bg-[#B88A44] text-white flex items-center justify-center font-bold">
                        2
                    </div>
                    <span class="mt-3 text-sm font-semibold text-[#B88A44]">
                        PEMBAYARAN
                    </span>
                </div>

                <div class="w-28 h-[2px] bg-[#D9CBB8]"></div>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full border-2 border-[#B88A44] text-[#B88A44] flex items-center justify-center font-bold">
                        3
                    </div>
                    <span class="mt-3 text-sm font-semibold text-slate-500">
                        DETAIL TRANSAKSI
                    </span>
                </div>

            </div>
        </div>

        <div class="grid lg:grid-cols-[1fr_420px] gap-10">

            {{-- LEFT --}}
            <div class="space-y-6">

                <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8 shadow-sm">

                    <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                        Pilih Metode Pembayaran
                    </h2>

                    <div class="space-y-4">

                        <label class="flex items-center justify-between p-5 rounded-2xl border border-[#EADBC8] cursor-pointer">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="qris" checked>
                                <span class="font-semibold text-[#102A43]">QRIS</span>
                            </div>
                            <span class="text-[#B88A44] font-bold">Cepat</span>
                        </label>

                        <label class="flex items-center justify-between p-5 rounded-2xl border border-[#EADBC8] cursor-pointer">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <span class="font-semibold text-[#102A43]">Transfer Bank</span>
                            </div>
                            <span class="text-slate-400">BCA</span>
                        </label>

                        <label class="flex items-center justify-between p-5 rounded-2xl border border-[#EADBC8] cursor-pointer">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="e_wallet">
                                <span class="font-semibold text-[#102A43]">E-Wallet</span>
                            </div>
                            <span class="text-slate-400">OVO / DANA / GoPay</span>
                        </label>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div>

                @php
                    $ticketSummary = json_decode($booking->ticket_summary ?? '[]', true);

                    $serviceFee = 2000;

                    $subtotal = collect($ticketSummary)->sum(function ($item) {
                        return $item['qty'] * $item['price'];
                    });

                    // fallback kalau ticket_summary kosong
                    if ($subtotal == 0) {
                        $subtotal = $booking->total_price;
                    }

                    $total = $subtotal + $serviceFee;
                @endphp

                <div class="sticky top-28 bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-xl">

                    <h2 class="text-3xl font-bold text-[#102A43] mb-8">
                        Ringkasan Pesanan
                    </h2>

                    <div class="space-y-5">

                        <div>
                            <h3 class="font-bold text-2xl text-[#102A43]">
                                {{ $booking->museum->name }}
                            </h3>

                            <p class="text-slate-500 mt-2">
                                {{ \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') }}
                            </p>
                        </div>

                        <div class="border-t pt-5 space-y-3">

                            @forelse($ticketSummary as $ticket)
                                <div class="flex justify-between items-center">

                                    <div>
                                        <p class="font-semibold text-[#102A43]">
                                            {{ $ticket['ticket_name'] }} x {{ $ticket['qty'] }}
                                        </p>

                                        <p class="text-sm text-slate-400">
                                            Rp {{ number_format($ticket['price'], 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <span class="font-semibold text-[#102A43]">
                                        Rp {{ number_format($ticket['qty'] * $ticket['price'], 0, ',', '.') }}
                                    </span>

                                </div>
                            @empty
                                <div class="flex justify-between">
                                    <span>Tiket Museum</span>
                                    <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            @endforelse

                        </div>

                        <div class="border-t pt-5 flex justify-between">
                            <span>Biaya Layanan</span>
                            <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                        </div>

                        <div class="border-t pt-5 flex justify-between items-center">

                            <span class="font-bold text-2xl text-[#102A43]">
                                Total Bayar
                            </span>

                            <span class="text-4xl font-bold text-[#B88A44]">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>

                        </div>

                    </div>

                    <button type="submit"
                        class="mt-8 w-full py-5 rounded-2xl bg-[#102A43] text-white font-semibold text-lg hover:bg-[#0d2238] transition">
                        BAYAR SEKARANG
                    </button>

                </div>

            </div>

        </div>

    </form>

</section>

@endsection
