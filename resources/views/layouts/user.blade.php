<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itihasa - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>

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
    </style>
</head>

<body class="bg-[#F6F1E8] text-slate-800 font-sans antialiased">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-[#EADBC8]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <div class="h-20 flex items-center justify-between">

                {{-- LEFT --}}
                <div class="flex items-center gap-12">

                    {{-- LOGO --}}
                    <a href="{{ route('user.home') }}"
                        class="flex items-center gap-4 group">

                            {{-- Logo Container --}}
                            <div class="relative">

                                <div class="absolute inset-0 rounded-2xl bg-[#B88A44]/10 blur-md group-hover:blur-lg transition"></div>

                                <div class="relative w-14 h-14 bg-white border border-[#EADBC8] rounded-2xl flex items-center justify-center shadow-sm">

                                    <img src="{{ asset('images/logo-itihasa.png') }}"
                                        class="w-9 h-9 object-contain">

                                </div>

                            </div>

                            {{-- Brand Text --}}
                            <div class="leading-tight">

                                <h1 class="text-[22px] font-bold tracking-[0.18em] text-[#102A43] uppercase">
                                    Itihasa
                                </h1>

                                <p class="text-[10px] uppercase tracking-[0.28em] text-[#B88A44] font-semibold mt-1">
                                    Heritage Museum
                                </p>

                            </div>

                        </a>

                    {{-- MENU --}}
                    <div class="hidden md:flex items-center gap-8 text-sm font-semibold">

                        <a href="{{ route('user.home') }}"
                        class="{{ request()->routeIs('user.home') ? 'nav-active' : '' }}">
                            Home
                        </a>

                        <a href="{{ auth()->check() ? route('user.wishlist') : route('login') }}"
                        class="{{ request()->routeIs('user.wishlist') ? 'nav-active' : '' }}">
                            Wishlist
                        </a>

                        <a href="{{ auth()->check() ? route('user.profile') : route('login') }}"
                        class="{{ request()->routeIs('user.profile') ? 'nav-active' : '' }}">
                            Profile
                        </a>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-4">

                    {{-- SEARCH --}}
                    <div class="hidden lg:flex items-center bg-[#F8F4EC] border border-[#EADBC8] rounded-xl px-4 py-2 w-72">
                        <svg class="w-4 h-4 text-[#B88A44]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.5 5.5a7.5 7.5 0 0011.15 11.15z"/>
                        </svg>

                        <input type="text"
                               placeholder="Search museum..."
                               class="bg-transparent outline-none px-3 text-sm w-full text-slate-700 placeholder:text-slate-400">
                    </div>

                    {{-- NOTIFICATION --}}
                    <button class="w-11 h-11 rounded-xl bg-white border border-[#EADBC8] flex items-center justify-center hover:bg-[#F8F4EC] transition">
                        <svg class="w-5 h-5 text-[#102A43]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z"/>
                        </svg>
                    </button>

                    {{-- PROFILE AVATAR --}}
                    <div class="w-11 h-11 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold shadow-md">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>

                </div>

            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="relative mt-24 bg-[#102A43] text-white overflow-hidden">

        {{-- Decorative Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 rounded-full border-[30px] border-[#B88A44]/10 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full border-[35px] border-[#B88A44]/10 translate-x-1/2 translate-y-1/2"></div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-16">

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12">

                {{-- BRAND --}}
                <div>
                    <div class="flex items-center gap-4 mb-5">

                        <img src="{{ asset('images/logo-itihasa.png') }}"
                            alt="Itihasa Logo"
                            class="w-14 h-14 object-contain bg-white rounded-2xl p-2 shadow-md">

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

                        <a href="{{ route('user.profile') }}" class="block hover:text-[#B88A44] transition">
                            Profile
                        </a>

                    </div>
                </div>

                {{-- SUPPORT --}}
                <div>
                    <h3 class="text-lg font-bold mb-5 text-white">
                        Support
                    </h3>

                    <div class="space-y-3 text-sm text-slate-300">

                        <a href="#" class="block hover:text-[#B88A44] transition">
                            Help Center
                        </a>

                        <a href="#" class="block hover:text-[#B88A44] transition">
                            Privacy Policy
                        </a>

                        <a href="#" class="block hover:text-[#B88A44] transition">
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
            <div class="border-t border-white/10 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">

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
