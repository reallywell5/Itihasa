<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Itihasa - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .nav-active {
            color: #102A43;
            position: relative;
        }

        .nav-active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 100%;
            height: 2px;
            background: #B88A44;
            border-radius: 999px;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body x-data="{ open: false }" class="bg-[#F6F1E8] text-slate-800 font-sans antialiased min-h-screen flex flex-col justify-between">

    {{-- ======================================================= --}}
    {{-- MOBILE SLIDE-OVER DRAWER (PENGUNJUNG)                   --}}
    {{-- ======================================================= --}}
    <div x-cloak x-show="open"
         class="fixed inset-0 z-50 md:hidden flex"
         role="dialog" aria-modal="true">

        {{-- BACKDROP --}}
        <div x-show="open"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="open = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- DRAWER BODY --}}
        <div x-show="open"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative mr-14 flex-1 w-full max-w-xs bg-white flex flex-col justify-between shadow-2xl z-10">

            <div>
                {{-- DRAWER HEADER --}}
                <div class="h-16 px-5 flex items-center justify-between border-b border-[#EADBC8]/60 bg-[#F6F1E8]/50">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-itihasa.png') }}" class="w-8 h-8 object-contain">
                        <div>
                            <h2 class="font-bold text-base tracking-wider text-[#102A43]">ITIHASA</h2>
                            <p class="text-[9px] uppercase tracking-widest text-[#B88A44] font-semibold">Heritage Museum</p>
                        </div>
                    </div>
                    <button @click="open = false" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- USER CARD IN DRAWER --}}
                @auth
                    <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-[#102A43] truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                @endauth

                {{-- DRAWER NAV LINKS --}}
                <nav class="p-4 space-y-1.5 text-sm font-semibold">
                    <a href="{{ route('user.home') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('user.home') ? 'bg-[#102A43] text-white' : 'text-slate-700 hover:bg-[#F6F1E8]' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/></svg>
                        <span>Home</span>
                    </a>

                    <a href="{{ auth()->check() ? route('user.wishlist') : route('login') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('user.wishlist') ? 'bg-[#102A43] text-white' : 'text-slate-700 hover:bg-[#F6F1E8]' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>Wishlist</span>
                    </a>

                    @auth
                        <a href="{{ route('user.profile') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('user.profile') ? 'bg-[#102A43] text-white' : 'text-slate-700 hover:bg-[#F6F1E8]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Profil & Tiket Saya</span>
                        </a>
                    @endauth
                </nav>
            </div>

            {{-- DRAWER FOOTER --}}
            <div class="p-4 border-t border-slate-100">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 transition">
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                @else
                    <div class="flex gap-2">
                        <a href="{{ route('login') }}" class="flex-1 py-2 text-center rounded-xl bg-slate-100 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="flex-1 py-2 text-center rounded-xl bg-[#102A43] text-xs font-bold text-white hover:bg-[#0c2238] transition">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- TOP NAVBAR PENGUNJUNG                                   --}}
    {{-- ======================================================= --}}
    <nav class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-[#EADBC8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 sm:h-20 flex items-center justify-between">

                {{-- LEFT (HAMBURGER + BRAND) --}}
                <div class="flex items-center gap-4 md:gap-8">
                    <button @click="open = true"
                            type="button"
                            class="md:hidden p-1.5 -ml-1 rounded-lg text-[#102A43] hover:bg-[#F6F1E8] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <a href="{{ route('user.home') }}" class="flex items-center gap-3 group">
                        <div class="relative w-10 h-10 sm:w-12 sm:h-12 bg-white border border-[#EADBC8] rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition">
                            <img src="{{ asset('images/logo-itihasa.png') }}" class="w-7 h-7 sm:w-8 sm:h-8 object-contain">
                        </div>
                        <div class="leading-tight">
                            <h1 class="text-base sm:text-lg font-bold tracking-[0.18em] text-[#102A43] uppercase">
                                Itihasa
                            </h1>
                            <p class="text-[8px] sm:text-[9px] uppercase tracking-[0.25em] text-[#B88A44] font-semibold">
                                Heritage Museum
                            </p>
                        </div>
                    </a>
                </div>

                {{-- DESKTOP MENU --}}
                <div class="hidden md:flex items-center gap-8 text-sm font-semibold">
                    <a href="{{ route('user.home') }}"
                       class="hover:text-[#B88A44] transition {{ request()->routeIs('user.home') ? 'nav-active' : '' }}">
                        Home
                    </a>
                    <a href="{{ auth()->check() ? route('user.wishlist') : route('login') }}"
                       class="hover:text-[#B88A44] transition {{ request()->routeIs('user.wishlist') ? 'nav-active' : '' }}">
                        Wishlist
                    </a>
                </div>

                {{-- RIGHT (PROFILE & AUTH) --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('user.profile') }}"
                           title="Profil Saya"
                           class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow hover:bg-[#0c2238] transition
                           {{ request()->routeIs('user.profile') ? 'ring-2 ring-[#B88A44] ring-offset-2' : '' }}">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-slate-600 hover:text-[#102A43] px-2 py-1">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 rounded-xl bg-[#102A43] text-white text-xs sm:text-sm font-semibold hover:bg-[#0c2238] transition shadow-sm">
                            Daftar
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="relative mt-24 bg-[#102A43] text-white overflow-hidden">

        {{-- Decorative Background --}}
        <div class="absolute top-0 left-0 w-56 lg:w-72 h-72 rounded-full border-[30px] border-[#B88A44]/10 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full border-[35px] border-[#B88A44]/10 translate-x-1/2 translate-y-1/2"></div>

        <div class="relative max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-16">

            <div class="grid
                grid-cols-1
                sm:grid-cols-2
                xl:grid-cols-4 gap-12">

                {{-- BRAND --}}
                <div>
                    <div class="flex flex-col sm:flex-row items-center gap-4 mb-5">

                        <img src="{{ asset('images/logo-itihasa.png') }}"
                            alt="Itihasa Logo"
                            class="w-12 h-12 md:w-14 md:h-14 object-contain bg-white rounded-2xl p-2 shadow-md">

                        <div>
                            <h2 class="text-2xl font-bold tracking-[0.18em] text-white">
                                ITIHASA
                            </h2>

                            <p class="text-xs uppercase tracking-[0.25em] text-[#B88A44] font-semibold mt-1">
                                Heritage Museum
                            </p>
                        </div>

                    </div>

                    <p class="text-slate-300 text-sm leading-relaxed">
                        Menjaga warisan budaya dan sejarah melalui pengalaman museum digital yang elegan, modern, dan intuitif.
                    </p>
                </div>

                {{-- QUICK LINKS --}}
                <div>
                    <h3 class="text-lg font-bold mb-5 text-white">
                        Quick Links
                    </h3>

                    <div class="space-y-3 text-sm text-slate-300">

                        <a href="{{ route('user.home') }}" class="block hover:text-[#B88A44] transition">
                            Home
                        </a>

                        <a href="{{ route('user.wishlist') }}" class="block hover:text-[#B88A44] transition">
                            Wishlist
                        </a>

                    </div>
                </div>

                {{-- SUPPORT --}}
                <div>
                    <h3 class="text-lg font-bold mb-5 text-white">
                        Support
                    </h3>

                    <div class="space-y-3 text-sm text-slate-300">

                        <a href="{{ route('faq') }}" class="block hover:text-[#B88A44] transition">
                            Help Center
                        </a>

                        <a href="{{ route('privacy-policy') }}" class="block hover:text-[#B88A44] transition">
                            Privacy Policy
                        </a>

                        <a href="{{ route('terms-conditions') }}" class="block hover:text-[#B88A44] transition">
                            Terms & Conditions
                        </a>

                    </div>
                </div>

                {{-- CONTACT --}}
                <div>
                    <h3 class="text-lg font-bold mb-5 text-white">
                        Contact
                    </h3>

                    <div class="space-y-4 text-sm text-slate-300">

                        <p>
                            Jakarta, Indonesia
                        </p>

                        <p>
                            support@itihasa.com
                        </p>

                        <p>
                            +62 812-3456-7890
                        </p>

                    </div>
                </div>

            </div>

            {{-- Divider --}}
            <div class="border-t border-white/10 mt-12 pt-8 flex text-center md:text-left items-center justify-between gap-4">

                <p class="text-sm text-slate-400">
                    © {{ date('Y') }} Itihasa. All rights reserved.
                </p>

                {{-- SOCIAL --}}
                <div class="flex items-center gap-4">

                    <a href="#" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-[#B88A44] transition">
                        F
                    </a>

                    <a href="#" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-[#B88A44] transition">
                        I
                    </a>

                    <a href="#" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-[#B88A44] transition">
                        X
                    </a>

                </div>

            </div>

        </div>

    </footer>

@stack('scripts')

@include('partials.sweetalert')
</body>
</html>
