@extends('layouts.petugas')

@section('title', 'Generate QR Code')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">
                Generate QR Code
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Buat QR Code berdasarkan transaksi yang sudah dibayar.
            </p>
        </div>

        <a href="{{ route('petugas.qrcodes.index') }}"
           class="px-4 py-2 rounded-lg border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
            Kembali
        </a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-8">

        <form action="{{ route('petugas.qrcodes.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- PILIH TRANSAKSI --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-2">
                    Pilih Transaksi
                </label>

                <select name="transaction_id"
                        class="w-full border border-zinc-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
                        required>

                    <option value="">Pilih transaksi</option>

                    @foreach($transactions as $transaction)
                        <option value="{{ $transaction->id }}">
                            {{ $transaction->invoice_code }}
                            - {{ $transaction->booking->user->name }}
                            - {{ $transaction->booking->museum->name }}
                        </option>
                    @endforeach

                </select>

                @error('transaction_id')
                    <p class="text-sm text-red-600 mt-2">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- STATUS --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-2">
                    Status QR
                </label>

                <input type="text"
                       value="pending"
                       disabled
                       class="w-full border border-zinc-200 rounded-lg px-4 py-3 text-sm bg-zinc-50">
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-5 py-3 rounded-lg bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-700">
                    Simpan QR Code
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
