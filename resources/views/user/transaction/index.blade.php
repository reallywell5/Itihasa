@extends('layouts.user')

@section('title', 'Pembayaran')

@section('content')

<section class="py-4 sm:py-8">

    <form method="POST" action="{{ route('user.payment.process', $booking->id) }}">
        @csrf

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-6 mb-8 border-b border-neutral-200">
            <div>
                <a href="{{ route('user.booking', $booking->museum->id) }}"
                   class="inline-flex items-center gap-1.5 text-xs text-[#5A5A5A] font-semibold uppercase tracking-wider hover:text-[#1A1A1A] transition mb-3">
                    <span>←</span>
                    <span>Kembali ke Jadwal</span>
                </a>
                <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[#1A1A1A] tracking-tight">
                    Metode Pembayaran
                </h1>
            </div>

            <p class="text-xs font-medium text-[#B8860B] uppercase tracking-[0.2em]">
                Langkah 2 dari 3
            </p>
        </div>

        {{-- STEPPER --}}
        <div class="flex justify-center mb-8">
            <div class="flex items-center gap-3 sm:gap-5 w-full max-w-xl">
                <div class="flex flex-col items-center flex-1">
                    <div class="w-9 h-9 rounded-sm bg-[#F8F7F4] border border-[#1A1A1A] text-[#1A1A1A] flex items-center justify-center font-semibold text-xs">
                        ✓
                    </div>
                    <span class="mt-2 text-[11px] font-medium uppercase tracking-wider text-[#5A5A5A] text-center">
                        Jadwal &amp; Tiket
                    </span>
                </div>

                <div class="flex-1 h-[1px] bg-[#1A1A1A]"></div>

                <div class="flex flex-col items-center flex-1">
                    <div class="w-9 h-9 rounded-sm bg-[#1A1A1A] text-white flex items-center justify-center font-semibold text-xs">
                        2
                    </div>
                    <span class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-[#1A1A1A] text-center">
                        Pembayaran
                    </span>
                </div>

                <div class="flex-1 h-[1px] bg-neutral-300"></div>

                <div class="flex flex-col items-center flex-1">
                    <div class="w-9 h-9 rounded-sm border border-neutral-300 text-[#5A5A5A] flex items-center justify-center font-semibold text-xs bg-[#F8F7F4]">
                        3
                    </div>
                    <span class="mt-2 text-[11px] font-medium uppercase tracking-wider text-[#5A5A5A] text-center">
                        E-Tiket QR
                    </span>
                </div>
            </div>
        </div>

        {{-- MIDTRANS BADGE --}}
        <div class="flex justify-center mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs">
                <span class="text-[#5A5A5A]">Pembayaran diproses aman melalui</span>
                <span class="font-semibold text-[#1A1A1A]">Midtrans</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-8 items-start">

            {{-- LEFT --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8">
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-neutral-200">
                    <h2 class="font-serif text-xl sm:text-2xl font-semibold text-[#1A1A1A]">
                        Pilih Metode Pembayaran
                    </h2>
                    <span class="text-[11px] uppercase tracking-wider font-semibold text-[#5A5A5A]">
                        Midtrans Gateway
                    </span>
                </div>

                <div class="space-y-3.5">
                    <label class="flex items-center justify-between p-4 sm:p-5 rounded-sm border border-neutral-200 hover:border-[#B8860B] cursor-pointer transition duration-200 bg-[#F8F7F4]/50 has-[:checked]:bg-white has-[:checked]:border-[#1A1A1A]">
                        <div class="flex items-center gap-3.5">
                            <input type="radio" name="payment_method" value="qris" checked class="accent-[#1A1A1A]">
                            <div>
                                <span class="font-semibold text-sm text-[#1A1A1A] block">QRIS</span>
                                <span class="text-xs text-[#5A5A5A]">GoPay, OVO, DANA, ShopeePay, BCA &amp; Bank Lainnya</span>
                            </div>
                        </div>
                        <span class="text-[11px] uppercase tracking-wider text-[#B8860B] font-semibold shrink-0">Instan</span>
                    </label>

                    <label class="flex items-center justify-between p-4 sm:p-5 rounded-sm border border-neutral-200 hover:border-[#B8860B] cursor-pointer transition duration-200 bg-[#F8F7F4]/50 has-[:checked]:bg-white has-[:checked]:border-[#1A1A1A]">
                        <div class="flex items-center gap-3.5">
                            <input type="radio" name="payment_method" value="bank_transfer" class="accent-[#1A1A1A]">
                            <div>
                                <span class="font-semibold text-sm text-[#1A1A1A] block">Transfer Bank (Virtual Account)</span>
                                <span class="text-xs text-[#5A5A5A]">BCA, Mandiri, BNI, BRI Virtual Account</span>
                            </div>
                        </div>
                        <span class="text-xs text-[#5A5A5A] shrink-0">Midtrans VA</span>
                    </label>

                    <label class="flex items-center justify-between p-4 sm:p-5 rounded-sm border border-neutral-200 hover:border-[#B8860B] cursor-pointer transition duration-200 bg-[#F8F7F4]/50 has-[:checked]:bg-white has-[:checked]:border-[#1A1A1A]">
                        <div class="flex items-center gap-3.5">
                            <input type="radio" name="payment_method" value="e_wallet" class="accent-[#1A1A1A]">
                            <div>
                                <span class="font-semibold text-sm text-[#1A1A1A] block">E-Wallet</span>
                                <span class="text-xs text-[#5A5A5A]">Transfer langsung melalui GoPay atau DANA</span>
                            </div>
                        </div>
                        <span class="text-xs text-[#5A5A5A] shrink-0">Dompet Digital</span>
                    </label>
                </div>
            </div>

            {{-- RIGHT SUMMARY --}}
            <div>
                @php
                    $ticketSummary = json_decode($booking->ticket_summary ?? '[]', true);
                    $subtotal = collect($ticketSummary)->sum(fn ($item) => $item['qty'] * $item['price']);
                    if ($subtotal == 0) {
                        $subtotal = $booking->total_price;
                    }
                    $total = $booking->total_price ?? $subtotal;
                @endphp

                <div class="sticky top-28 bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6 sm:p-7">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">Konfirmasi</p>
                    <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A] mb-5 pb-4 border-b border-neutral-200">
                        Ringkasan Pesanan
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <h3 class="font-serif font-semibold text-lg text-[#1A1A1A]">
                                {{ $booking->museum->name }}
                            </h3>
                            <p class="text-xs text-[#5A5A5A] mt-1">
                                Tanggal: {{ \Carbon\Carbon::parse($booking->visit_date)->translatedFormat('d M Y') }}
                            </p>
                        </div>

                        <div class="border-t border-neutral-200 pt-4 space-y-2.5 text-xs">
                            @forelse($ticketSummary as $ticket)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold text-[#1A1A1A]">
                                            {{ $ticket['ticket_name'] }} x {{ $ticket['qty'] }}
                                        </p>
                                        <p class="text-[11px] text-[#5A5A5A]">
                                            Rp {{ number_format($ticket['price'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <span class="font-semibold text-[#1A1A1A]">
                                        Rp {{ number_format($ticket['qty'] * $ticket['price'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @empty
                                <div class="flex justify-between">
                                    <span>Tiket Museum</span>
                                    <span class="font-semibold text-[#1A1A1A]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            @endforelse
                        </div>

                        <div class="border-t border-neutral-200 pt-4 flex justify-between items-baseline">
                            <span class="font-semibold text-sm text-[#1A1A1A]">
                                Total Bayar
                            </span>
                            <span class="font-serif text-2xl font-semibold text-[#B8860B]">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button type="submit"
                            class="mt-6 w-full py-3.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300">
                        Bayar Sekarang
                    </button>

                    <p class="text-center text-[11px] text-[#5A5A5A] mt-3">
                        Transaksi diamankan oleh Midtrans Payment Gateway
                    </p>
                </div>
            </div>

        </div>

    </form>

</section>

@endsection
