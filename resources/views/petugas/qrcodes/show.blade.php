@extends('layouts.petugas')

@section('title', 'Detail QR Code')

@section('content')
<div class="max-w-lg mx-auto space-y-6">

    <a href="{{ route('petugas.qrcodes.index') }}" class="text-blue-600 font-semibold">
        ← Kembali ke Daftar
    </a>

    <div class="bg-white rounded-3xl border border-blue-100 p-8 shadow-sm text-center space-y-6">

        <div>
            <h1 class="text-xl font-bold text-slate-800">
                {{ $transaction->booking->user->name ?? '-' }}
            </h1>
            <p class="text-slate-500">{{ $transaction->booking->museum->name ?? '-' }}</p>
        </div>

        {{-- QR code digenerate langsung dari invoice_code, tidak disimpan ke DB --}}
        <div class="flex justify-center">
            {!! QrCode::size(220)->generate($transaction->invoice_code) !!}
        </div>

        <p class="font-mono text-sm text-slate-500">
            {{ $transaction->invoice_code }}
        </p>

        @if($transaction->used_at)
            <span class="inline-flex px-3 py-1.5 rounded-xl bg-gray-100 text-gray-600 text-xs font-semibold border border-gray-200">
                ✓ Sudah Digunakan ({{ $transaction->used_at->format('d M Y, H:i') }})
            </span>
        @else
            <span class="inline-flex px-3 py-1.5 rounded-xl bg-green-50 text-green-600 text-xs font-semibold border border-green-100">
                ● Belum Dipakai
            </span>
        @endif

    </div>

</div>
@endsection
