@extends('layouts.user')

@section('title', 'Beranda')

@section('content')

{{-- HERO SECTION --}}
<section class="max-w-7xl mx-auto pt-2 pb-10 sm:pt-4 sm:pb-14 lg:pt-4 lg:pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">

        {{-- LEFT COLUMN: HERITAGE ELEGANT TEXT & UPGRADED SIMPLE SEARCH --}}
        <div class="lg:col-span-6 space-y-6">

            <div>
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#B88A44] block mb-3.5">
                    JELAJAHI WARISAN BUDAYA
                </span>

                <h1 class="font-display text-3xl sm:text-4xl lg:text-[50px] font-bold text-[#102A43] leading-[1.22] lg:leading-[1.25] tracking-tight">
                    Warisan Sejarah & <span class="italic font-normal text-[#B88A44]">Kebudayaan</span> Nusantara
                </h1>

                <p class="mt-4 text-slate-600 text-sm sm:text-base leading-relaxed max-w-xl font-normal">
                    Reservasi tiket museum bersejarah di Indonesia secara instan, nyaman, dan terpercaya.
                </p>
            </div>

            {{-- SEARCH & FILTER (MODERN INDUSTRY-STANDARD DISCOVERY & SEARCH) --}}
            <div class="space-y-3.5"
                 x-data="{ 
                    query: '{{ request('search') }}',
                    city: '{{ request('city') }}',
                    category: '{{ request('category') }}',
                    price: '{{ request('price_range') }}',
                    clearSearch() {
                        this.query = '';
                        $refs.searchInput.focus();
                    }
                 }">

                {{-- MAIN SEARCH CARD --}}
                <div class="bg-white rounded-3xl border border-[#EADBC8] p-3.5 sm:p-4 shadow-[0_12px_36px_rgba(16,42,67,0.07)] space-y-3 transition hover:border-[#B88A44]/50">
                    <form method="GET" action="{{ route('user.home') }}#featured">

                        {{-- 1. PRIMARY SEARCH INPUT BAR --}}
                        <div class="relative flex items-center bg-[#FAF7F2] rounded-2xl border border-[#EADBC8] p-1.5 transition focus-within:border-[#B88A44] focus-within:ring-2 focus-within:ring-[#B88A44]/20 focus-within:bg-white shadow-inner">
                            
                            {{-- MAGNIFYING ICON --}}
                            <div class="pl-3 pr-2 text-[#B88A44] flex items-center pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>

                            {{-- INPUT FIELD --}}
                            <input type="text"
                                   name="search"
                                   x-ref="searchInput"
                                   x-model="query"
                                   placeholder="Cari museum, pameran seni, atau kota..."
                                   class="w-full py-2.5 sm:py-3 bg-transparent text-xs sm:text-sm text-slate-800 font-medium placeholder:text-slate-400 focus:outline-none">

                            {{-- CLEAR BUTTON (MUNCUL JIKA ADA QUERY) --}}
                            <button type="button"
                                    x-show="query && query.length > 0"
                                    x-cloak
                                    @click="clearSearch()"
                                    title="Hapus teks"
                                    class="p-1.5 mr-1 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-200/60 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            {{-- SEARCH CTA BUTTON --}}
                            <button type="submit"
                                    class="px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl bg-[#102A43] hover:bg-[#0c2238] active:scale-95 text-white font-bold text-xs sm:text-sm transition shadow-md shrink-0 flex items-center gap-1.5">
                                <span>Cari</span>
                                <svg class="w-4 h-4 hidden sm:inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>

                        {{-- 2. FILTER STRIP (LOKASI, KATEGORI, HARGA) --}}
                        <div class="pt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
                            
                            {{-- FILTER KOTA --}}
                            <div class="relative">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1 ml-1 flex items-center gap-1">
                                    <span>📍</span> <span>Lokasi / Kota</span>
                                </label>
                                <div class="relative">
                                    <select name="city"
                                            x-model="city"
                                            @change="$el.form.submit()"
                                            class="w-full pl-3 pr-8 py-2 rounded-xl border border-[#EADBC8] bg-[#FAF7F2] hover:bg-white text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#B88A44]/30 focus:border-[#B88A44] transition cursor-pointer appearance-none shadow-sm">
                                        <option value="">Semua Kota</option>
                                        @foreach($cities ?? [] as $c)
                                            <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>
                                                {{ $c }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400 text-xs">
                                        ▾
                                    </div>
                                </div>
                            </div>

                            {{-- FILTER KATEGORI --}}
                            <div class="relative">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1 ml-1 flex items-center gap-1">
                                    <span>🏛️</span> <span>Kategori</span>
                                </label>
                                <div class="relative">
                                    <select name="category"
                                            x-model="category"
                                            @change="$el.form.submit()"
                                            class="w-full pl-3 pr-8 py-2 rounded-xl border border-[#EADBC8] bg-[#FAF7F2] hover:bg-white text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#B88A44]/30 focus:border-[#B88A44] transition cursor-pointer appearance-none shadow-sm">
                                        <option value="">Semua Kategori</option>
                                        <option value="museum" {{ request('category') == 'museum' ? 'selected' : '' }}>🏛️ Museum Sejarah</option>
                                        <option value="seni" {{ request('category') == 'seni' ? 'selected' : '' }}>🎨 Seni & Galeri</option>
                                        <option value="budaya" {{ request('category') == 'budaya' ? 'selected' : '' }}>🎭 Budaya & Etnik</option>
                                        <option value="alam" {{ request('category') == 'alam' ? 'selected' : '' }}>🌋 Alam & Sains</option>
                                        <option value="religius" {{ request('category') == 'religius' ? 'selected' : '' }}>⛪ Religius</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400 text-xs">
                                        ▾
                                    </div>
                                </div>
                            </div>

                            {{-- FILTER HARGA --}}
                            <div class="relative">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1 ml-1 flex items-center gap-1">
                                    <span>🎟️</span> <span>Tiket Masuk</span>
                                </label>
                                <div class="relative">
                                    <select name="price_range"
                                            x-model="price"
                                            @change="$el.form.submit()"
                                            class="w-full pl-3 pr-8 py-2 rounded-xl border border-[#EADBC8] bg-[#FAF7F2] hover:bg-white text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#B88A44]/30 focus:border-[#B88A44] transition cursor-pointer appearance-none shadow-sm">
                                        <option value="">Semua Harga</option>
                                        <option value="free" {{ request('price_range') == 'free' ? 'selected' : '' }}>🎟️ Tiket Gratis</option>
                                        <option value="under_25k" {{ request('price_range') == 'under_25k' ? 'selected' : '' }}>≤ Rp 25.000</option>
                                        <option value="25k_50k" {{ request('price_range') == '25k_50k' ? 'selected' : '' }}>Rp 25k - 50k</option>
                                        <option value="above_50k" {{ request('price_range') == 'above_50k' ? 'selected' : '' }}>&gt; Rp 50.000</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400 text-xs">
                                        ▾
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>

                {{-- 3. QUICK CHIPS / REKOMENDASI POPULER --}}
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider shrink-0 mr-1 flex items-center gap-1">
                        <span>🔥</span> <span>Paling Dicari:</span>
                    </span>
                    
                    <a href="{{ route('user.home') }}#featured"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ !request()->hasAny(['search', 'category', 'city', 'price_range']) ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        Semua
                    </a>

                    <a href="{{ route('user.home', ['category' => 'museum']) }}#featured"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('category') == 'museum' ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        🏛️ Sejarah
                    </a>

                    <a href="{{ route('user.home', ['category' => 'seni']) }}#featured"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('category') == 'seni' ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        🎨 Seni & Galeri
                    </a>

                    <a href="{{ route('user.home', ['category' => 'alam']) }}#featured"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('category') == 'alam' ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        🌋 Sains & Bumi
                    </a>

                    @if(!empty($cities))
                        @foreach(array_slice($cities, 0, 2) as $c)
                            <a href="{{ route('user.home', ['city' => $c]) }}#featured"
                               class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('city') == $c ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                                📍 {{ $c }}
                            </a>
                        @endforeach
                    @endif

                    <a href="{{ route('user.home', ['price_range' => 'free']) }}#featured"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('price_range') == 'free' ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        🎟️ Tiket Gratis
                    </a>
                </div>

                {{-- 4. ACTIVE FILTERS PILLS & DISMISS --}}
                @if(request()->hasAny(['search', 'category', 'city', 'price_range']) && (request('search') || request('category') || request('city') || request('price_range')))
                    <div class="flex flex-wrap items-center gap-1.5 pt-1 text-xs">
                        <span class="text-xs text-slate-500 font-medium">Filter aktif:</span>

                        @if(request('search'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-900 border border-amber-200 text-xs font-semibold">
                                <span>"{{ request('search') }}"</span>
                                <a href="{{ route('user.home', request()->except('search')) }}#featured" class="hover:text-red-600 font-bold ml-0.5">✕</a>
                            </span>
                        @endif

                        @if(request('city'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-900 border border-blue-200 text-xs font-semibold">
                                <span>📍 {{ request('city') }}</span>
                                <a href="{{ route('user.home', request()->except('city')) }}#featured" class="hover:text-red-600 font-bold ml-0.5">✕</a>
                            </span>
                        @endif

                        @if(request('category'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-900 border border-purple-200 text-xs font-semibold">
                                <span>{{ $categories[request('category')] ?? request('category') }}</span>
                                <a href="{{ route('user.home', request()->except('category')) }}#featured" class="hover:text-red-600 font-bold ml-0.5">✕</a>
                            </span>
                        @endif

                        @if(request('price_range'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-900 border border-emerald-200 text-xs font-semibold">
                                <span>Harga: {{ request('price_range') == 'free' ? 'Gratis' : (request('price_range') == 'under_25k' ? '≤ Rp 25k' : request('price_range')) }}</span>
                                <a href="{{ route('user.home', request()->except('price_range')) }}#featured" class="hover:text-red-600 font-bold ml-0.5">✕</a>
                            </span>
                        @endif

                        <a href="{{ route('user.home') }}#featured"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-red-600 hover:bg-red-50 transition border border-transparent hover:border-red-200">
                            <span>✕ Reset Semua</span>
                        </a>
                    </div>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN: SMOOTH ROUNDED HERO IMAGE --}}
        <div class="lg:col-span-6">
            <div class="relative w-full h-80 sm:h-96 lg:h-[460px] rounded-[36px] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.1)]">
                <img src="{{ asset('images/hero-museum.jpg') }}"
                     alt="Museum Heritage Indonesia"
                     class="w-full h-full object-cover object-center transform hover:scale-105 transition duration-700">
            </div>
        </div>

    </div>
</section>

{{-- LIST MUSEUM (SPACIOUS SLIDER) --}}
<section id="featured" class="max-w-7xl mx-auto py-12 sm:py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block mb-1">
                @if(request()->hasAny(['search', 'category', 'city', 'price_range']) && (request('search') || request('category') || request('city') || request('price_range')))
                    Hasil Pencarian ({{ $museums->count() }} Ditemukan)
                @else
                    Destinasi Terpilih ({{ $museums->count() }})
                @endif
            </span>
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#102A43]">
                @if(request('search'))
                    Museum untuk "{{ request('search') }}"
                @elseif(request('city'))
                    Koleksi Museum di {{ request('city') }}
                @elseif(request('category'))
                    Koleksi {{ $categories[request('category')] ?? 'Museum' }}
                @else
                    Koleksi Pilihan Nusantara
                @endif
            </h2>
        </div>

        <div class="flex gap-2">
            <button onclick="scrollLeftSlider()"
                    aria-label="Sebelumnya"
                    class="w-10 h-10 rounded-xl bg-white border border-[#EADBC8] text-[#102A43] font-bold shadow-sm hover:bg-slate-50 flex items-center justify-center transition">
                ←
            </button>
            <button onclick="scrollRightSlider()"
                    aria-label="Berikutnya"
                    class="w-10 h-10 rounded-xl bg-[#102A43] text-white font-bold shadow-sm hover:bg-[#0c2238] flex items-center justify-center transition">
                →
            </button>
        </div>
    </div>

    {{-- SLIDER --}}
    <div id="museumSlider"
         class="flex gap-6 overflow-x-auto no-scrollbar scroll-smooth pb-4">
        @forelse($museums as $museum)
            @php
                $isOpen = false;
                $todayBounds = $museum->operatingBoundsForDate(now());
                $nowTime = now()->format('H:i');
                if (!$museum->isClosedOnDate(now()) && $todayBounds && $nowTime >= $todayBounds['open'] && $nowTime <= $todayBounds['close']) {
                    $isOpen = true;
                }
                $lowestPrice = $museum->lowestTicketPrice();
            @endphp

            <div class="min-w-[280px] max-w-[280px] sm:min-w-[320px] sm:max-w-[320px] bg-white rounded-2xl overflow-hidden border border-[#EADBC8] shadow-sm hover:shadow-md transition flex flex-col justify-between shrink-0">
                {{-- IMAGE --}}
                <div class="relative w-full h-48 overflow-hidden bg-slate-100">
                    <img
                        src="{{ $museum->image
                            ? (Str::startsWith($museum->image, 'storage/')
                                ? asset($museum->image)
                                : asset('storage/' . $museum->image))
                            : asset('images/default-museum.jpg') }}"
                        alt="{{ $museum->name }}"
                        class="w-full h-full object-cover hover:scale-105 transition duration-300">

                    <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full text-[10px] font-bold backdrop-blur-sm shadow-sm {{ $isOpen ? 'bg-emerald-50 text-emerald-800' : 'bg-red-50 text-red-700' }}">
                        {{ $isOpen ? '● Buka' : '○ Tutup' }}
                    </span>

                    @auth
                        @php
                            $isWishlisted = $museum->wishlists->contains('user_id', auth()->id());
                        @endphp
                        <form action="{{ route('user.wishlist.store', $museum->id) }}" method="POST" class="absolute top-3 right-3">
                            @csrf
                            <button type="submit" class="w-8 h-8 rounded-full flex items-center justify-center shadow transition
                                {{ $isWishlisted ? 'bg-red-500 text-white' : 'bg-white/90 backdrop-blur-sm text-slate-600 hover:text-red-500' }}">
                                {{ $isWishlisted ? '♥' : '♡' }}
                            </button>
                        </form>
                    @endauth
                </div>

                {{-- CONTENT --}}
                <div class="p-5 flex flex-col flex-1 justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#B88A44]">
                                {{ $museum->category ?? 'Museum' }}
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="text-[10px] text-slate-500 font-medium">
                                📍 {{ explode(',', $museum->address)[0] ?? $museum->address }}
                            </span>
                        </div>

                        <h3 class="font-bold text-base text-[#102A43] line-clamp-1">
                            {{ $museum->name }}
                        </h3>

                        <p class="text-xs text-slate-500 line-clamp-2 mt-1 min-h-[32px]">
                            {{ $museum->address }}
                        </p>

                        <div class="mt-4 pt-3 border-t border-[#EADBC8]/60 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Tiket Masuk</span>
                            <span class="font-bold text-sm text-[#102A43]">
                                @if($lowestPrice !== null && $lowestPrice > 0)
                                    Rp {{ number_format($lowestPrice, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-4 pt-3 border-t border-[#EADBC8]/60 flex items-center gap-2">
                        <a href="{{ route('museum.detail', $museum->id) }}"
                           class="flex-1 py-2.5 text-center rounded-xl border border-[#102A43] text-[#102A43] font-semibold text-xs hover:bg-[#F6F1E8] transition">
                            Detail
                        </a>

                        <a href="{{ auth()->check() ? route('user.booking', $museum->id) : route('login') }}"
                           class="flex-1 py-2.5 text-center rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white font-semibold text-xs transition shadow-sm">
                            Pesan Tiket
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="w-full text-center py-16 px-4 bg-white rounded-3xl border border-[#EADBC8] shadow-sm">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-amber-50 text-[#B88A44] flex items-center justify-center text-3xl">
                    🔍
                </div>
                <h3 class="font-bold text-base text-[#102A43]">Museum Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                    Tidak ada museum yang sesuai dengan kata kunci atau filter pencarian Anda. Coba ganti kata kunci atau reset filter.
                </p>
                <div class="mt-4">
                    <a href="{{ route('user.home') }}#featured"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white text-xs font-bold transition shadow-sm">
                        <span>Lihat Semua Museum</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</section>

{{-- FAQ SECTION (ELEGAN & SIMPEL) --}}
<section class="max-w-4xl mx-auto py-16 sm:py-20 border-t border-[#EADBC8]" x-data="{ active: null }">

    <div class="text-center mb-12">
        <span class="text-[11px] font-bold uppercase tracking-[0.25em] text-[#B88A44] block mb-2">
            Pertanyaan Umum
        </span>
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#102A43]">
            Frequently Asked Questions
        </h2>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
            Informasi praktis seputar pemesanan tiket, kunjungan rombongan, dan validasi QR code di Itihasa.
        </p>
    </div>

    <div class="space-y-3.5">

        {{-- FAQ 1 --}}
        <div class="bg-white rounded-2xl border border-[#EADBC8] overflow-hidden shadow-sm transition">
            <button type="button"
                    @click="active = (active === 1 ? null : 1)"
                    class="w-full px-6 py-4 flex items-center justify-between text-left gap-4">
                <span class="font-bold text-sm sm:text-base text-[#102A43]">
                    Bagaimana cara memesan tiket museum di Itihasa?
                </span>
                <span class="w-7 h-7 rounded-full bg-[#F6F1E8] text-[#102A43] flex items-center justify-center font-bold text-xs shrink-0 transition-transform duration-200"
                      :class="active === 1 ? 'rotate-180 bg-[#102A43] text-white' : ''">
                    ↓
                </span>
            </button>
            <div x-show="active === 1" x-collapse x-cloak>
                <div class="px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-[#EADBC8]/50">
                    Pilih museum yang ingin dikunjungi, klik tombol <strong>Pesan Tiket</strong>, tentukan tanggal kunjungan dan jumlah tiket, lalu selesaikan pembayaran. E-Tiket dengan kode QR akan langsung aktif di akun Anda.
                </div>
            </div>
        </div>

        {{-- FAQ 2 --}}
        <div class="bg-white rounded-2xl border border-[#EADBC8] overflow-hidden shadow-sm transition">
            <button type="button"
                    @click="active = (active === 2 ? null : 2)"
                    class="w-full px-6 py-4 flex items-center justify-between text-left gap-4">
                <span class="font-bold text-sm sm:text-base text-[#102A43]">
                    Apakah tersedia tiket khusus untuk rombongan atau rombongan sekolah?
                </span>
                <span class="w-7 h-7 rounded-full bg-[#F6F1E8] text-[#102A43] flex items-center justify-center font-bold text-xs shrink-0 transition-transform duration-200"
                      :class="active === 2 ? 'rotate-180 bg-[#102A43] text-white' : ''">
                    ↓
                </span>
            </button>
            <div x-show="active === 2" x-collapse x-cloak>
                <div class="px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-[#EADBC8]/50">
                    Ya. Pemesanan tiket untuk <strong>6 orang atau lebih</strong> secara otomatis dialihkan ke reservasi rombongan. Pemesan cukup mengisi <strong>1 nama perwakilan / ketua rombongan</strong> tanpa perlu menginput puluhan nama anggota satu per satu.
                </div>
            </div>
        </div>

        {{-- FAQ 3 --}}
        <div class="bg-white rounded-2xl border border-[#EADBC8] overflow-hidden shadow-sm transition">
            <button type="button"
                    @click="active = (active === 3 ? null : 3)"
                    class="w-full px-6 py-4 flex items-center justify-between text-left gap-4">
                <span class="font-bold text-sm sm:text-base text-[#102A43]">
                    Bagaimana cara menggunakan E-Tiket saat tiba di lokasi museum?
                </span>
                <span class="w-7 h-7 rounded-full bg-[#F6F1E8] text-[#102A43] flex items-center justify-center font-bold text-xs shrink-0 transition-transform duration-200"
                      :class="active === 3 ? 'rotate-180 bg-[#102A43] text-white' : ''">
                    ↓
                </span>
            </button>
            <div x-show="active === 3" x-collapse x-cloak>
                <div class="px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-[#EADBC8]/50">
                    Buka menu <strong>Riwayat Tiket</strong> di profil akun Anda. Tunjukkan tiket kepada petugas di loket masuk museum untuk divalidasi.
                </div>
            </div>
        </div>

        {{-- FAQ 4 --}}
        <div class="bg-white rounded-2xl border border-[#EADBC8] overflow-hidden shadow-sm transition">
            <button type="button"
                    @click="active = (active === 4 ? null : 4)"
                    class="w-full px-6 py-4 flex items-center justify-between text-left gap-4">
                <span class="font-bold text-sm sm:text-base text-[#102A43]">
                    Metode pembayaran apa saja yang didukung?
                </span>
                <span class="w-7 h-7 rounded-full bg-[#F6F1E8] text-[#102A43] flex items-center justify-center font-bold text-xs shrink-0 transition-transform duration-200"
                      :class="active === 4 ? 'rotate-180 bg-[#102A43] text-white' : ''">
                    ↓
                </span>
            </button>
            <div x-show="active === 4" x-collapse x-cloak>
                <div class="px-6 pb-5 pt-1 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-[#EADBC8]/50">
                    Kami mendukung pembayaran terintegrasi Midtrans termasuk <strong>QRIS</strong> (BCA, Mandiri, BRI, GoPay, OVO, DANA, ShopeePay) serta <strong>Virtual Account Bank</strong> (BCA, Mandiri, BNI, BRI).
                </div>
            </div>
        </div>

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
        left: -340,
        behavior: 'smooth'
    });
}

function scrollRightSlider() {
    document.getElementById('museumSlider').scrollBy({
        left: 340,
        behavior: 'smooth'
    });
}
</script>

@endsection
