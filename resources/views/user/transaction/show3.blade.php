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

        {{-- QRIS --}}
        @if($transaction->payment_method == 'qris')
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-6">Scan QRIS</h2>

                <img src="{{ asset('images/qris.png') }}"
                     class="w-72 mx-auto mb-8">
            </div>
        @endif

        {{-- BANK --}}
        @if($transaction->payment_method == 'bank_transfer')
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-6">Transfer ke Virtual Account</h2>

                <div class="bg-[#F9F7F2] rounded-2xl p-6 inline-block">
                    <p class="text-slate-500 mb-2">BCA Virtual Account</p>
                    <h3 class="text-3xl font-bold text-[#102A43]">
                        8808{{ rand(10000000,99999999) }}
                    </h3>
                </div>
            </div>
        @endif

        {{-- EWALLET --}}
        @if($transaction->payment_method == 'e_wallet')
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-6">Transfer ke E-Wallet</h2>

                <div class="bg-[#F9F7F2] rounded-2xl p-6 inline-block">
                    <p class="text-slate-500 mb-2">Nomor E-Wallet</p>
                    <h3 class="text-3xl font-bold text-[#102A43]">
                        081234567890
                    </h3>
                </div>
            </div>
        @endif

        {{-- TOTAL --}}
        <div class="mt-10 text-center">
            <p class="text-slate-500">Total Pembayaran</p>

            <h2 class="text-4xl font-bold text-[#B88A44] mt-2">
                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
            </h2>
        </div>

        {{-- BUTTON --}}
        <form action="{{ route('user.payment.confirm', $transaction->id) }}"
              method="POST"
              class="mt-10">
            @csrf

            <button class="w-full py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                Saya Sudah Bayar
            </button>
        </form>

    </div>

</section>

@endsection
