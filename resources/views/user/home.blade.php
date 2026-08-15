@extends('layouts.user')

@section('title', 'Beranda')

@section('content')

{{-- HERO SECTION --}}
<section class="max-w-7xl mx-auto py-8 sm:py-12 lg:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">

        <div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#102A43] leading-tight">
                Jelajahi Sejarah & Warisan Budaya Indonesia
            </h1>

            <p class="mt-4 text-slate-600 text-sm sm:text-base leading-relaxed max-w-xl">
                Temukan museum terbaik, koleksi bersejarah, dan pengalaman budaya yang mendalam dalam satu platform digital modern dan terintegrasi.
            </p>

            {{-- SEARCH & FILTER --}}
            <form method="GET" action="{{ route('user.home') }}" class="mt-6">
                <div class="flex flex-col sm:flex-row gap-2.5 p-2 bg-white rounded-2xl border border-[#EADBC8] shadow-sm">
                    <input type="text"
                           name="search"
                           placeholder="Cari nama museum..."
                           value="{{ request('search') }}"
                           class="flex-1 px-4 py-2.5 rounded-xl border-0 bg-slate-50 sm:bg-transparent text-xs text-slate-800 focus:outline-none focus:ring-0">

                    <select name="category"
                            class="px-3 py-2.5 rounded-xl border-0 bg-slate-50 sm:bg-transparent text-xs text-slate-700 focus:outline-none">
                        <option value="">Semua Kategori</option>
                        <option value="museum" {{ request('category') == 'museum' ? 'selected' : '' }}>Museum Sejarah</option>
                        <option value="seni" {{ request('category') == 'seni' ? 'selected' : '' }}>Seni & Galeri</option>
                        <option value="budaya" {{ request('category') == 'budaya' ? 'selected' : '' }}>Budaya & Etnik</option>
                        <option value="alam" {{ request('category') == 'alam' ? 'selected' : '' }}>Alam & Sains</option>
                        <option value="religius" {{ request('category') == 'religius' ? 'selected' : '' }}>Religius</option>
                    </select>

                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#102A43] text-white font-bold text-xs hover:bg-[#0c2238] transition shadow shrink-0">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        {{-- HERO IMAGE --}}
        <div class="relative h-64 sm:h-80 lg:h-[440px] w-full overflow-hidden rounded-3xl border border-[#EADBC8] shadow-lg">
            <img src="{{ asset('images/hero-museum.jpg') }}"
                 alt="Museum Itihasa"
                 class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-t from-[#102A43]/40 via-transparent to-transparent"></div>
        </div>

    </div>
</section>

{{-- LIST MUSEUM --}}
<section id="featured" class="max-w-7xl mx-auto py-8 sm:py-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-[#102A43]">
                Museum & Destinasi Pilihan
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Koleksi museum bersejarah terverifikasi se-Indonesia.
            </p>
        </div>

        <div class="flex gap-2">
            <button onclick="scrollLeftSlider()"
                    class="w-9 h-9 rounded-xl bg-white border border-[#EADBC8] text-slate-700 font-bold shadow-sm hover:bg-slate-50 flex items-center justify-center transition">
                ←
            </button>
            <button onclick="scrollRightSlider()"
                    class="w-9 h-9 rounded-xl bg-[#102A43] text-white font-bold shadow-sm hover:bg-[#0c2238] flex items-center justify-center transition">
                →
            </button>
        </div>
    </div>

    {{-- SLIDER --}}
    <div id="museumSlider"
         class="flex gap-4 sm:gap-6 overflow-x-auto no-scrollbar scroll-smooth pb-4">
        @forelse($museums as $museum)
            <div class="min-w-[270px] max-w-[270px] sm:min-w-[310px] sm:max-w-[310px] bg-white rounded-2xl overflow-hidden border border-[#EADBC8] shadow-sm hover:shadow-md transition flex flex-col shrink-0">
                {{-- IMAGE --}}
                <div class="relative w-full h-44 sm:h-48 overflow-hidden bg-slate-100">
                    <img
                        src="{{ $museum->image
                            ? (Str::startsWith($museum->image, 'storage/')
                                ? asset($museum->image)
                                : asset('storage/' . $museum->image))
                            : asset('images/default-museum.jpg') }}"
                        alt="{{ $museum->name }}"
                        class="w-full h-full object-cover hover:scale-105 transition duration-300">

                    @auth
                        @php
                            $isWishlisted = $museum->wishlists->contains('user_id', auth()->id());
                        @endphp
                        <form action="{{ route('user.wishlist.store', $museum->id) }}" method="POST" class="absolute top-3 right-3">
                            @csrf
                            <button class="w-8 h-8 rounded-full flex items-center justify-center shadow transition
                                {{ $isWishlisted ? 'bg-red-500 text-white' : 'bg-white/90 backdrop-blur-sm text-slate-600 hover:text-red-500' }}">
                                {{ $isWishlisted ? '♥' : '♡' }}
                            </button>
                        </form>
                    @endauth
                </div>

                {{-- CONTENT --}}
                <div class="p-4 sm:p-5 flex flex-col flex-1">
                    <div class="flex items-center gap-1.5 mb-2">
                        <span class="px-2 py-0.5 rounded-md bg-[#F6F1E8] text-[#B88A44] text-[10px] font-bold">
                            {{ $museum->category ?? 'Museum' }}
                        </span>

                        @php
                            $isOpen = false;
                            if (!$museum->isClosedOnDate(now())) {
                                $bounds = $museum->operatingBoundsForDate(now());
                                $nowTime = now()->format('H:i');
                                if ($bounds && $nowTime >= $bounds['open'] && $nowTime <= $bounds['close']) {
                                    $isOpen = true;
                                }
                            }
                        @endphp

                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isOpen ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                            {{ $isOpen ? '● Buka' : '○ Tutup' }}
                        </span>
                    </div>

                    <h3 class="font-bold text-base text-[#102A43] line-clamp-2 min-h-[44px]">
                        {{ $museum->name }}
                    </h3>

                    <p class="text-xs text-slate-500 line-clamp-2 mt-1 min-h-[32px]">
                        {{ $museum->address }}
                    </p>

                    {{-- ACTIONS --}}
                    <div class="mt-4 pt-3 border-t border-[#EADBC8]/60 flex items-center gap-2">
                        <a href="{{ route('museum.detail', $museum->id) }}"
                           class="flex-1 py-2 text-center rounded-xl border border-[#102A43] text-[#102A43] font-semibold text-xs hover:bg-slate-50 transition">
                            Detail
                        </a>

                        <a href="{{ auth()->check() ? route('user.booking', $museum->id) : route('login') }}"
                           class="flex-1 py-2 text-center rounded-xl bg-[#102A43] text-white font-semibold text-xs hover:bg-[#0c2238] transition shadow">
                            Pesan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="w-full text-center py-12 text-slate-500 text-xs">
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
        left: -320,
        behavior: 'smooth'
    });
}

function scrollRightSlider() {
    document.getElementById('museumSlider').scrollBy({
        left: 320,
        behavior: 'smooth'
    });
}
</script>
@endsection
