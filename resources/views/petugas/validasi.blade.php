@extends('layouts.petugas')

@section('title', 'Validasi Tiket Manual')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold mb-1.5">
            🛡 Validasi Tiket • Pencarian Manual
        </span>
        <h1 class="text-lg sm:text-xl font-bold text-slate-800">
            Validasi Tiket Manual
        </h1>
        <p class="text-xs text-slate-400 mt-0.5">
            Gunakan formulir ini jika QR Code di layar HP pengunjung rusak, redup, atau tidak dapat dipindai oleh kamera.
        </p>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- SEARCH FORM --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm">
        <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
            Cari Kode Invoice / Tiket
        </h2>

        <form method="GET" action="{{ route('petugas.validasi') }}" class="flex flex-col sm:flex-row gap-2.5">
            <input
                type="text"
                name="invoice_code"
                value="{{ request('invoice_code') }}"
                placeholder="Masukkan kode tiket (Contoh: ITH-...)"
                required
                class="flex-1 px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-emerald-600"
            >

            <button
                type="submit"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm shrink-0">
                Cari Tiket
            </button>
        </form>
    </div>

    {{-- VALIDATION RESULT --}}
    @if(isset($transaction))
        <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm space-y-4">
            @if(isset($staffMuseumId) && $staffMuseumId && $transaction->booking?->museum_id !== $staffMuseumId)
                <div class="p-3.5 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl text-xs font-semibold flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <span>Perhatian: Tiket ini terdaftar untuk <strong>{{ $transaction->booking->museum->name ?? 'Museum Lain' }}</strong>, bukan untuk museum Anda.</span>
                </div>
            @endif

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-800">
                    Hasil Temuan Tiket
                </h2>
                @if($transaction->used_at)
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold">
                        Sudah Digunakan ({{ $transaction->used_at->format('H:i') }} WIB)
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                        ● Tiket Masih Aktif / Valid
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="text-[10px] uppercase font-bold text-slate-400">Pengunjung</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $transaction->booking->user->name ?? 'Pengunjung' }}</p>
                    <p class="text-[10px] text-slate-500">{{ $transaction->booking->user->email ?? '-' }}</p>
                </div>

                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="text-[10px] uppercase font-bold text-slate-400">Kode Invoice</p>
                    <p class="font-bold font-mono text-slate-800 mt-0.5">{{ $transaction->invoice_code }}</p>
                    <p class="text-[10px] text-slate-500">Tgl Kunjungan: {{ $transaction->booking->visit_date ? \Carbon\Carbon::parse($transaction->booking->visit_date)->translatedFormat('d M Y') : '-' }}</p>
                </div>

                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="text-[10px] uppercase font-bold text-slate-400">Museum Tujuan</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $transaction->booking->museum->name ?? '-' }}</p>
                    <p class="text-[10px] text-slate-500">Status Bayar: <strong class="text-emerald-600 uppercase">{{ $transaction->payment_status }}</strong></p>
                </div>
            </div>

            {{-- TICKET ITEMS --}}
            <div class="border-t border-slate-100 pt-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Rincian Varian Tiket</p>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 space-y-1.5 text-xs">
                    @foreach($transaction->booking->ticket_items ?? [] as $item)
                        <div class="flex justify-between items-center text-slate-700">
                            <div>
                                <span class="font-semibold">{{ $item['ticket_name'] ?? 'Tiket' }}</span>
                                <span class="text-slate-400"> x{{ $item['qty'] ?? 1 }}</span>
                            </div>
                            <span class="font-mono font-bold text-slate-800">
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

            {{-- VALIDATE BUTTON --}}
            @if(!$transaction->used_at)
                <form action="{{ route('petugas.qrcodes.validate') }}" method="POST" class="pt-2">
                    @csrf
                    <input type="hidden" name="qr_code" value="{{ $transaction->invoice_code }}">
                    <button type="submit"
                            class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Konfirmasi Validasi & Izinkan Masuk</span>
                    </button>
                </form>
            @endif
        </div>
    @elseif(request('invoice_code'))
        <div class="bg-white rounded-2xl border border-red-100 p-6 text-center text-red-500 text-xs font-semibold shadow-sm">
            Tiket dengan kode invoice "{{ request('invoice_code') }}" tidak ditemukan atau belum berstatus lunas.
        </div>
    @endif

</div>
@endsection
