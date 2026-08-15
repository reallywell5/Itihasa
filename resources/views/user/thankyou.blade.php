@extends('layouts.user')

@section('title', 'Terima Kasih Telah Berkunjung')

@section('content')

<section class="max-w-xl mx-auto py-8 sm:py-14">

    <div class="bg-white rounded-2xl shadow-sm border border-[#EADBC8] p-6 sm:p-8 text-center">

        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-100 text-3xl shadow-sm">
            🎉
        </div>

        <h1 class="text-xl sm:text-2xl font-bold text-[#102A43] mb-2">
            Terima Kasih Telah Berkunjung!
        </h1>

        <p class="text-xs text-slate-500 mb-6 max-w-sm mx-auto">
            Semoga kunjunganmu di <strong class="text-slate-700">{{ $qrCode->transaction->booking->museum->name ?? 'Museum' }}</strong> memberikan wawasan dan pengalaman berharga.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <a href="{{ route('museum.detail', $qrCode->transaction->booking->museum->id ?? 1) }}"
               class="py-2.5 rounded-xl border border-[#B88A44] text-[#B88A44] text-xs font-bold hover:bg-[#F6F1E8] transition text-center">
                Kembali ke Museum
            </a>

            <a href="{{ route('user.profile') }}"
               class="py-2.5 rounded-xl bg-[#102A43] text-white text-xs font-bold hover:bg-[#0c2238] transition shadow text-center">
                Buka Profil & Ulasan
            </a>
        </div>

    </div>

</section>

@endsection
