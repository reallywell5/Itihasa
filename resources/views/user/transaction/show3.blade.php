@extends('layouts.user')

@section('title', 'Detail Pembayaran')

@section('content')

<section class="max-w-4xl mx-auto px-6 lg:px-8 py-14">

    <div class="bg-white rounded-[32px] border border-[#EADBC8] shadow-sm p-8">

        <div class="text-center mb-10">

            <h1 class="text-4xl font-bold text-[#102A43] mb-4">
                Selesaikan Pembayaran
            </h1>

            <p class="text-slate-500">
                Pilih dan selesaikan pembayaran sesuai metode yang dipilih.
            </p>

        </div>

        {{-- STATUS --}}
        <div class="mb-8 text-center">

            @if($transaction->payment_status == 'pending')
                <span class="px-4 py-2 rounded-xl bg-yellow-100 text-yellow-700 text-sm font-semibold">
                    Menunggu Pembayaran
                </span>
            @elseif($transaction->payment_status == 'paid')
                <span class="px-4 py-2 rounded-xl bg-green-100 text-green-700 text-sm font-semibold">
                    Pembayaran Berhasil
                </span>
            @endif

        </div>

        {{-- QRIS --}}
        @if($transaction->payment_method == 'qris')
            <div class="text-center">

                <h2 class="text-2xl font-bold mb-6">
                    Scan QRIS
                </h2>

                <img src="{{ asset('images/qris.png') }}"
                     class="w-72 mx-auto mb-6 rounded-2xl shadow">

                <p class="text-slate-500 text-sm">
                    Scan QRIS menggunakan aplikasi mobile banking atau e-wallet.
                </p>

            </div>
        @endif

        {{-- BANK TRANSFER --}}
        @if($transaction->payment_method == 'bank_transfer')
            <div class="text-center">

                <h2 class="text-2xl font-bold mb-6">
                    Transfer ke Virtual Account
                </h2>

                <div class="bg-[#F9F7F2] rounded-2xl p-6 inline-block">
                    <p class="text-slate-500 mb-2">
                        BCA Virtual Account
                    </p>

                    <h3 class="text-3xl font-bold text-[#102A43]">
                        8808{{ str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}
                    </h3>

                    <p class="text-sm text-slate-400 mt-3">
                        A/N ITIHASA
                    </p>
                </div>

            </div>
        @endif

        {{-- E-WALLET --}}
        @if($transaction->payment_method == 'e_wallet')
            <div class="text-center">

                <h2 class="text-2xl font-bold mb-6">
                    Transfer ke E-Wallet
                </h2>

                <div class="bg-[#F9F7F2] rounded-2xl p-6 inline-block">

                    <p class="text-slate-500 mb-2">
                        DANA / OVO / GoPay
                    </p>

                    <h3 class="text-3xl font-bold text-[#102A43]">
                        081234567890
                    </h3>

                    <p class="text-sm text-slate-400 mt-3">
                        A/N ITIHASA
                    </p>

                </div>

            </div>
        @endif

        {{-- INVOICE --}}
        <div class="mt-10 text-center">

            <p class="text-slate-500">
                Invoice Code
            </p>

            <h3 class="text-xl font-bold text-[#102A43] mt-2">
                {{ $transaction->invoice_code }}
            </h3>

        </div>

        {{-- TOTAL --}}
        <div class="mt-8 text-center">

            <p class="text-slate-500">
                Total Pembayaran
            </p>

            <h2 class="text-4xl font-bold text-[#B88A44] mt-2">
                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
            </h2>

        </div>

        {{-- BUTTON --}}
        @if($transaction->payment_status == 'pending')
            <form action="{{ route('user.payment.confirm', $transaction->id) }}"
                  method="POST"
                  class="mt-10">
                @csrf

                <button
                    class="w-full py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">

                    Saya Sudah Bayar

                </button>
            </form>
        @endif

    </div>

</section>

@endsection
