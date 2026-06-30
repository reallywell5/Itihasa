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
                    placeholder="Masukkan kode tiket..."
                    class="md:col-span-3 px-4 py-3 border border-blue-100 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-semibold">
                    Cari Tiket
                </button>

            </div>

        </form>

    </div>

    {{-- HASIL VALIDASI --}}
    @if(isset($transaction))
    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">

        <h2 class="text-lg font-bold text-slate-800 mb-4">
            Detail Tiket
        </h2>

        <div class="grid md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-slate-400">Nama Pengunjung</p>
                <h3 class="font-bold text-slate-800 mt-1">
                    {{ $transaction->booking->user->name }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-slate-400">Kode Tiket</p>
                <h3 class="font-bold text-slate-800 mt-1">
                    {{ $transaction->invoice_code }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-slate-400">Museum</p>
                <h3 class="font-bold text-slate-800 mt-1">
                    {{ $transaction->booking->museum->name }}
                </h3>
            </div>

            <div>
                <p class="text-sm text-slate-400">Status</p>

                @if($transaction->used_at)
                    <span class="inline-flex items-center px-3 py-1.5 mt-1 rounded-xl bg-gray-100 text-gray-600 text-xs font-semibold border border-gray-200">
                        Sudah Digunakan
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 mt-1 rounded-xl bg-green-50 text-green-600 text-xs font-semibold border border-green-100">
                        Valid
                    </span>
                @endif
            </div>

        </div>

        @if(!empty($qr))
            <form action="{{ route('petugas.validateQr') }}" method="POST">
                @csrf

                <input type="hidden" name="invoice_code" value="{{ $qr->qr_code }}">

                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                    Tandai Sudah Digunakan
                </button>
            </form>
        @endif

    </div>
    @endif

</div>

@endsection
