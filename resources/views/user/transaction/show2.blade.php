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
                    {{ $transaction->booking->museum->name }}
                </div>

            </div>

            <div class="mt-6 flex flex-col items-center gap-4">

                <a href="{{ route('user.ticket.download',$transaction->id) }}"
                class="inline-flex items-center gap-2 bg-[#4E342E] hover:bg-[#3A2722] text-white px-6 py-3.5 rounded-2xl shadow-md transition duration-300 font-semibold text-sm">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4"/>

                    </svg>

                    Unduh Tiket Digital (Gambar)

                </a>

                {{-- PETUNJUK PENGGUNAAN TIKET GAMBAR --}}
                <div class="p-4 rounded-2xl bg-[#F9F7F2] border border-[#EADBC8] text-center text-xs text-slate-600 space-y-1.5 w-full">
                    <p class="font-bold text-[#102A43] flex items-center justify-center gap-1 text-sm">
                        📸 Petunjuk Penggunaan Tiket Gambar
                    </p>
                    <p class="leading-relaxed">
                        Klik tombol <strong>Unduh Tiket Digital</strong> di atas untuk menyimpan file gambar tiket ke galeri perangkat Anda. Tunjukkan QR Code pada gambar tersebut kepada petugas saat tiba di lokasi museum.
                    </p>
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
                            {{ $transaction->booking->user->name }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Museum</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->booking->museum->name }}
                        </span>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Tanggal Transaksi</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transaction->created_at->format('d F Y') }}
                        </span>
                    </div>

                    <div class="border-b pb-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Rincian Tiket</span>
                            <span class="text-xs uppercase font-bold text-[#B88A44]">Pembelian Real-Time</span>
                        </div>
                        <div class="bg-[#F9F7F2] p-4 rounded-2xl border border-[#EADBC8] space-y-2">
                            @foreach($transaction->booking->ticket_items as $item)
                                <div class="flex justify-between items-center text-sm">
                                    <div>
                                        <span class="font-semibold text-[#102A43]">{{ $item['ticket_name'] }}</span>
                                        <span class="text-slate-500"> x {{ $item['qty'] }}</span>
                                        @if($item['price'] > 0)
                                            <span class="text-xs text-slate-400">(@ Rp {{ number_format($item['price'], 0, ',', '.') }})</span>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-[#102A43]">
                                        @if($item['subtotal'] > 0)
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        @else
                                            {{ $item['qty'] }} Tiket
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between border-b pb-4">
                        <span class="text-slate-500">Total Pembayaran</span>
                        <span class="font-bold text-[#B88A44] text-lg">
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
