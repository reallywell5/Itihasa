@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('layouts.app')

@section('title', 'Detail QR Code')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">Detail QR Code</h1>
            <p class="text-sm text-zinc-500 mt-1">Informasi lengkap QR Code tiket.</p>
        </div>

        <a href="{{ route('qrcodes.index') }}"
           class="px-4 py-2 rounded-lg border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
            Kembali
        </a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Transaction Detail ID
                </p>
                <p class="mt-2 text-lg font-bold text-zinc-900">
                    #{{ $qrCode->transaction_detail_id }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    QR Code
                </p>
                <p class="mt-2 text-sm font-mono text-zinc-700">
                    {{ $qrCode->qr_code }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Scan Status
                </p>

                <div class="mt-2">
                    @if($qrCode->scan_status == 'used')
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            Used
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                            Valid
                        </span>
                    @endif
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Scanned At
                </p>
                <p class="mt-2 text-sm text-zinc-700">
                    {{ $qrCode->scanned_at ? $qrCode->scanned_at->format('d M Y H:i') : '-' }}
                </p>
            </div>

        </div>

        <div class="mt-10 border-t border-zinc-100 pt-8">
            <p class="text-xs uppercase tracking-widest font-bold text-zinc-400 mb-4">
                Tampilan QR Code
            </p>

            <div class="inline-block p-5 border border-zinc-200 rounded-xl bg-white">
                {!! QrCode::size(200)->generate($qrCode->qr_code) !!}
            </div>
        </div>
    </div>

</div>
@endsection
