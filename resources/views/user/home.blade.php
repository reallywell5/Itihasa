@extends('layouts.user')

@section('title', 'Beranda — Museum & Warisan Nusantara')

@section('transparent_header')
@endsection

@section('full_bleed')
@endsection

@section('content')

{{-- ===================================================================== --}}
{{-- 1. HERO SECTION (FULL-BLEED EDITORIAL)                                --}}
{{-- ===================================================================== --}}
<section class="relative min-h-[85vh] w-full flex items-end overflow-hidden bg-[#121212]">
    {{-- BACKGROUND VIDEO --}}
    <video autoplay
           muted
           loop
           playsinline
           poster="{{ asset('images/institutional/hero-museum.jpg') }}"
           class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none">
        <source src="{{ asset('images/' . rawurlencode('Adobe Express - video museum.mp4')) }}" type="video/mp4">
    </video>

    {{-- SUBTLE DARK EDITORIAL GRADIENT OVERLAY FOR TEXT READABILITY --}}
    <div class="absolute inset-0 bg-gradient-to-t from-[#121212]/90 via-[#121212]/40 to-[#121212]/25"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-[#121212]/70 via-[#121212]/25 to-transparent"></div>

    {{-- HERO EDITORIAL CONTENT (BOTTOM-LEFT INSTITUTIONAL ANCHOR) --}}
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 pt-32 pb-14 sm:pb-20">
        <div class="max-w-3xl">
            <p class="animate-editorial animate-delay-1 font-sans text-[11px] font-medium uppercase tracking-[0.26em] text-[#C59B27] mb-4">
                Kurasi &amp; Pelestarian Warisan Bangsa
            </p>

            <h1 class="animate-editorial animate-delay-2 font-serif text-[38px] sm:text-5xl lg:text-[68px] xl:text-[76px] font-normal text-white leading-[1.06] tracking-[-0.02em]">
                Menjaga <span class="italic font-normal text-[#F5EFE6]">Jejak</span><br class="hidden sm:block">
                Peradaban <span class="italic font-normal text-[#C59B27]">Nusantara</span>
            </h1>

            <p class="animate-editorial animate-delay-3 font-sans mt-5 text-sm sm:text-base text-neutral-200/95 leading-relaxed max-w-xl font-normal">
                Jelajahi pameran tetap, artefak bersejarah, dan ruang edukasi kebudayaan di berbagai museum terakreditasi Indonesia dalam satu portal reservasi resmi.
            </p>

            {{-- PRIMARY CTA BUTTON --}}
            <div class="animate-editorial animate-delay-4 font-sans mt-8 flex flex-wrap items-center gap-3.5">
                <a href="#pameran"
                   class="px-6 py-3.5 rounded-sm bg-[#B8860B] hover:bg-[#8C3B2B] text-white text-xs font-medium uppercase tracking-[0.14em] transition duration-300">
                    Pesan Tiket Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===================================================================== --}}
{{-- 2. QUICK INFO BAR (INFORMASI KUNJUNGAN CEPAT)                         --}}
{{-- ===================================================================== --}}
@php
    $minCatalogPrice = $museums->map(fn($m) => $m->relationLoaded('tickets') ? $m->tickets->min('price') : $m->lowestTicketPrice())->filter(fn($p) => $p !== null)->min();
    $isMondayClosed = now()->dayOfWeekIso === 1;
@endphp

