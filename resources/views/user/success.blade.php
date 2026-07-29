@extends('layouts.user')

@section('title', 'Tiket Berhasil')

@section('content')

<section class="max-w-6xl mx-auto px-6 lg:px-8 py-14">

    {{-- HEADER --}}
    <div class="text-center mb-12">

        <div class="w-24 h-24 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-6 border border-green-100">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <p class="text-sm uppercase tracking-[0.25em] text-[#B88A44] font-bold mb-3">
            Pembayaran Berhasil
        </p>

        <h1 class="text-5xl font-bold text-[#102A43]">
            Tiket Kamu Sudah Siap
        </h1>

        <p class="text-slate-500 mt-4 max-w-xl mx-auto">
            Tiket berhasil dibuat. Simpan QR Code ini dan tunjukkan kepada petugas saat masuk museum.
        </p>

    </div>

    <div class="grid lg:grid-cols-2 gap-10">

        {{-- QR CODE --}}
        <div class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm">

            <div class="text-center">

                <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                    QR Tiket
                </h2>

                <div class="w-72 h-72 mx-auto rounded-3xl bg-[#F9F7F2] border border-[#EADBC8] flex items-center justify-center shadow-inner">

                    {!! QrCode::size(220)->generate($transaction->invoice_code) !!}

                </div>

                <p class="mt-6 text-sm text-slate-500">
                    Kode Tiket:
                </p>

                <h3 class="text-xl font-bold text-[#B88A44] mt-2 tracking-wider">
                    {{ $transaction->invoice_code }}
                </h3>

            </div>

        </div>

        {{-- DETAIL TIKET --}}
        <div class="space-y-6">

            <div class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                    Detail Tiket
                </h2>

                <div class="space-y-5">

                    <div class="flex justify-between">
                        <span class="text-slate-500">Museum</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->booking->museum->name }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Pengunjung</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->booking->user->name }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Tanggal Transaksi</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Status</span>
                        <span class="font-semibold
                            {{ $transaction->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    </div>

                    <div class="border-t border-[#EADBC8] pt-4 space-y-2">
                        <p class="text-xs uppercase font-bold text-slate-400 mb-2">Rincian Tiket Dibeli</p>
                        @foreach($transaction->booking->ticket_items as $item)
                            <div class="flex justify-between items-center text-sm">
                                <div>
                                    <span class="font-semibold text-[#102A43]">{{ $item['ticket_name'] }}</span>
                                    <span class="text-slate-400"> x {{ $item['qty'] }}</span>
                                    @if($item['price'] > 0)
                                        <span class="text-xs text-slate-400">(@ Rp {{ number_format($item['price'], 0, ',', '.') }})</span>
                                    @endif
                                </div>
                                <span class="font-semibold text-[#102A43]">
                                    @if($item['subtotal'] > 0)
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    @else
                                        {{ $item['qty'] }} Qty
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-[#EADBC8] pt-4 flex justify-between items-center">
                        <span class="font-bold text-[#102A43]">
                            Total Dibayar
                        </span>

                        <span class="text-2xl font-bold text-[#B88A44]">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="grid grid-cols-2 gap-4">

                <a href="{{ route('user.ticket', $transaction->id) }}"
                   class="py-4 rounded-2xl bg-[#102A43] text-white font-semibold text-center hover:bg-[#0c2238] transition shadow-md">
                    Lihat Tiket
                </a>

                <a href="{{ route('user.home') }}"
                   class="py-4 rounded-2xl border border-[#B88A44] text-[#B88A44] font-semibold text-center hover:bg-[#F6F1E8] transition">
                    Kembali
                </a>

            </div>

        </div>

    </div>

</section>

@endsection
