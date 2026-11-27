<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Itihasa — @yield('title', 'Museum & Warisan Budaya Nusantara')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                    },
                    colors: {
                        obsidian: '#121212',
                        charcoal: '#1A1A1A',
                        muted: '#5A5A5A',
                        offwhite: '#F8F7F4',
                        ochre: '#B8860B',
                        gold: '#C59B27',
                        terracotta: '#8C3B2B',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 5.5rem;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            color: #1A1A1A;
            background-color: #FFFFFF;
            -webkit-tap-highlight-color: transparent;
        }
        .font-display, h1, h2, h3 {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .font-sans-clean {
            font-family: 'DM Sans', sans-serif;
        }
        @keyframes editorialFadeUp {
            0% {
                opacity: 0;
                transform: translateY(14px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-editorial {
            opacity: 0;
            animation: editorialFadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.22s; }
        .animate-delay-3 { animation-delay: 0.36s; }
        .animate-delay-4 { animation-delay: 0.5s; }
        @media (prefers-reduced-motion: reduce) {
            .animate-editorial {
                opacity: 1;
                animation: none;
            }
        }
        .nav-underline {
            position: relative;
        }
        .nav-underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0%;
            height: 1.5px;
            background-color: #B8860B;
            transition: width 0.3s ease;
        }
        .nav-underline:hover::after,
        .nav-underline.is-active::after {
            width: 100%;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

@php
    $isTransparentHeader = View::hasSection('transparent_header');
    $isFullBleed = View::hasSection('full_bleed') || $isTransparentHeader;
@endphp

<body x-data="{
        open: false,
        scrolled: false
      }"
      x-init="
        scrolled = window.scrollY > 36;
        window.addEventListener('scroll', () => { scrolled = window.scrollY > 36; }, { passive: true });
      "
      class="bg-white text-[#1A1A1A] antialiased min-h-screen flex flex-col justify-between selection:bg-[#1A1A1A] selection:text-[#F8F7F4]">

    {{-- ======================================================= --}}
    {{-- MOBILE SLIDE-OVER DRAWER                                --}}
    {{-- ======================================================= --}}
    <div x-cloak x-show="open"
         class="fixed inset-0 z-50 lg:hidden flex"
         role="dialog" aria-modal="true">

        {{-- BACKDROP --}}
        <div x-show="open"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="open = false"
             class="fixed inset-0 bg-[#121212]/60 backdrop-blur-xs"></div>

        {{-- DRAWER BODY --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative mr-12 flex-1 w-full max-w-xs bg-white flex flex-col justify-between border-r border-neutral-200 z-10">

            <div>
                {{-- DRAWER HEADER --}}
                <div class="h-18 px-6 py-4 flex items-center justify-between border-b border-neutral-200 bg-[#F8F7F4]">
                    <a href="{{ route('user.home') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-itihasa.png') }}" alt="Itihasa" class="w-8 h-8 object-contain">
                        <div>
                            <span class="font-serif font-semibold text-base tracking-tight text-[#1A1A1A] block leading-none uppercase">ITIHASA</span>
                            <span class="text-[9px] uppercase tracking-[0.22em] text-[#B8860B] font-semibold block mt-1">Museum Nusantara</span>
                        </div>
                    </a>
                    <button @click="open = false"
                            type="button"
                            aria-label="Tutup Menu"
                            class="p-1.5 rounded-sm text-[#5A5A5A] hover:text-[#1A1A1A] hover:bg-neutral-200/60 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- USER CARD IN DRAWER --}}
                @auth
                    <div class="px-6 py-4 bg-[#F8F7F4]/60 border-b border-neutral-200 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#1A1A1A] text-white flex items-center justify-center font-semibold text-xs shrink-0">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-[#1A1A1A] truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-[#5A5A5A] truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                @endauth

                {{-- DRAWER NAV LINKS --}}
                <nav class="p-5 space-y-1 text-sm font-medium">
                    <a href="{{ route('user.home') }}" @click="open = false"
                       class="flex items-center justify-between px-3 py-2.5 rounded-sm transition duration-300 {{ request()->routeIs('user.home') || request()->routeIs('landing') ? 'bg-[#F8F7F4] text-[#8C3B2B] font-semibold' : 'text-[#1A1A1A] hover:bg-[#F8F7F4]' }}">
                        <span>Beranda</span>
                        <span class="text-xs text-[#B8860B]">→</span>
                    </a>
                    <a href="{{ route('user.home') }}#kunjungan" @click="open = false"
                       class="flex items-center justify-between px-3 py-2.5 rounded-sm text-[#1A1A1A] hover:bg-[#F8F7F4] transition duration-300">
                        <span>Kunjungan</span>
                    </a>
                    <a href="{{ route('user.home') }}#pameran" @click="open = false"
                       class="flex items-center justify-between px-3 py-2.5 rounded-sm text-[#1A1A1A] hover:bg-[#F8F7F4] transition duration-300">
                        <span>Pameran &amp; Acara</span>
                    </a>
                    <a href="{{ route('user.home') }}#koleksi" @click="open = false"
                       class="flex items-center justify-between px-3 py-2.5 rounded-sm text-[#1A1A1A] hover:bg-[#F8F7F4] transition duration-300">
                        <span>Koleksi</span>
                    </a>
                    <a href="{{ route('user.home') }}#tentang" @click="open = false"
                       class="flex items-center justify-between px-3 py-2.5 rounded-sm text-[#1A1A1A] hover:bg-[#F8F7F4] transition duration-300">
                        <span>Tentang Kami</span>
                    </a>

                    <div class="my-3 border-t border-neutral-200 pt-3 space-y-1">
                        <a href="{{ auth()->check() ? route('user.wishlist') : route('login') }}" @click="open = false"
                           class="flex items-center justify-between px-3 py-2.5 rounded-sm transition duration-300 {{ request()->routeIs('user.wishlist') ? 'bg-[#F8F7F4] text-[#8C3B2B] font-semibold' : 'text-[#5A5A5A] hover:bg-[#F8F7F4] hover:text-[#1A1A1A]' }}">
                            <span>Daftar Simpan (Wishlist)</span>
                        </a>

                        @auth
                            <a href="{{ route('user.profile') }}" @click="open = false"
                               class="flex items-center justify-between px-3 py-2.5 rounded-sm transition duration-300 {{ request()->routeIs('user.profile') ? 'bg-[#F8F7F4] text-[#8C3B2B] font-semibold' : 'text-[#5A5A5A] hover:bg-[#F8F7F4] hover:text-[#1A1A1A]' }}">
                                <span>Profil &amp; Tiket Saya</span>
                            </a>
                        @endauth
                    </div>
                </nav>
            </div>

            {{-- DRAWER FOOTER ACTIONS --}}
            <div class="p-5 border-t border-neutral-200 space-y-3 bg-[#F8F7F4]/50">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 rounded-sm border border-neutral-300 text-xs font-semibold text-[#8C3B2B] hover:bg-neutral-100 transition duration-300 text-center">
                            Keluar Akun
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}"
                           class="py-2 text-center rounded-sm border border-neutral-300 text-xs font-semibold text-[#1A1A1A] hover:bg-neutral-100 transition duration-300">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="py-2 text-center rounded-sm bg-[#1A1A1A] text-xs font-semibold text-white hover:bg-[#8C3B2B] transition duration-300">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- INSTITUTIONAL STICKY / TRANSPARENT-ON-HERO NAVBAR       --}}
    {{-- ======================================================= --}}
    <header class="{{ $isTransparentHeader ? 'fixed top-0 inset-x-0' : 'sticky top-0' }} z-40 transition-all duration-300"
            :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled
                ? 'bg-gradient-to-b from-[#121212]/80 via-[#121212]/45 to-transparent text-white border-b border-white/10'
                : 'bg-white/95 backdrop-blur-md text-[#1A1A1A] border-b border-neutral-200 shadow-[0_1px_2px_rgba(0,0,0,0.03)]'">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
            <div class="h-18 sm:h-20 flex items-center justify-between gap-4">

                {{-- LEFT: HAMBURGER (MOBILE) + LOGO MUSEUM --}}
                <div class="flex items-center gap-3">
                    <button @click="open = true"
                            type="button"
                            aria-label="Buka Menu Navigasi"
                            class="lg:hidden p-2 -ml-2 rounded-sm transition duration-300"
                            :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white hover:bg-white/10' : 'text-[#1A1A1A] hover:bg-neutral-100'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <a href="{{ route('user.home') }}" class="flex items-center gap-3 group shrink-0">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-sm bg-white p-1.5 border border-neutral-200 flex items-center justify-center transition duration-300 group-hover:border-[#B8860B]">
                            <img src="{{ asset('images/logo-itihasa.png') }}" alt="Logo Museum Itihasa" class="w-full h-full object-contain">
                        </div>
                        <div class="leading-none">
                            <span class="font-serif text-lg sm:text-xl font-semibold tracking-tight uppercase block transition-colors duration-300"
                                  :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white' : 'text-[#1A1A1A]'">
                                ITIHASA
                            </span>
                            <span class="text-[9px] font-sans-clean uppercase tracking-[0.22em] block mt-0.5 font-medium transition-colors duration-300"
                                  :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-[#C59B27]' : 'text-[#B8860B]'">
                                Museum Nusantara
                            </span>
                        </div>
                    </a>
                </div>

                {{-- CENTER: CLEAN DESKTOP NAVIGATION MENU --}}
                <nav class="hidden lg:flex items-center gap-7 text-xs font-medium tracking-wide">
                    <a href="{{ route('user.home') }}"
                       class="nav-underline py-1 transition-colors duration-300 {{ request()->routeIs('user.home') || request()->routeIs('landing') ? 'is-active' : '' }}"
                       :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white/95 hover:text-white' : 'text-[#1A1A1A] hover:text-[#B8860B]'">
                        Beranda
                    </a>
                    <a href="{{ route('user.home') }}#kunjungan"
                       class="nav-underline py-1 transition-colors duration-300"
                       :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white/85 hover:text-white' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'">
                        Kunjungan
                    </a>
                    <a href="{{ route('user.home') }}#pameran"
                       class="nav-underline py-1 transition-colors duration-300"
                       :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white/85 hover:text-white' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'">
                        Pameran &amp; Acara
                    </a>
                    <a href="{{ route('user.home') }}#koleksi"
                       class="nav-underline py-1 transition-colors duration-300"
                       :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white/85 hover:text-white' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'">
                        Koleksi
                    </a>
                    <a href="{{ route('user.home') }}#tentang"
                       class="nav-underline py-1 transition-colors duration-300"
                       :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white/85 hover:text-white' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'">
                        Tentang Kami
                    </a>
                </nav>

                {{-- RIGHT: PROFILE / AUTH --}}
                <div class="flex items-center gap-2 sm:gap-3.5 shrink-0">

                    {{-- PROFILE / AUTH --}}
                    @auth
                        @if(file_exists(app_path('Services/NotificationService.php')) && Route::has('notifications.index'))
                            @include('components.notification-bell')
                        @endif

                        <a href="{{ route('user.wishlist') }}"
                           title="Wishlist"
                           class="hidden sm:inline-flex p-2 rounded-sm transition duration-300"
                           :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white/90 hover:text-[#C59B27]' : 'text-[#5A5A5A] hover:text-[#8C3B2B]'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                            </svg>
                        </a>

                        <a href="{{ route('user.profile') }}"
                           title="{{ Auth::user()->name }}"
                           class="w-8 h-8 rounded-full flex items-center justify-center font-semibold text-xs border transition duration-300 {{ request()->routeIs('user.profile') ? 'ring-2 ring-[#B8860B] ring-offset-1' : '' }}"
                           :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'bg-white/15 text-white border-white/40 hover:bg-white hover:text-[#1A1A1A]' : 'bg-[#1A1A1A] text-white border-[#1A1A1A] hover:bg-[#8C3B2B] hover:border-[#8C3B2B]'">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-xs font-medium px-2.5 py-1.5 transition duration-300"
                           :class="{{ $isTransparentHeader ? 'true' : 'false' }} && !scrolled ? 'text-white/90 hover:text-[#C59B27]' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'">
                            Masuk
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    {{-- ======================================================= --}}
    {{-- MAIN CONTENT                                            --}}
    {{-- ======================================================= --}}
    @if($isFullBleed)
        <main class="flex-1 w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
                @include('components.broadcast-alerts')
            </div>
            @yield('content')
        </main>
    @else
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-6 sm:py-10">
            @include('components.broadcast-alerts')
            @yield('content')
        </main>
    @endif

    {{-- ======================================================= --}}
    {{-- FOOTER (DARK #121212 & TERSTRUKTUR — 4 KOLOM)           --}}
    {{-- ======================================================= --}}
    <footer class="bg-[#121212] text-neutral-400 border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-14 sm:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 pb-12 border-b border-neutral-800">

                {{-- KOLOM 1: IDENTITAS & DESKRIPSI SINGKAT MUSEUM --}}
                <div class="lg:col-span-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-sm bg-white p-1.5 flex items-center justify-center">
                            <img src="{{ asset('images/logo-itihasa.png') }}" alt="Logo Museum Itihasa" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h2 class="font-serif text-lg font-semibold tracking-wide text-white uppercase leading-none">
                                ITIHASA
                            </h2>
                            <p class="text-[10px] uppercase tracking-[0.22em] text-[#C59B27] font-medium mt-1">
                                Museum &amp; Warisan Nusantara
                            </p>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-neutral-400 leading-relaxed max-w-sm">
                        Portal kuratorial dan reservasi tiket resmi museum sejarah, seni, serta cagar budaya di seluruh Nusantara. Mengabadikan memori peradaban melalui pengalaman kunjungan terintegrasi.
                    </p>
                </div>

                {{-- KOLOM 2: JAM BUKA & ALAMAT --}}
                <div class="lg:col-span-3 space-y-3">
                    <h3 class="font-serif text-sm font-semibold text-white tracking-wide uppercase">
                        Jam Buka &amp; Alamat
                    </h3>
                    <div class="space-y-2 text-xs leading-relaxed">
                        <div>
                            <p class="text-neutral-200 font-medium">Jam Operasional Umum</p>
                            <p>Selasa – Minggu: 08.00 – 16.00 WIB</p>
                            <p class="text-neutral-500">Senin &amp; Hari Libur Nasional: Tutup</p>
                        </div>
                        <div class="pt-1">
                            <p class="text-neutral-200 font-medium">Sekretariat Pusat</p>
                            <p>Jl. Medan Merdeka Barat No. 12,<br>Jakarta Pusat 10110, Indonesia</p>
                        </div>
                    </div>
                </div>

                {{-- KOLOM 3: TAUTAN CEPAT --}}
                <div class="lg:col-span-2 space-y-3">
                    <h3 class="font-serif text-sm font-semibold text-white tracking-wide uppercase">
                        Tautan Cepat
                    </h3>
                    <ul class="space-y-2 text-xs">
                        <li>
                            <a href="{{ route('user.home') }}#pameran" class="hover:text-[#C59B27] transition duration-300">
                                Pesan Tiket
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.home') }}#koleksi" class="hover:text-[#C59B27] transition duration-300">
                                Sorotan Koleksi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.home') }}#kunjungan" class="hover:text-[#C59B27] transition duration-300">
                                Informasi Kunjungan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('faq') }}" class="hover:text-[#C59B27] transition duration-300">
                                FAQ &amp; Bantuan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('privacy-policy') }}" class="hover:text-[#C59B27] transition duration-300">
                                Kebijakan Privasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terms-conditions') }}" class="hover:text-[#C59B27] transition duration-300">
                                Syarat &amp; Ketentuan
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- KOLOM 4: MEDIA SOSIAL & KONTAK --}}
                <div class="lg:col-span-3 space-y-3">
                    <h3 class="font-serif text-sm font-semibold text-white tracking-wide uppercase">
                        Media Sosial &amp; Kontak
                    </h3>
                    <div class="space-y-1.5 text-xs">
                        <p>
                            <span class="text-neutral-500">Surel:</span>
                            <a href="mailto:support@itihasa.com" class="text-neutral-200 hover:text-[#C59B27] transition duration-300">support@itihasa.com</a>
                        </p>
                        <p>
                            <span class="text-neutral-500">Telepon:</span>
                            <span class="text-neutral-200">+62 812-3456-7890</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <a href="#tentang" aria-label="Instagram"
                           class="w-8 h-8 rounded-sm border border-neutral-800 flex items-center justify-center text-neutral-400 hover:text-[#C59B27] hover:border-[#C59B27] transition duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#tentang" aria-label="YouTube"
                           class="w-8 h-8 rounded-sm border border-neutral-800 flex items-center justify-center text-neutral-400 hover:text-[#C59B27] hover:border-[#C59B27] transition duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <a href="#tentang" aria-label="X / Twitter"
                           class="w-8 h-8 rounded-sm border border-neutral-800 flex items-center justify-center text-neutral-400 hover:text-[#C59B27] hover:border-[#C59B27] transition duration-300">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="#tentang" aria-label="Facebook"
                           class="w-8 h-8 rounded-sm border border-neutral-800 flex items-center justify-center text-neutral-400 hover:text-[#C59B27] hover:border-[#C59B27] transition duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>

            {{-- BOTTOM BAR --}}
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-neutral-500">
                <p>© {{ date('Y') }} Museum Itihasa Nusantara. Seluruh hak cipta dilindungi.</p>
                <p>Kurasi &amp; Sistem Reservasi Museum Terintegrasi</p>
            </div>
        </div>
    </footer>

@stack('scripts')
@include('partials.sweetalert')
</body>
</html>