<section id="kunjungan" class="w-full bg-white border-b border-neutral-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-neutral-200">

            {{-- KOLOM 1: JAM OPERASIONAL --}}
            <div class="py-6 sm:pr-6 flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-sm bg-[#F8F7F4] border border-neutral-200 flex items-center justify-center text-[#B8860B] shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-[0.18em] text-[#5A5A5A] font-semibold">Jam Operasional</p>
                    <p class="text-sm font-semibold text-[#1A1A1A] mt-0.5">Selasa – Minggu, 08.00 – 16.00</p>
                    <p class="text-xs text-[#5A5A5A] mt-0.5">Senin &amp; Libur Nasional Tutup</p>
                </div>
            </div>

            {{-- KOLOM 2: HARGA TIKET MASUK --}}
            <div class="py-6 sm:px-6 flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-sm bg-[#F8F7F4] border border-neutral-200 flex items-center justify-center text-[#B8860B] shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-[0.18em] text-[#5A5A5A] font-semibold">Tiket Masuk</p>
                    <p class="text-sm font-semibold text-[#1A1A1A] mt-0.5">
                        @if($minCatalogPrice !== null && $minCatalogPrice > 0)
                            Mulai Rp {{ number_format($minCatalogPrice, 0, ',', '.') }}
                        @elseif($minCatalogPrice === 0)
                            Tersedia Akses Gratis
                        @else
                            Tarif Terjangkau Resmi
                        @endif
                    </p>
                    <p class="text-xs text-[#5A5A5A] mt-0.5">Individu &amp; Rombongan Sekolah</p>
                </div>
            </div>

            {{-- KOLOM 3: LOKASI / WILAYAH --}}
            <div class="py-6 sm:px-6 flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-sm bg-[#F8F7F4] border border-neutral-200 flex items-center justify-center text-[#B8860B] shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-[0.18em] text-[#5A5A5A] font-semibold">Lokasi Mitra</p>
                    <p class="text-sm font-semibold text-[#1A1A1A] mt-0.5">{{ count($cities ?? []) }} Wilayah Nusantara</p>
                    <p class="text-xs text-[#5A5A5A] mt-0.5">{{ $museums->count() }} Museum Terdaftar</p>
                </div>
            </div>

            {{-- KOLOM 4: STATUS HARI INI --}}
            <div class="py-6 sm:pl-6 flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-sm bg-[#F8F7F4] border border-neutral-200 flex items-center justify-center text-[#B8860B] shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-[0.18em] text-[#5A5A5A] font-semibold">Status Hari Ini</p>
                    <p class="text-sm font-semibold mt-0.5 flex items-center gap-1.5 {{ $isMondayClosed ? 'text-[#8C3B2B]' : 'text-emerald-700' }}">
                        <span class="w-2 h-2 rounded-full {{ $isMondayClosed ? 'bg-[#8C3B2B]' : 'bg-emerald-600' }}"></span>
                        <span>{{ $isMondayClosed ? 'Tutup Perawatan (Senin)' : 'Buka untuk Kunjungan' }}</span>
                    </p>
                    <p class="text-xs text-[#5A5A5A] mt-0.5">Reservasi daring aktif 24 jam</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===================================================================== --}}
{{-- 3. SECTION PAMERAN & ACARA (EXHIBITIONS & MUSEUM CATALOG — 3 COL)     --}}
{{-- ===================================================================== --}}
<section id="pameran" class="w-full bg-white py-16 sm:py-24 border-b border-neutral-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">

        {{-- HEADER SECTION: SERIF BESAR DI KIRI + TAUTAN "LIHAT SEMUA →" DI KANAN --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 border-b border-neutral-200">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] mb-2">
                    Pameran &amp; Agenda Kunjungan
                </p>
                <h2 class="font-serif text-3xl sm:text-4xl font-normal text-[#1A1A1A] tracking-[-0.015em]">
                    @if(request('search'))
                        Hasil Pencarian: <span class="italic">"{{ request('search') }}"</span>
                    @elseif(request('city'))
                        Destinasi di <span class="italic">{{ ucfirst(request('city')) }}</span>
                    @elseif(request('category'))
                        {{ $categories[request('category')] ?? 'Pameran & Museum' }}
                    @else
                        Pameran &amp; <span class="italic">Museum Pilihan</span>
                    @endif
                </h2>
            </div>

            <a href="{{ route('user.home') }}#pameran"
               class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-[#1A1A1A] hover:text-[#8C3B2B] nav-underline self-start md:self-auto transition duration-300">
                <span>Lihat Semua</span>
                <span>→</span>
            </a>
        </div>

        {{-- FILTER & PENCARIAN KURATORIAL (MEMPERTAHANKAN LOGIKA FILTER LENGKAP) --}}
        <div class="mt-8 mb-10 bg-[#F8F7F4] border border-neutral-200 rounded-sm p-4 sm:p-5">
            <form method="GET" action="{{ route('user.home') }}#pameran" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                {{-- KEYWORD --}}
                <div class="lg:col-span-4">
                    <label class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-[#5A5A5A] mb-1.5">
                        Cari Museum atau Topik
                    </label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nama museum, kota, koleksi..."
                           class="w-full px-3.5 py-2.5 rounded-sm border border-neutral-200 bg-white text-xs text-[#1A1A1A] placeholder:text-neutral-400 focus:outline-none focus:border-[#B8860B] transition duration-200">
                </div>

                {{-- WILAYAH / KOTA --}}
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-[#5A5A5A] mb-1.5">
                        Wilayah
                    </label>
                    <select name="city"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2.5 rounded-sm border border-neutral-200 bg-white text-xs text-[#1A1A1A] focus:outline-none focus:border-[#B8860B] transition duration-200 cursor-pointer">
                        <option value="">Semua Kota</option>
                        @foreach($cities ?? [] as $c)
                            <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- KATEGORI --}}
                <div class="lg:col-span-3">
                    <label class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-[#5A5A5A] mb-1.5">
                        Kategori Kurasi
                    </label>
                    <select name="category"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2.5 rounded-sm border border-neutral-200 bg-white text-xs text-[#1A1A1A] focus:outline-none focus:border-[#B8860B] transition duration-200 cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $catKey => $catLabel)
                            <option value="{{ $catKey }}" {{ request('category') == $catKey ? 'selected' : '' }}>
                                {{ $catLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TARIF ADMISI --}}
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-[#5A5A5A] mb-1.5">
                        Tarif Masuk
                    </label>
                    <select name="price_range"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2.5 rounded-sm border border-neutral-200 bg-white text-xs text-[#1A1A1A] focus:outline-none focus:border-[#B8860B] transition duration-200 cursor-pointer">
                        <option value="">Semua Tarif</option>
                        <option value="free" {{ request('price_range') == 'free' ? 'selected' : '' }}>Gratis</option>
                        <option value="under_25k" {{ request('price_range') == 'under_25k' ? 'selected' : '' }}>≤ Rp 25.000</option>
                        <option value="25k_50k" {{ request('price_range') == '25k_50k' ? 'selected' : '' }}>Rp 25.000 – 50.000</option>
                        <option value="above_50k" {{ request('price_range') == 'above_50k' ? 'selected' : '' }}>&gt; Rp 50.000</option>
                    </select>
                </div>

                {{-- SUBMIT / RESET --}}
                <div class="lg:col-span-1 flex items-center gap-2">
                    <button type="submit"
                            class="w-full py-2.5 px-3 rounded-sm bg-[#1A1A1A] hover:bg-[#8C3B2B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                        Cari
                    </button>
                </div>
            </form>

            {{-- KATEGORI CEPAT & ACTIVE CHIPS --}}
            <div class="mt-4 pt-3.5 border-t border-neutral-200 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('user.home') }}#pameran"
                       class="px-3 py-1 rounded-sm text-[11px] font-medium transition duration-300 {{ !request()->hasAny(['search', 'category', 'city', 'price_range']) ? 'bg-[#1A1A1A] text-white' : 'bg-white text-[#5A5A5A] border border-neutral-200 hover:border-[#1A1A1A] hover:text-[#1A1A1A]' }}">
                        Semua ({{ $museums->count() }})
                    </a>
                    @foreach($categories as $catKey => $catLabel)
                        <a href="{{ route('user.home', ['category' => $catKey]) }}#pameran"
                           class="px-3 py-1 rounded-sm text-[11px] font-medium transition duration-300 {{ request('category') == $catKey ? 'bg-[#1A1A1A] text-white' : 'bg-white text-[#5A5A5A] border border-neutral-200 hover:border-[#1A1A1A] hover:text-[#1A1A1A]' }}">
                            {{ $catLabel }}
                        </a>
                    @endforeach
                    <a href="{{ route('user.home', ['price_range' => 'free']) }}#pameran"
                       class="px-3 py-1 rounded-sm text-[11px] font-medium transition duration-300 {{ request('price_range') == 'free' ? 'bg-[#8C3B2B] text-white' : 'bg-white text-[#5A5A5A] border border-neutral-200 hover:border-[#8C3B2B] hover:text-[#8C3B2B]' }}">
                        Tiket Gratis
                    </a>
                </div>

                @if(request()->hasAny(['search', 'category', 'city', 'price_range']) && (request('search') || request('category') || request('city') || request('price_range')))
                    <a href="{{ route('user.home') }}#pameran"
                       class="text-xs font-semibold text-[#8C3B2B] hover:underline">
                        Atur Ulang Filter ×
                    </a>
                @endif
            </div>
        </div>

        {{-- 3-COLUMN EXHIBITION & MUSEUM CARD GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7 sm:gap-8">
            @forelse($museums as $museum)
                @php
                    $isOpen = false;
                    $todayBounds = $museum->operatingBoundsForDate(now());
                    $nowTime = now()->format('H:i');
                    if (!$museum->isClosedOnDate(now()) && $todayBounds && $nowTime >= $todayBounds['open'] && $nowTime <= $todayBounds['close']) {
                        $isOpen = true;
                    }
                    $lowestPrice = $museum->relationLoaded('tickets') ? $museum->tickets->min('price') : $museum->lowestTicketPrice();
                    $cityShort = trim(explode(',', $museum->address)[0] ?? $museum->address);
                @endphp

                <article class="group bg-white border border-neutral-200 rounded-sm overflow-hidden flex flex-col justify-between transition duration-300 hover:border-[#B8860B]">
                    <div>
                        {{-- 4:3 IMAGE WITH SMOOTH HOVER ZOOM --}}
                        <div class="relative aspect-[4/3] w-full overflow-hidden bg-neutral-100">
                            <a href="{{ route('museum.detail', $museum->id) }}" class="block w-full h-full">
                                <img src="{{ $museum->image
                                        ? (Str::startsWith($museum->image, 'storage/')
                                            ? asset($museum->image)
                                            : asset('storage/' . $museum->image))
                                        : asset('images/institutional/hero-museum.jpg') }}"
                                     alt="{{ $museum->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                            </a>

                            {{-- STATUS / DATE BADGE --}}
                            <div class="absolute top-3.5 left-3.5">
                                <span class="inline-block px-2.5 py-1 rounded-sm text-[10px] font-semibold uppercase tracking-wider {{ $isOpen ? 'bg-[#121212]/90 text-[#C59B27]' : 'bg-[#121212]/80 text-neutral-200' }}">
                                    {{ $isOpen ? 'Sedang Berlangsung' : 'Pameran Tetap' }}
                                </span>
                            </div>

                            {{-- WISHLIST BUTTON (AUTH ONLY) --}}
                            @auth
                                @php
                                    $isWishlisted = $museum->wishlists->contains('user_id', auth()->id());
                                @endphp
                                <form action="{{ route('user.wishlist.store', $museum->id) }}" method="POST" class="absolute top-3.5 right-3.5">
                                    @csrf
                                    <button type="submit"
                                            title="Simpan ke Wishlist"
                                            class="w-8 h-8 rounded-sm flex items-center justify-center text-xs border transition duration-300 {{ $isWishlisted ? 'bg-[#8C3B2B] border-[#8C3B2B] text-white' : 'bg-white/95 border-neutral-200 text-[#1A1A1A] hover:text-[#8C3B2B]' }}">
                                        {{ $isWishlisted ? '♥' : '♡' }}
                                    </button>
                                </form>
                            @endauth
                        </div>

                        {{-- CARD BODY --}}
                        <div class="p-6">
                            <div class="flex items-center justify-between gap-2 text-[11px] uppercase tracking-[0.14em] text-[#8C3B2B] font-semibold mb-2">
                                <span class="truncate">{{ $categories[$museum->category] ?? ucfirst($museum->category ?? 'Museum Sejarah') }}</span>
                                <span class="text-[#5A5A5A] font-medium shrink-0">{{ $cityShort }}</span>
                            </div>

                            <h3 class="font-serif text-xl font-medium text-[#1A1A1A] tracking-tight leading-snug line-clamp-1 group-hover:text-[#8C3B2B] transition-colors duration-300">
                                <a href="{{ route('museum.detail', $museum->id) }}">
                                    {{ $museum->name }}
                                </a>
                            </h3>

                            <p class="mt-2.5 text-sm text-[#5A5A5A] line-clamp-2 leading-relaxed min-h-[42px]">
                                {{ $museum->description ?? $museum->address }}
                            </p>
                        </div>
                    </div>

                    {{-- CARD FOOTER & ACTIONS --}}
                    <div class="px-6 pb-6 pt-4 border-t border-neutral-200 flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-[10px] uppercase tracking-wider text-[#5A5A5A]">Tiket Masuk</span>
                            <span class="text-sm font-semibold text-[#1A1A1A]">
                                @if($lowestPrice !== null && $lowestPrice > 0)
                                    Rp {{ number_format($lowestPrice, 0, ',', '.') }}
                                @else
                                    <span class="text-emerald-700">Gratis</span>
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            @auth
                                <a href="{{ route('museum.detail', $museum->id) }}"
                                   class="px-3.5 py-2 rounded-sm border border-neutral-300 text-xs font-semibold text-[#1A1A1A] hover:border-[#1A1A1A] transition duration-300">
                                    Detail
                                </a>
                                <a href="{{ route('user.booking', $museum->id) }}"
                                   class="px-4 py-2 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold transition duration-300">
                                    Pesan Tiket
                                </a>
                            @else
                                <a href="{{ route('museum.detail', $museum->id) }}"
                                   class="px-4 py-2 rounded-sm border border-[#1A1A1A] text-xs font-semibold text-[#1A1A1A] hover:bg-[#1A1A1A] hover:text-white transition duration-300">
                                    Lihat Detail →
                                </a>
                            @endauth
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 px-6 text-center bg-[#F8F7F4] border border-neutral-200 rounded-sm">
                    <p class="text-[11px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-2">Katalog Kosong</p>
                    <h3 class="font-serif text-2xl font-semibold text-[#1A1A1A]">
                        Pameran atau Museum Tidak Ditemukan
                    </h3>
                    <p class="text-sm text-[#5A5A5A] mt-2 max-w-md mx-auto">
                        Tidak ada entri yang cocok dengan kata kunci atau filter yang dipilih. Silakan atur ulang pencarian untuk menampilkan seluruh koleksi kami.
                    </p>
                    <a href="{{ route('user.home') }}#pameran"
                       class="mt-6 inline-block px-5 py-2.5 rounded-sm bg-[#1A1A1A] hover:bg-[#8C3B2B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                        Tampilkan Semua
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</section>

{{-- ===================================================================== --}}
{{-- 4. SECTION SOROTAN KOLEKSI (COLLECTION HIGHLIGHTS — DARK BENTO GRID)  --}}
{{-- ===================================================================== --}}
<section id="koleksi" class="w-full bg-[#161616] text-white py-20 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-neutral-800">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#C59B27] mb-2">
                    Sorotan Koleksi Pilihan
                </p>
                <h2 class="font-serif text-3xl sm:text-4xl font-normal text-white tracking-[-0.015em]">
                    Mahakarya &amp; <span class="italic">Artefak Peradaban</span>
                </h2>
            </div>
            <p class="text-sm text-neutral-400 max-w-md leading-relaxed">
                Dari tinggalan prasejarah dan perunggu ritual hingga seni pahat batu klasik yang merekam peradaban agung Nusantara.
            </p>
        </div>

        {{-- ASYMMETRIC EDITORIAL BENTO GRID --}}
        <div class="mt-10 grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- FEATURED LARGE BENTO CARD (KIRI) --}}
            <a href="#pameran"
               class="lg:col-span-7 group relative overflow-hidden rounded-sm border border-neutral-800 bg-[#121212] min-h-[380px] sm:min-h-[500px] flex items-end">
                <img src="{{ asset('images/institutional/highlight-heirlooms.jpg') }}"
                     alt="Khazanah Nekara Perunggu, Keris Pusaka & Perhiasan Emas Nusantara"
                     class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out">
                <div class="absolute inset-0 bg-gradient-to-t from-[#121212]/95 via-[#121212]/35 to-transparent"></div>

                <div class="relative z-10 p-6 sm:p-8 max-w-xl">
                    <span class="inline-block px-2.5 py-1 rounded-sm bg-[#B8860B] text-white text-[10px] font-semibold uppercase tracking-widest mb-3">
                        Khazanah Emas &amp; Perunggu
                    </span>
                    <h3 class="font-serif text-2xl sm:text-3xl font-normal text-white tracking-tight group-hover:text-[#C59B27] transition-colors duration-300">
                        Nekara Ritual &amp; <span class="italic">Pusaka Kerajaan Kuno</span>
                    </h3>
                    <p class="mt-2 text-xs sm:text-sm text-neutral-300 leading-relaxed">
                        Karya metalurgi tinggi abad lampau meliputi nekara perunggu upacara, keris berpamoran klasik, serta perhiasan kebesaran keraton.
                    </p>
                    <span class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-[#C59B27]">
                        <span>Lihat Pameran Terkait</span>
                        <span class="group-hover:translate-x-1 transition-transform duration-300">→</span>
                    </span>
                </div>
            </a>

            {{-- RIGHT COLUMN: 2 STACKED EDITORIAL CARDS --}}
            <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">

                {{-- CARD 2 --}}
                <a href="{{ route('user.home', ['category' => 'museum']) }}#pameran"
                   class="group relative overflow-hidden rounded-sm border border-neutral-800 bg-[#121212] min-h-[238px] flex items-end">
                    <img src="{{ asset('images/editorial/sculpture-generated.jpg') }}"
                         alt="Seni Pahat Batu & Ruang Galeri Monolit"
                         class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#121212]/95 via-[#121212]/40 to-transparent"></div>

                    <div class="relative z-10 p-6 w-full">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#C59B27] block mb-1.5">
                            Arkeologi &amp; Seni Pahat
                        </span>
                        <div class="flex items-end justify-between gap-3">
                            <h3 class="font-serif text-xl font-semibold text-white tracking-tight group-hover:text-[#C59B27] transition-colors duration-300">
                                Monolit Batu &amp; Inti Perunggu
                            </h3>
                            <span class="text-sm text-white group-hover:translate-x-1 transition-transform duration-300">→</span>
                        </div>
                    </div>
                </a>

                {{-- CARD 3 --}}
                <a href="{{ route('user.home', ['category' => 'budaya']) }}#pameran"
                   class="group relative overflow-hidden rounded-sm border border-neutral-800 bg-[#121212] min-h-[238px] flex items-end">
                    <img src="{{ asset('images/hero-museum.jpg') }}"
                         alt="Sejarah Tokoh Kebangsaan & Wastra Nusantara"
                         class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#121212]/95 via-[#121212]/40 to-transparent"></div>

                    <div class="relative z-10 p-6 w-full">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#C59B27] block mb-1.5">
                            Sejarah &amp; Etnografi
                        </span>
                        <div class="flex items-end justify-between gap-3">
                            <h3 class="font-serif text-xl font-semibold text-white tracking-tight group-hover:text-[#C59B27] transition-colors duration-300">
                                Tokoh Kebangsaan &amp; Tradisi Wastra
                            </h3>
                            <span class="text-sm text-white group-hover:translate-x-1 transition-transform duration-300">→</span>
                        </div>
                    </div>
                </a>

            </div>

        </div>

    </div>
</section>

{{-- ===================================================================== --}}
{{-- 5. RENCANAKAN KUNJUNGAN / CTA BANNER & PANDUAN (#tentang)             --}}
{{-- ===================================================================== --}}
<section id="tentang" class="w-full bg-[#F8F7F4] py-20 sm:py-24 border-b border-neutral-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">

        {{-- 2-COLUMN INSTITUTIONAL CTA BANNER --}}
        <div class="bg-white border border-neutral-200 rounded-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 items-stretch">

            {{-- LEFT: AJAKAN & BOOKING CTA --}}
            <div class="lg:col-span-6 p-8 sm:p-12 lg:p-14 flex flex-col justify-center">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#8C3B2B] mb-3">
                    Rencanakan Kunjungan Anda
                </p>

                <h2 class="font-serif text-3xl sm:text-4xl font-normal text-[#1A1A1A] tracking-[-0.015em] leading-[1.15]">
                    Pengalaman Kunjungan <span class="italic">Terkurasi</span> dengan E-Tiket Resmi
                </h2>

                <p class="mt-4 text-sm sm:text-base text-[#5A5A5A] leading-relaxed">
                    Siapkan rencana perjalanan sejarah Anda secara praktis. Pesan tiket masuk untuk individu maupun rombongan institusi pendidikan dan nikmati verifikasi gerbang digital instan.
                </p>

                <ul class="mt-6 space-y-2.5 text-xs sm:text-sm text-[#1A1A1A]">
                    <li class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#B8860B]"></span>
                        <span>Konfirmasi instan dengan E-Tiket QR terintegrasi</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#B8860B]"></span>
                        <span>Dukungan pembayaran QRIS, Virtual Account, &amp; E-Wallet</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#B8860B]"></span>
                        <span>Program paspor budaya &amp; stempel kunjungan museum</span>
                    </li>
                </ul>

                <div class="mt-8 flex flex-wrap items-center gap-3.5">
                    <a href="#pameran"
                       class="px-6 py-3 rounded-sm bg-[#1A1A1A] hover:bg-[#8C3B2B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                        Pesan Tiket Kunjungan
                    </a>

                    <a href="{{ route('faq') }}"
                       class="px-6 py-3 rounded-sm border border-neutral-300 text-[#1A1A1A] hover:border-[#1A1A1A] text-xs font-semibold uppercase tracking-wider transition duration-300">
                        Panduan &amp; FAQ
                    </a>
                </div>
            </div>

            {{-- RIGHT: VISUAL ARSITEKTUR / GALERI --}}
            <div class="lg:col-span-6 relative min-h-[280px] sm:min-h-[360px] bg-neutral-100 border-t lg:border-t-0 lg:border-l border-neutral-200 overflow-hidden">
                <img src="{{ asset('images/institutional/hero-museum.jpg') }}"
                     alt="Arsitektur Kolonade Gedung Museum"
                     class="w-full h-full object-cover object-center">
            </div>

        </div>

        {{-- PANDUAN RINGKAS / FAQ ACCORDION --}}
        <div class="mt-16 max-w-3xl mx-auto" x-data="{ activeFaq: null }">
            <div class="text-center mb-10">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] mb-2">
                    Pedoman Pengunjung
                </p>
                <h2 class="font-serif text-2xl sm:text-3xl font-normal text-[#1A1A1A] tracking-tight">
                    Pertanyaan Umum <span class="italic">Kunjungan</span>
                </h2>
            </div>

            <div class="bg-white border border-neutral-200 rounded-sm divide-y divide-neutral-200">

                {{-- FAQ 1 --}}
                <div>
                    <button type="button"
                            @click="activeFaq = (activeFaq === 1 ? null : 1)"
                            class="w-full px-6 py-4 flex items-center justify-between text-left gap-4 hover:bg-[#F8F7F4] transition duration-200">
                        <span class="font-serif font-medium text-base text-[#1A1A1A]">
                            Bagaimana alur pemesanan tiket museum secara daring?
                        </span>
                        <span class="text-xs font-semibold text-[#B8860B] shrink-0"
                              x-text="activeFaq === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse x-cloak>
                        <div class="px-6 pb-5 pt-1 text-xs sm:text-sm text-[#5A5A5A] leading-relaxed border-t border-neutral-100">
                            Pilih museum tujuan pada daftar pameran, klik <strong>Pesan Tiket</strong>, tentukan tanggal kunjungan dan kategori tiket, lalu selesaikan pembayaran melalui Midtrans (QRIS / Virtual Account). E-Tiket QR Code akan tersedia langsung di halaman Profil Anda.
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div>
                    <button type="button"
                            @click="activeFaq = (activeFaq === 2 ? null : 2)"
                            class="w-full px-6 py-4 flex items-center justify-between text-left gap-4 hover:bg-[#F8F7F4] transition duration-200">
                        <span class="font-serif font-medium text-base text-[#1A1A1A]">
                            Apakah tersedia pemesanan untuk rombongan sekolah atau instansi?
                        </span>
                        <span class="text-xs font-semibold text-[#B8860B] shrink-0"
                              x-text="activeFaq === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse x-cloak>
                        <div class="px-6 pb-5 pt-1 text-xs sm:text-sm text-[#5A5A5A] leading-relaxed border-t border-neutral-100">
                            Ya. Pemesanan untuk <strong>6 orang atau lebih</strong> akan otomatis ditandai sebagai reservasi rombongan dengan manifes daftar nama peserta untuk mempercepat verifikasi petugas di gerbang masuk.
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div>
                    <button type="button"
                            @click="activeFaq = (activeFaq === 3 ? null : 3)"
                            class="w-full px-6 py-4 flex items-center justify-between text-left gap-4 hover:bg-[#F8F7F4] transition duration-200">
                        <span class="font-serif font-medium text-base text-[#1A1A1A]">
                            Bagaimana proses masuk di gerbang museum menggunakan E-Tiket?
                        </span>
                        <span class="text-xs font-semibold text-[#B8860B] shrink-0"
                              x-text="activeFaq === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse x-cloak>
                        <div class="px-6 pb-5 pt-1 text-xs sm:text-sm text-[#5A5A5A] leading-relaxed border-t border-neutral-100">
                            Buka menu <strong>Profil &amp; Tiket Saya</strong> pada perangkat Anda dan tunjukkan QR Code tiket kepada petugas loket. Anda juga dapat mengunduh E-Tiket sebagai gambar untuk akses luring (*offline*).
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

@endsection
