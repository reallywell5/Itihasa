@extends('layouts.user')

@section('title', 'Beranda')

@section('content')

{{-- HERO SECTION --}}
<section class="max-w-7xl mx-auto px-6 lg:px-8 pt-16 pb-20">

    <div class="grid lg:grid-cols-2 gap-16 items-center">

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

            {{-- SEARCH --}}
            <form method="GET" action="{{ route('user.home') }}" class="mt-8 space-y-4">
                <div class="flex flex-col md:flex-row gap-4">

                    <input type="text"
                        name="search"
                        placeholder="Cari museum..."
                        value="{{ request('search') }}"
                        class="flex-1 px-5 py-4 rounded-2xl border border-[#EADBC8]">

                    <select name="category"
                        class="px-5 py-4 rounded-2xl border border-[#EADBC8]">

                        <option value="">Semua Kategori</option>
                        <option value="history">Sejarah</option>
                        <option value="art">Seni</option>
                        <option value="science">Sains</option>

                    </select>

                    <button class="px-6 py-4 rounded-2xl bg-[#102A43] text-white font-semibold">
                        Cari
                    </button>

                </div>
            </form>
        </div>

        {{-- HERO IMAGE --}}
        <div class="relative h-[540px] w-full overflow-hidden rounded-[32px] border border-[#EADBC8] shadow-2xl">

            <img src="{{ asset('images/hero-museum.jpg') }}"
                alt="Museum"
                class="w-full h-full object-cover object-center">

            {{-- overlay biar lebih elegan --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>

        </div>

    </div>

</section>

{{-- LIST MUSEUM --}}
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
        class="flex gap-8 overflow-x-auto no-scrollbar scroll-smooth">

        @forelse($museums as $museum)

        <div class="min-w-[330px] max-w-[330px] h-[560px] bg-white rounded-[28px] overflow-hidden border border-[#EADBC8] shadow-sm hover:shadow-xl transition flex flex-col flex-shrink-0">

            {{-- IMAGE --}}
            <div class="relative w-full h-56 overflow-hidden">
                <img
                    src="{{ $museum->image
                        ? (Str::startsWith($museum->image, 'storage/')
                            ? asset($museum->image)
                            : asset('storage/' . $museum->image))
                        : asset('images/default-museum.jpg') }}"
                    alt="{{ $museum->name }}"
                    class="w-full h-full object-cover">
            </div>

            {{-- CONTENT --}}
            <div class="p-6 flex flex-col flex-1">

                {{-- TITLE --}}
                <div class="flex items-start justify-between mb-3 gap-3">

                    <h3 class="font-bold text-xl text-[#102A43] line-clamp-2 min-h-[64px]">
                        {{ $museum->name }}
                    </h3>

                    @auth
                        @php
                            $isWishlisted = $museum->wishlists->contains('user_id', auth()->id());
                        @endphp

                        <form action="{{ route('user.wishlist.store', $museum->id) }}" method="POST">
                            @csrf
                            <button class="w-10 h-10 rounded-full flex items-center justify-center transition
                                {{ $isWishlisted ? 'bg-red-100 text-red-500' : 'bg-[#F6F1E8] text-[#B88A44]' }}">
                                {{ $isWishlisted ? '♥' : '♡' }}
                            </button>
                        </form>
                    @endauth

                </div>

                {{-- ADDRESS --}}
                <p class="text-sm text-slate-500 line-clamp-3 min-h-[72px]">
                    {{ $museum->address }}
                </p>

                {{-- TAG --}}
                <div class="flex gap-2 mt-4 mb-6">
                    <span class="px-3 py-1 rounded-full bg-[#F6F1E8] text-[#B88A44] text-xs font-semibold">
                        Museum
                    </span>

                    @php
                        $now = now()->format('H:i');
                        $open = \Carbon\Carbon::parse($museum->opening_time)->format('H:i');
                        $close = \Carbon\Carbon::parse($museum->closing_time)->format('H:i');

                        $isOpen = $now >= $open && $now <= $close;
                    @endphp

                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $isOpen ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                        {{ $isOpen ? 'Buka' : 'Tutup' }}
                    </span>
                </div>

                {{-- BOTTOM --}}
                <div class="mt-auto flex items-center justify-between">

                    <div class="flex gap-2">
                        <a href="{{ route('museum.detail', $museum->id) }}"
                        class="px-4 py-3 rounded-xl border border-[#102A43] text-[#102A43] font-semibold">
                            Detail
                        </a>

                        <a href="{{ auth()->check()
                            ? route('user.booking', $museum->id)
                            : route('login') }}"
                        class="px-4 py-3 rounded-xl bg-[#102A43] text-white font-semibold">
                            Pesan
                        </a>
                    </div>

                </div>

            </div>

        </div>

        @empty
        <div class="w-full text-center py-12 text-slate-500">
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
