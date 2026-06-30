@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('layouts.petugas')

@section('title', 'Detail QR Code')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">
                Detail QR Code
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Informasi lengkap tiket dan status validasi.
            </p>
        </div>

        <a href="{{ route('petugas.qrcodes.index') }}"
           class="px-4 py-2 rounded-lg border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
            Kembali
        </a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- INVOICE --}}
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Invoice Code
                </p>

                <p class="mt-2 text-lg font-bold text-zinc-900">
                    {{ $qrCode->transactionDetail?->transaction?->invoice_code ?? '-' }}
                </p>
            </div>

            {{-- PENGUNJUNG --}}
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Nama Pengunjung
                </p>

                <p class="mt-2 text-lg font-semibold text-zinc-900">
                    {{ $qr?->transaction?->booking?->user?->name ?? '-' }}
                </p>
            </div>

            {{-- MUSEUM --}}
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Museum
                </p>

                <p class="mt-2 text-lg font-semibold text-zinc-900">
                    {{ $qr?->transaction?->booking?->museum?->name ?? '-' }}
                </p>
            </div>

            {{-- TOTAL --}}
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Total Pembayaran
                </p>

                <p class="mt-2 text-lg font-bold text-zinc-900">
                    Rp {{ number_format($qrCode->transaction?->total_amount ?? 0, 0, ',', '.') }}
                </p>
            </div>

            {{-- STATUS --}}
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Status Tiket
                </p>

                <div class="mt-2">
                    @if($qr->scan_status == 'used')
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-xl">
                            Used
                        </span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-xl">
                            Valid
                        </span>
                    @endif
                </div>
            </div>

            {{-- SCANNED --}}
            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Scanned At
                </p>

                <p class="mt-2 text-sm text-zinc-700">
                    {{ $qrCode->scanned_at ? $qrCode->scanned_at->format('d M Y H:i') : '-' }}
                </p>
            </div>

        </div>

        {{-- QR DISPLAY --}}
        <div class="mt-10 border-t border-zinc-100 pt-8">

            <p class="text-xs uppercase tracking-widest font-bold text-zinc-400 mb-4">
                QR Code Ticket
            </p>

            <div class="inline-block p-5 border border-zinc-200 rounded-xl bg-white">
                {!! QrCode::size(220)->generate($qrCode->qr_code) !!}
            </div>

        </div>

    </div>

</div>
@endsection
