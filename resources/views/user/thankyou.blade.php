@extends('layouts.user')

@section('title', 'Terima Kasih Telah Berkunjung')

@section('content')

<section class="max-w-xl mx-auto py-8 sm:py-14">

    <div class="bg-white rounded-sm border border-neutral-200 p-8 sm:p-10 text-center">

        <div class="w-14 h-14 rounded-sm bg-[#F8F7F4] text-[#B8860B] flex items-center justify-center mx-auto mb-5 border border-neutral-200 text-2xl">
            ✓
        </div>

        <p class="text-[11px] uppercase tracking-[0.22em] text-[#B8860B] font-semibold mb-2">
            Kunjungan Selesai
        </p>

        <h1 class="font-serif text-2xl sm:text-3xl font-semibold text-[#1A1A1A] tracking-tight mb-3">
            Terima Kasih Telah Berkunjung
        </h1>

        <p class="text-xs sm:text-sm text-[#5A5A5A] mb-8 max-w-sm mx-auto leading-relaxed">
            Semoga kunjungan Anda di <strong class="text-[#1A1A1A]">{{ $qrCode->transaction->booking->museum->name ?? 'Museum' }}</strong> memberikan wawasan dan pengalaman berharga.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('museum.detail', $qrCode->transaction->booking->museum->id ?? 1) }}"
               class="py-3 rounded-sm border border-neutral-300 text-[#1A1A1A] text-xs font-semibold uppercase tracking-wider hover:bg-[#F8F7F4] transition duration-300 text-center">
                Kembali ke Museum
            </a>

            <a href="{{ route('user.profile') }}"
               class="py-3 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300 text-center">
                Buka Profil &amp; Ulasan
            </a>
        </div>

    </div>

</section>

@endsection
