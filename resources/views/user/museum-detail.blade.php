@extends('layouts.user')

@section('title', $museum->name)

@section('content')

{{-- HERO IMAGE --}}
<section class="relative h-[520px] overflow-hidden">

    <img src="{{ asset('storage/' . $museum->image) }}"
         alt="{{ $museum->name }}"
         class="w-full h-full object-cover">

    <div class="absolute inset-0 bg-black/35"></div>

</section>

{{-- FLOATING DETAIL --}}
<section class="relative -mt-24 z-20 max-w-7xl mx-auto px-6 lg:px-8">

    <div class="grid lg:grid-cols-[1fr_380px] gap-10">

        {{-- LEFT --}}
        <div class="space-y-8">

            {{-- MAIN INFO --}}
            <div class="bg-white rounded-[32px] shadow-xl border border-[#EADBC8] p-8">

                <div class="flex items-center justify-between mb-5">

                    <div>
                        <p class="uppercase text-xs tracking-[0.35em] text-[#B88A44] font-bold mb-3">
                            Koleksi Bersejarah
                        </p>

                        <h1 class="text-4xl font-bold text-[#102A43]">
                            {{ $museum->name }}
                        </h1>
                    </div>

                    @auth
                        @php
                            $isWishlisted = $museum->wishlists->contains('user_id', auth()->id());
                        @endphp

                        <form action="{{ route('user.wishlist.store', $museum->id) }}" method="POST">
                            @csrf
                            <button
                                class="w-10 h-10 rounded-full flex items-center justify-center transition
                                {{ $isWishlisted ? 'bg-red-100 text-red-500' : 'bg-[#F6F1E8] text-[#B88A44]' }}">
                                {{ $isWishlisted ? '♥' : '♡' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                        class="w-10 h-10 rounded-full flex items-center justify-center bg-[#F6F1E8] text-[#B88A44]">
                            ♡
                        </a>
                    @endauth

                </div>

                <div class="flex flex-wrap items-center gap-4 mb-6">

                    <span class="text-[#B88A44] font-bold">
                        ⭐ {{ number_format($museum->averageRating() ?? 0, 1) }}
                    </span>

                    <span class="text-slate-400">
                        {{ $museum->reviews->count() }} Ulasan
                    </span>

                    <span class="text-slate-400">
                        • {{ $museum->address }}
                    </span>

                </div>

                <p class="text-slate-600 leading-relaxed text-lg">
                    {{ $museum->description }}
                </p>

            </div>

            {{-- TENTANG --}}
            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8">

                <h2 class="text-2xl font-bold text-[#102A43] mb-5">
                    Tentang Museum
                </h2>

                <p class="text-slate-600 leading-relaxed">
                    {{ $museum->description }}
                </p>

            </div>

            {{-- GALLERY --}}
            <div>

                <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                    Galeri
                </h2>

                <div class="grid grid-cols-2 gap-4">

                    @for($i=0;$i<4;$i++)
                    <img src="{{ asset('storage/' . $museum->image) }}"
                         class="rounded-[20px] h-56 w-full object-cover hover:scale-[1.02] transition">
                    @endfor

                </div>

            </div>

            {{-- REVIEWS --}}
            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8">

                <h2 class="text-2xl font-bold text-[#102A43] mb-8">
                    Ulasan Pengunjung
                </h2>

                <div class="space-y-6">

                    @forelse($museum->reviews as $review)

                    <div class="border-b pb-6">

                        <div class="flex items-center gap-4 mb-4">

                            <div class="w-12 h-12 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>

                            <div>
                                <h3 class="font-bold text-[#102A43]">
                                    {{ $review->user->name }}
                                </h3>

                                <p class="text-sm text-[#B88A44]">
                                    @for($i=0;$i<$review->rating;$i++) ⭐ @endfor
                                </p>
                            </div>

                        </div>

                        <p class="text-slate-600">
                            {{ $review->comment }}
                        </p>

                    </div>

                    @empty

                    <div class="text-center text-slate-500 py-6">
                        Belum ada ulasan untuk museum ini.
                    </div>

                    @endforelse

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            <div class="sticky top-28 space-y-6">

                {{-- QUICK INFO --}}
                <div class="bg-white rounded-[28px] border border-[#EADBC8] shadow-sm p-8">

                    <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                        Informasi Cepat
                    </h2>

                    <div class="space-y-5">

                        <div class="flex justify-between">
                            <span class="text-slate-500">Jam Buka</span>
                            <span class="font-semibold text-[#102A43]">
                                {{ $museum->opening_time }} - {{ $museum->closing_time }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Kategori</span>
                            <span class="font-semibold text-[#102A43]">
                                Museum
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Durasi</span>
                            <span class="font-semibold text-[#102A43]">
                                2-3 Jam
                            </span>
                        </div>

                        <div class="border-t pt-5 flex justify-between items-center">

                            <div>
                                <p class="text-sm text-slate-400">
                                    Harga Mulai
                                </p>

                                <h3 class="text-2xl font-bold text-[#B88A44]">
                                    Rp 25.000
                                </h3>
                            </div>

                        </div>

                    </div>

                    <a href="{{ auth()->check()
                        ? route('user.booking', $museum->id)
                        : route('login') }}"
                       class="mt-8 w-full flex justify-center py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                        Pesan Tiket
                    </a>

                </div>

                {{-- FASILITAS --}}
                <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8">

                    <h3 class="text-xl font-bold text-[#102A43] mb-5">
                        Fasilitas
                    </h3>

                    <div class="space-y-4 text-slate-600">

                        <p>✔ Area Parkir</p>
                        <p>✔ Kafe</p>
                        <p>✔ Toko Souvenir</p>
                        <p>✔ Tur Pemandu</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
