@extends('layouts.user')

@section('title', 'Thank You')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-12">

    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-sm border border-blue-100 p-10 text-center">

        <div class="w-20 h-20 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-6">
            <span class="text-4xl">✅</span>
        </div>

        <h1 class="text-3xl font-bold text-slate-800 mb-4">
            Terima Kasih Sudah Berkunjung!
        </h1>

        <p class="text-slate-500 mb-8">
            Tiket Anda telah berhasil digunakan untuk masuk ke
            <strong>{{ $qrCode->transaction?->booking?->museum?->name ?? '-' }}</strong>.
            Semoga pengalaman Anda menyenangkan.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <a href="{{ route('user.museums.index') }}"
               class="px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                Lihat Museum Lagi
            </a>

            <a href="{{ route('reviews.create', $qrCode->transaction?->booking?->museum?->id) }}"
               class="px-6 py-3 rounded-xl border border-blue-600 text-blue-600 font-semibold hover:bg-blue-50 transition">
                Beri Review
            </a>

        </div>

    </div>

</div>
@endsection
