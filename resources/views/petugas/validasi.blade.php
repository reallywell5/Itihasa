@extends('layouts.petugas')

@section('title', 'Validasi Tiket')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">

        <p class="text-sm font-semibold text-blue-600 mb-2">
            Ticket Verification
        </p>

        <h1 class="text-2xl font-bold text-slate-800">
            Validasi Tiket
        </h1>

        <p class="text-slate-500 mt-2">
            Cari tiket secara manual jika QR Code tidak dapat dipindai.
        </p>

    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl font-semibold">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM VALIDASI --}}
    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">

        <h2 class="text-lg font-bold text-slate-800 mb-4">
            Cari Tiket
        </h2>

        <form method="GET" action="{{ route('petugas.validasi') }}">

            <div class="grid md:grid-cols-4 gap-4">

                <input
                    type="text"
                    name="invoice_code"
                    value="{{ request('invoice_code') }}"
                    placeholder="Masukkan kode tiket (misal: ITH-...)..."
                    class="md:col-span-3 px-4 py-3 border border-blue-100 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-semibold py-3 transition">
                    Cari Tiket
                </button>

            </div>

        </form>

    </div>

    {{-- HASIL VALIDASI --}}
    @if(isset($transaction))
    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm space-y-6">

        <h2 class="text-lg font-bold text-slate-800">
            Detail Tiket
        </h2>

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <p class="text-xs uppercase font-bold text-slate-400">Nama Pengunjung</p>
                <h3 class="font-bold text-slate-800 text-lg mt-1">
                    {{ $transaction->booking->user->name ?? 'Guest' }}
                </h3>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-slate-400">Kode Tiket</p>
                <h3 class="font-bold text-slate-800 text-lg font-mono mt-1">
                    {{ $transaction->invoice_code }}
                </h3>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-slate-400">Museum</p>
                <h3 class="font-bold text-slate-800 mt-1">
                    {{ $transaction->booking->museum->name }}
                </h3>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-slate-400 mb-1">Status Tiket</p>

                @if($transaction->used_at)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-gray-100 text-gray-600 text-xs font-semibold border border-gray-200">
                        ✓ Sudah Digunakan ({{ $transaction->used_at->format('H:i') }} WIB)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-green-50 text-green-600 text-xs font-semibold border border-green-100">
                        ● Valid / Belum Dipakai
                    </span>
                @endif
            </div>

        </div>

        {{-- RINCIAN TIKET DIBELI --}}
        <div class="border-t border-blue-50 pt-4">
            <p class="text-xs uppercase font-bold text-slate-400 mb-3">Rincian Tiket Dibeli</p>
            <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 space-y-2">
                @foreach($transaction->booking->ticket_items as $item)
                    <div class="flex justify-between items-center text-sm">
                        <div>
                            <span class="font-semibold text-slate-800">{{ $item['ticket_name'] }}</span>
                            <span class="text-slate-500"> x {{ $item['qty'] }}</span>
                        </div>
                        <span class="font-semibold text-slate-900">
                            @if($item['subtotal'] > 0)
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            @else
                                {{ $item['qty'] }} Qty
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TOMBOL KELOLA VALIDASI --}}
        @if(!$transaction->used_at)
            <form action="{{ route('petugas.qrcodes.validate') }}" method="POST" class="pt-2">
                @csrf
                <input type="hidden" name="qr_code" value="{{ $transaction->invoice_code }}">

                <button type="submit"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition shadow-md">
                    ✓ Tandai Tiket Sudah Digunakan
                </button>
            </form>
        @endif

    </div>
    @elseif(request('invoice_code'))
        <div class="bg-white rounded-3xl border border-red-100 p-8 text-center text-red-500 shadow-sm font-semibold">
            Tiket dengan kode "{{ request('invoice_code') }}" tidak ditemukan.
        </div>
    @endif

</div>

@endsection
