@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Generate QR Code')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">Generate QR Code</h1>
            <p class="text-sm text-zinc-500 mt-1">Buat QR Code untuk tiket pengunjung.</p>
        </div>

        <a href="{{ route('qrcodes.index') }}"
           class="px-4 py-2 rounded-lg border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
            Kembali
        </a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-8">
        <form action="{{ route('qrcodes.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-2">
                    Transaction Detail
                </label>

                <select name="transaction_detail_id"
                        class="w-full border border-zinc-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
                        required>
                    <option value="">Pilih Transaction Detail</option>

                    @foreach($transactionDetails as $detail)
                        <option value="{{ $detail->id }}">
                            Transaction Detail #{{ $detail->id }}
                        </option>
                    @endforeach
                </select>

                @error('transaction_detail_id')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-2">
                    QR Code
                </label>

                <input type="text"
                       name="qr_code"
                       value="{{ old('qr_code', 'QR-' . strtoupper(Str::random(10))) }}"
                       class="w-full border border-zinc-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900"
                       required>

                @error('qr_code')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" name="scan_status" value="valid">

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
