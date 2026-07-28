@extends('layouts.user')

@section('title', 'Terima Kasih')

@section('content')

<section class="max-w-3xl mx-auto px-6 py-20">

    <div class="bg-white rounded-[32px] shadow-sm border border-[#EADBC8] p-10 text-center">

        <h1 class="text-4xl font-bold text-[#102A43] mb-4">
            Terima Kasih Sudah Berkunjung 🎉
        </h1>

        <p class="text-slate-500 mb-8">
            Semoga pengalamanmu di {{ $qrCode->transaction->booking->museum->name }} menyenangkan.
        </p>

        <div class="grid grid-cols-2 gap-4">

            <a href="{{ route('museum.detail', $qrCode->transaction->booking->museum->id) }}"
               class="py-4 rounded-2xl border border-[#B88A44] text-[#B88A44] font-semibold">
                Kembali ke Museum
            </a>

            <a href="#review-section"
               class="py-4 rounded-2xl bg-[#102A43] text-white font-semibold">
                Beri Review
            </a>

        </div>

    </div>

</section>

@endsection
