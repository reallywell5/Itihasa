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

            {{-- SEARCH & FILTER (FITUR F USER: KOTA, KATEGORI & RENTANG HARGA) --}}
            <div class="space-y-3">
                <form method="GET" action="{{ route('user.home') }}">
                    <div class="flex flex-col sm:flex-row gap-2 p-2 bg-white rounded-2xl border border-[#EADBC8] shadow-[0_8px_30px_rgba(16,42,67,0.06)] transition hover:border-[#B88A44]/60">
                        {{-- KEYWORD INPUT --}}
                        <div class="relative flex-1 flex items-center min-w-0">
                            <svg class="w-4 h-4 text-[#B88A44] absolute left-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text"
                                   name="search"
                                   placeholder="Cari nama museum..."
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-2 py-2.5 rounded-xl border-0 bg-transparent text-xs sm:text-sm text-slate-800 focus:outline-none placeholder:text-slate-400">
                        </div>

                        {{-- CITY FILTER --}}
                        <div class="sm:border-l sm:border-[#EADBC8]/80 sm:pl-1.5 flex items-center">
                            <select name="city"
                                    class="w-full sm:w-auto px-2.5 py-2.5 rounded-xl border-0 bg-[#F6F1E8]/70 text-xs text-slate-700 font-medium focus:outline-none cursor-pointer">
                                <option value="">Semua Kota</option>
                                @foreach($cities ?? [] as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                        📍 {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CATEGORY FILTER --}}
                        <div class="sm:border-l sm:border-[#EADBC8]/80 sm:pl-1.5 flex items-center">
                            <select name="category"
                                    class="w-full sm:w-auto px-2.5 py-2.5 rounded-xl border-0 bg-[#F6F1E8]/70 text-xs text-slate-700 font-medium focus:outline-none cursor-pointer">
                                <option value="">Semua Kategori</option>
                                <option value="museum" {{ request('category') == 'museum' ? 'selected' : '' }}>Museum Sejarah</option>
                                <option value="seni" {{ request('category') == 'seni' ? 'selected' : '' }}>Seni & Galeri</option>
                                <option value="budaya" {{ request('category') == 'budaya' ? 'selected' : '' }}>Budaya & Etnik</option>
                                <option value="alam" {{ request('category') == 'alam' ? 'selected' : '' }}>Alam & Sains</option>
                                <option value="religius" {{ request('category') == 'religius' ? 'selected' : '' }}>Religius</option>
                            </select>
                        </div>

                        {{-- PRICE RANGE FILTER --}}
                        <div class="sm:border-l sm:border-[#EADBC8]/80 sm:pl-1.5 flex items-center">
                            <select name="price_range"
                                    class="w-full sm:w-auto px-2.5 py-2.5 rounded-xl border-0 bg-[#F6F1E8]/70 text-xs text-slate-700 font-medium focus:outline-none cursor-pointer">
                                <option value="">Semua Harga</option>
                                <option value="free" {{ request('price_range') == 'free' ? 'selected' : '' }}>Gratis</option>
                                <option value="under_25k" {{ request('price_range') == 'under_25k' ? 'selected' : '' }}>&le; Rp 25.000</option>
                                <option value="25k_50k" {{ request('price_range') == '25k_50k' ? 'selected' : '' }}>Rp 25k - 50k</option>
                                <option value="above_50k" {{ request('price_range') == 'above_50k' ? 'selected' : '' }}>&gt; Rp 50.000</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white font-bold text-xs sm:text-sm transition shadow-sm shrink-0 flex items-center justify-center gap-1.5">
                            <span>Cari</span>
                            <span>→</span>
                        </button>
                    </div>
                </form>

                {{-- QUICK FILTER CHIPS (FITUR F USER: 1-KLIK CEPAT) --}}
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider shrink-0 mr-1">Filter Cepat:</span>
                    
                    <a href="{{ route('user.home') }}"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ !request()->hasAny(['search', 'category', 'city', 'price_range']) ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        Semua
                    </a>

                    <a href="{{ route('user.home', ['category' => 'museum']) }}"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('category') == 'museum' ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        🏛️ Sejarah
                    </a>

                    <a href="{{ route('user.home', ['category' => 'seni']) }}"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('category') == 'seni' ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        🎨 Seni & Galeri
                    </a>

                    <a href="{{ route('user.home', ['category' => 'alam']) }}"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('category') == 'alam' ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        🌋 Alam & Sains
                    </a>

                    @if(!empty($cities))
                        @foreach(array_slice($cities, 0, 2) as $c)
                            <a href="{{ route('user.home', ['city' => $c]) }}"
                               class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('city') == $c ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                                📍 {{ $c }}
                            </a>
                        @endforeach
                    @endif

                    <a href="{{ route('user.home', ['price_range' => 'free']) }}"
                       class="px-3 py-1 rounded-full text-xs font-semibold shrink-0 transition {{ request('price_range') == 'free' ? 'bg-[#102A43] text-white shadow-sm' : 'bg-white border border-[#EADBC8] text-slate-600 hover:bg-[#F6F1E8]' }}">
                        🎟️ Tiket Gratis
                    </a>
                </div>

                {{-- ACTIVE FILTERS INDICATOR & RESET --}}
                @if(request()->hasAny(['search', 'category', 'city', 'price_range']) && (request('search') || request('category') || request('city') || request('price_range')))
                    <div class="flex items-center gap-2 pt-1">
                        <span class="text-xs text-slate-500">
                            Menampilkan hasil untuk:
                            @if(request('search')) <strong class="text-[#102A43]">"{{ request('search') }}"</strong> @endif
                            @if(request('city')) <span class="px-2 py-0.5 rounded-md bg-white border text-[10px] font-bold text-slate-700">📍 {{ request('city') }}</span> @endif
                            @if(request('category')) <span class="px-2 py-0.5 rounded-md bg-white border text-[10px] font-bold text-slate-700">{{ $categories[request('category')] ?? request('category') }}</span> @endif
                            @if(request('price_range')) <span class="px-2 py-0.5 rounded-md bg-white border text-[10px] font-bold text-slate-700">Harga: {{ request('price_range') }}</span> @endif
                        </span>
                        <a href="{{ route('user.home') }}" class="text-xs font-bold text-red-600 hover:underline">
                            ✕ Reset Filter
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
            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block mb-1">Destinasi ({{ $museums->count() }})</span>
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#102A43]">
                Koleksi Pilihan
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
            <div class="w-full text-center py-12 text-slate-500 text-xs">
                Tidak ada museum yang sesuai dengan filter pencarian.
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
                    Ya. Pemesanan tiket untuk <strong>6 orang atau lebih</strong> secara otomatis dialihkan ke formulir reservasi rombongan. Anda dapat mengisi daftar nama peserta satu per satu atau menempel daftar nama sekaligus (bulk paste).
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
