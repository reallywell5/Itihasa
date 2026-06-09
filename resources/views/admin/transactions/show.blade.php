@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $transaction->id)

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md">

    <div class="mb-6">
        <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali ke Daftar</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t pt-6">
        <!-- Informasi Transaksi -->
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">Rincian Pembelian</h3>
            <div class="space-y-4 text-sm text-gray-600">
                <div>
                    <span class="block text-gray-400">Pembeli</span>
                    <strong class="text-gray-800 text-base">{{ $transaction->user?->name ?? 'Guest' }}</strong>
                </div>
                <div>
                    <span class="block text-gray-400">Email Akun</span>
                    <span>{{ $transaction->user?->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-gray-400">Waktu Transaksi</span>
                    <span>{{ $transaction->created_at?->format('d F Y, H:i') ?? '-' }} WIB</span>
                </div>
                <div>
                    <span class="block text-gray-400">Total Tagihan</span>
                    <strong class="text-lg text-indigo-600">Rp {{ number_format($transaction->total_price ?? 0) }}</strong>
                </div>
                <div>
                    <span class="block text-gray-400 mb-1">Status Tiket</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold
                        {{ $transaction->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ ucfirst($transaction->status ?? 'pending') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Kolom Informasi Tiket & QR Code -->
        <div class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-2xl border">
            <h4 class="text-sm font-bold text-gray-700 mb-4">QR Code Masuk Museum</h4>

            @if(!empty($transaction->qr_code))
                <!-- Jika Anda menggunakan library QR Code generator (seperti simplesoftwareio/simple-qrcode) -->
                <div class="bg-white p-4 rounded-xl shadow-sm mb-2">
                    {!! QrCode::size(150)->generate($transaction->qr_code) !!}
                </div>
                <span class="text-xs font-mono text-gray-400">{{ $transaction->qr_code }}</span>
            @else
                <!-- Jika data QR Code tidak ada / belum digenerate -->
                <div class="text-center p-4">
                    <span class="text-3xl block mb-2">🎫</span>
                    <p class="text-xs text-gray-400">QR Code akan aktif setelah status transaksi berubah menjadi 'Paid' di aplikasi user.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
