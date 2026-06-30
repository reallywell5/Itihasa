@extends('layouts.user')

@section('title', 'Tiket QR')

@section('content')

<section class="max-w-6xl mx-auto px-6 lg:px-8 py-14">

    {{-- HEADER --}}
    <div class="text-center mb-12">

        <p class="uppercase text-xs tracking-[0.35em] text-[#B88A44] font-bold mb-4">
            Tiket Masuk Digital
        </p>

        <h1 class="text-4xl font-bold text-[#102A43] mb-4">
            Tiket Museum Kamu
        </h1>

        <p class="text-slate-500 text-lg">
            Tunjukkan QR Code ini kepada petugas untuk validasi masuk museum.
        </p>

    </div>

    <div class="grid lg:grid-cols-[420px_1fr] gap-10 items-start">

        {{-- QR CARD --}}
        <div class="bg-white rounded-[32px] border border-[#EADBC8] shadow-xl p-8">

            <div class="text-center">

                <div class="w-72 h-72 mx-auto bg-[#F9F7F2] rounded-[28px] border border-[#EADBC8] flex items-center justify-center mb-8">

                    {!! QrCode::size(220)->generate($transaction->invoice_code) !!}

                </div>

                <p class="text-sm text-slate-400 mb-2">
                    Kode Tiket
                </p>

                <h2 class="text-2xl font-bold tracking-[0.2em] text-[#102A43]">
                    {{ $transaction->invoice_code }}
                </h2>

                <div class="mt-6 inline-flex px-4 py-2 rounded-xl bg-[#F9F7F2] border border-[#EADBC8] text-[#B88A44] font-semibold text-sm">
                    {{ $transaction->museum->name }}
                </div>

            </div>

        </div>

        {{-- DETAIL --}}
        <div class="space-y-8">

            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-2xl font-bold text-[#102A43] mb-8">
                    Informasi Pengunjung
                </h2>

                <div class="space-y-6">

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Nama Pengunjung</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->user->name }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Museum</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->museum->name }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Tanggal Transaksi</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d F Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Total Pembayaran</span>
                        <span class="font-semibold text-[#102A43]">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Status Tiket</span>

                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $transaction->used_at ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600' }}">
                            {{ $transaction->used_at ? 'Sudah Digunakan' : 'Masih Berlaku' }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="grid grid-cols-2 gap-4">

                <a href="{{ route('user.profile') }}"
                   class="py-4 rounded-2xl border border-[#B88A44] text-[#B88A44] font-semibold text-center hover:bg-[#F6F1E8] transition">
                    Riwayat Tiket
                </a>

                <a href="{{ route('user.home') }}"
                   class="py-4 rounded-2xl bg-[#102A43] text-white font-semibold text-center hover:bg-[#0c2238] transition">
                    Kembali
                </a>

            </div>

        </div>

    </div>

</section>

@endsection
