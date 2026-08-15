@extends('layouts.user')

@section('title', 'Tiket QR Digital')

@section('content')

<section class="max-w-5xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="text-center mb-6 sm:mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#B88A44]/15 text-[#B88A44] text-xs font-bold uppercase tracking-wider mb-2">
            🎟 Tiket Masuk Digital
        </span>

        <h1 class="text-xl sm:text-2xl font-bold text-[#102A43]">
            Tiket Museum Resmi Kamu
        </h1>

        <p class="text-xs text-slate-500 mt-1">
            Tunjukkan QR Code ini kepada petugas scan gate saat tiba di area museum.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[360px_1fr] gap-6 lg:gap-8 items-start">

        {{-- QR CARD --}}
        <div class="bg-white rounded-2xl border border-[#EADBC8] shadow-sm p-5 sm:p-6 text-center">

            <div class="w-56 h-56 sm:w-64 sm:h-64 mx-auto bg-[#F9F7F2] rounded-2xl border border-[#EADBC8] flex items-center justify-center p-3 mb-4">
                {!! QrCode::size(200)->generate($transaction->invoice_code) !!}
            </div>

            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">
                Kode Invoice Tiket
            </p>

            <h2 class="text-base sm:text-lg font-bold font-mono text-[#102A43]">
                {{ $transaction->invoice_code }}
            </h2>

            <div class="mt-2.5 inline-flex px-3 py-1 rounded-xl bg-[#F9F7F2] border border-[#EADBC8] text-[#B88A44] font-bold text-xs">
                {{ $transaction->booking->museum->name }}
            </div>

            @if ($transaction->booking->is_rombongan)
                <div class="mt-1.5 block">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 font-semibold text-[11px]">
                        Rombongan · {{ $transaction->booking->jumlah_anggota }} orang
                    </span>
                </div>
            @endif

            <div class="mt-5 space-y-2.5">
                <a href="{{ route('user.ticket.download', $transaction->id) }}"
                   class="w-full inline-flex items-center justify-center gap-2 bg-[#102A43] hover:bg-[#0c2238] text-white px-4 py-2.5 rounded-xl shadow transition font-bold text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4"/>
                    </svg>
                    Unduh Tiket Digital (Gambar)
                </a>

                <p class="text-[10px] text-slate-400 leading-relaxed">
                    💡 Gambar tiket bisa disimpan offline di galeri HP tanpa perlu koneksi internet di lokasi.
                </p>
            </div>

        </div>

        {{-- DETAIL & MANIFEST --}}
        <div class="space-y-4 sm:space-y-6">

            <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm">
                <h2 class="text-sm font-bold text-[#102A43] mb-3">
                    Informasi Kunjungan
                </h2>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Nama Pemesan</span>
                        <span class="font-bold text-[#102A43]">{{ $transaction->booking->user->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Museum Tujuan</span>
                        <span class="font-bold text-[#102A43]">{{ $transaction->booking->museum->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Tanggal Kunjungan</span>
                        <span class="font-bold text-[#102A43]">
                            {{ $transaction->booking->visit_date ? \Carbon\Carbon::parse($transaction->booking->visit_date)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>

                    <div class="py-2 border-b border-slate-100 space-y-1.5">
                        <p class="text-[10px] uppercase font-bold text-[#B88A44]">Rincian Varian Tiket</p>
                        <div class="bg-[#F9F7F2] p-3 rounded-xl border border-[#EADBC8] space-y-1">
                            @foreach($transaction->booking->ticket_items ?? [] as $item)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-slate-800">{{ $item['ticket_name'] ?? 'Tiket' }} x{{ $item['qty'] ?? 1 }}</span>
                                    <span class="font-bold text-[#102A43]">
                                        @if(($item['subtotal'] ?? 0) > 0)
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        @else
                                            {{ $item['qty'] ?? 1 }} Tiket
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($transaction->booking->is_rombongan && !empty($transaction->booking->manifest))
                        <div class="py-2 border-b border-slate-100 space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] uppercase font-bold text-[#B88A44]">Manifes Rombongan</span>
                                <span class="text-[10px] font-bold text-slate-500">{{ count($transaction->booking->manifest) }} orang</span>
                            </div>
                            <div class="bg-[#F9F7F2] p-3 rounded-xl border border-[#EADBC8] max-h-44 overflow-y-auto">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                    @foreach ($transaction->booking->manifest as $index => $nama)
                                        <div class="flex items-center gap-1.5 text-slate-700">
                                            <span class="text-[10px] font-bold text-[#B88A44] w-4 shrink-0">{{ $index + 1 }}.</span>
                                            <span class="truncate">{{ $nama }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Total Pembayaran</span>
                        <span class="font-extrabold text-[#B88A44] text-sm">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-1.5">
                        <span class="text-slate-600">Status Gate Tiket</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $transaction->used_at ? 'bg-slate-100 text-slate-600' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $transaction->used_at ? '✓ Sudah Digunakan' : '● Masih Berlaku' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('user.profile') }}"
                   class="py-2.5 rounded-xl border border-[#B88A44] text-[#B88A44] font-bold text-xs text-center hover:bg-[#F6F1E8] transition">
                    Riwayat Tiket
                </a>

                <a href="{{ route('user.home') }}"
                   class="py-2.5 rounded-xl bg-[#102A43] text-white font-bold text-xs text-center hover:bg-[#0c2238] transition shadow">
                    Kembali Beranda
                </a>
            </div>

        </div>

    </div>

</section>

@endsection
