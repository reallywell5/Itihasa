@extends('layouts.user')

@section('title', 'Beranda')

@section('content')

{{-- HERO SECTION --}}
<section class="max-w-7xl mx-auto px-6 lg:px-8 pt-16 pb-20">

    <div class="grid lg:grid-cols-2 gap-16 items-center">

        {{-- LEFT --}}
        <div>

            <p class="text-sm uppercase tracking-[0.35em] text-[#B88A44] font-bold mb-5">
                Jelajahi Warisan Budaya
            </p>

            <h1 class="text-5xl lg:text-6xl font-bold text-[#102A43] leading-tight">
                Temukan Sejarah Melalui Museum
            </h1>

            <p class="mt-6 text-slate-600 text-lg leading-relaxed max-w-xl">
                Jelajahi museum terbaik, koleksi bersejarah, dan pengalaman budaya
                yang mendalam dalam satu platform digital modern.
            </p>

            {{-- SEARCH + FILTER --}}
            <form method="GET" action="{{ route('user.home') }}" class="mt-8 space-y-4">

                <div class="flex flex-col md:flex-row gap-4">

                    {{-- Search --}}
                    <input type="text"
                        name="search"
                        placeholder="Cari museum..."
                        value="{{ request('search') }}"
                        class="flex-1 px-5 py-4 rounded-2xl border border-[#EADBC8] focus:outline-none focus:ring-2 focus:ring-[#B88A44]">

                    {{-- Category --}}
                    <select name="category"
                        class="px-5 py-4 rounded-2xl border border-[#EADBC8] focus:outline-none">

                        <option value="">Semua Kategori</option>
                        <option value="history" {{ request('category') == 'history' ? 'selected' : '' }}>
                            Sejarah
                        </option>
                        <option value="art" {{ request('category') == 'art' ? 'selected' : '' }}>
                            Seni
                        </option>
                        <option value="science" {{ request('category') == 'science' ? 'selected' : '' }}>
                            Sains
                        </option>

                    </select>

                    {{-- Button --}}
                    <button class="px-6 py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                        Cari
                    </button>

                </div>

            </form>

        </div>

        {{-- RIGHT --}}
        <div class="relative">

            <div class="absolute -top-8 -left-8 w-56 h-56 rounded-full border-[28px] border-[#B88A44]/10"></div>

            <img src="{{ asset('images/museum-hero.jpg') }}"
                 alt="Museum"
                 class="rounded-[32px] shadow-2xl object-cover h-[540px] w-full border border-[#EADBC8]">

        </div>

    </div>

</section>

{{-- DAFTAR MUSEUM --}}
<section id="featured" class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

    <div class="flex items-center justify-between mb-10">

        <div>
            <h2 class="text-3xl font-bold text-[#102A43]">
                Museum Pilihan
            </h2>

            <p class="text-slate-500 mt-2">
                Temukan museum terbaik untuk pengalaman budaya premium.
            </p>
        </div>

        <div class="flex gap-3">

            <button onclick="scrollLeftSlider()"
                class="w-11 h-11 rounded-xl bg-white border border-[#EADBC8] shadow-sm">
                ←
            </button>

            <button onclick="scrollRightSlider()"
                class="w-11 h-11 rounded-xl bg-[#102A43] text-white shadow-sm">
                →
            </button>

        </div>

    </div>

    {{-- SLIDER --}}
    <div id="museumSlider"
         class="flex gap-6 overflow-x-auto scroll-smooth pb-4 no-scrollbar">

        @forelse($recommended as $museum)

        <div class="min-w-[330px] bg-white rounded-[28px] overflow-hidden border border-[#EADBC8] shadow-sm hover:shadow-xl transition group">

            {{-- IMAGE --}}
            <div class="relative overflow-hidden">

                <img src="{{ asset('storage/' . $museum->image) }}"
                     alt="{{ $museum->name }}"
                     class="h-60 w-full object-cover group-hover:scale-105 transition duration-500">

                <div class="absolute top-4 left-4 px-3 py-1 rounded-full bg-white/90 text-[#B88A44] text-xs font-bold">
                    Unggulan
                </div>

            </div>

            {{-- CONTENT --}}
            <div class="p-6">

                <div class="flex items-center justify-between mb-3">

                    <h3 class="font-bold text-xl text-[#102A43]">
                        {{ $museum->name }}
                    </h3>

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

                <p class="text-sm text-slate-500 mb-5">
                    {{ $museum->address }}
                </p>

                <div class="flex gap-2 mb-6">

                    <span class="px-3 py-1 rounded-full bg-[#F6F1E8] text-[#B88A44] text-xs font-semibold">
                        Museum
                    </span>

                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-xs font-semibold">
                        Buka
                    </span>

                </div>

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-xs text-slate-400">
                            Rating
                        </p>

                        <p class="font-bold text-[#102A43] text-lg">
                            ⭐ {{ number_format($museum->averageRating() ?? 0, 1) }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">

                        <a href="{{ route('museum.detail', $museum->id) }}"
                        class="px-4 py-3 rounded-xl border border-[#102A43] text-[#102A43] font-semibold hover:bg-[#102A43] hover:text-white transition">
                            Lihat Detail
                        </a>

                        <a href="{{ auth()->check()
                            ? route('user.booking', $museum->id)
                            : route('login') }}"
                        class="px-4 py-3 rounded-xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                            Pesan
                        </a>

                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="w-full text-center text-slate-500 py-12">
            Tidak ada museum ditemukan.
        </div>

        @endforelse

    </div>

</section>

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<script>
function scrollLeftSlider() {
    document.getElementById('museumSlider').scrollBy({
        left: -400,
        behavior: 'smooth'
    });
}

function scrollRightSlider() {
    document.getElementById('museumSlider').scrollBy({
        left: 400,
        behavior: 'smooth'
    });
}
</script>

@endsection
