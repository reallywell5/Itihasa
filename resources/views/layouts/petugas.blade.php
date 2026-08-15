<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') - Petugas Loket Itihasa</title>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/main.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background: #f8fafc;
            -webkit-tap-highlight-color: transparent;
        }

        .menu-active {
            background: #059669;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);
        }

        .menu-active svg {
            color: #ffffff !important;
        }

        .menu-normal {
            color: #64748b;
        }

        .menu-normal svg {
            color: #94a3b8;
        }

        .menu-normal:hover {
            background: #ecfdf5;
            color: #059669;
        }

        .menu-normal:hover svg {
            color: #059669;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ mobileMenuOpen: false }" class="text-slate-700 font-sans antialiased">
<div class="flex min-h-screen">

    {{-- ======================================================= --}}
    {{-- DESKTOP SIDEBAR PETUGAS (lg+)                           --}}
    {{-- ======================================================= --}}
    <aside class="hidden lg:flex w-64 xl:w-72 bg-white border-r border-slate-100 flex-col shrink-0 sticky top-0 h-screen z-30 shadow-sm">
        {{-- LOGO --}}
        <div class="h-16 px-5 flex items-center gap-3 border-b border-slate-100">
            <img src="{{ asset('images/logo-itihasa.png') }}"
                 alt="Itihasa Logo"
                 class="w-9 h-9 object-contain">
            <div>
                <h1 class="text-base font-bold text-slate-800 leading-none">Itihasa</h1>
                <p class="text-[9px] uppercase tracking-widest text-emerald-600 font-bold mt-1">
                    🛡 Petugas Lapangan
                </p>
            </div>
        </div>

        {{-- NAVIGATION LINKS --}}
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <p class="px-3 pt-2 pb-1 text-[9px] font-bold uppercase tracking-widest text-slate-400">
                Operasional Loket
            </p>

            <a href="{{ route('petugas.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('petugas.dashboard') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('petugas.qrcodes.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('petugas.qrcodes.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h5v5H4V4zm11 0h5v5h-5V4zM4 15h5v5H4v-5zm11 0h2v2h-2v-2zm4 4h1v1h-1v-1z"/>
                </svg>
                <span>Daftar QR Code</span>
            </a>

            <a href="{{ route('petugas.validasi') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('petugas.validasi') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Validasi / Scan Tiket</span>
            </a>

            <a href="{{ route('petugas.pengunjung') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('petugas.pengunjung') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0"/>
                </svg>
                <span>Pengunjung Hari Ini</span>
            </a>

            <a href="{{ route('petugas.riwayat') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('petugas.riwayat') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Riwayat Scan</span>
            </a>

            <p class="px-3 pt-4 pb-1 text-[9px] font-bold uppercase tracking-widest text-slate-400">
                Pengaturan
            </p>

            <a href="{{ route('petugas.profil') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('petugas.profil*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1118.879 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Profil Saya</span>
            </a>
        </nav>

        {{-- LOGOUT BUTTON --}}
        <div class="p-3 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all w-full text-left text-red-600 hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ======================================================= --}}
    {{-- TABLET ICON SIDEBAR PETUGAS (md to lg)                  --}}
    {{-- ======================================================= --}}
    <aside class="hidden md:flex lg:hidden w-16 bg-white border-r border-slate-100 flex-col items-center py-4 shrink-0 sticky top-0 h-screen z-30 shadow-sm">
        <a href="{{ route('petugas.dashboard') }}" class="mb-4">
            <img src="{{ asset('images/logo-itihasa.png') }}" alt="Itihasa" class="w-8 h-8 object-contain">
        </a>

        <div class="flex-1 space-y-2 flex flex-col items-center">
            <a href="{{ route('petugas.dashboard') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('petugas.dashboard') ? 'menu-active' : 'menu-normal' }}" title="Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/></svg>
            </a>

            <a href="{{ route('petugas.qrcodes.index') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('petugas.qrcodes.*') ? 'menu-active' : 'menu-normal' }}" title="QR Code">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h5v5H4V4zm11 0h5v5h-5V4zM4 15h5v5H4v-5zm11 0h2v2h-2v-2zm4 4h1v1h-1v-1z"/></svg>
            </a>

            <a href="{{ route('petugas.validasi') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('petugas.validasi') ? 'menu-active' : 'menu-normal' }}" title="Validasi Tiket">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </a>

            <a href="{{ route('petugas.pengunjung') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('petugas.pengunjung') ? 'menu-active' : 'menu-normal' }}" title="Pengunjung">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0"/></svg>
            </a>

            <a href="{{ route('petugas.riwayat') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('petugas.riwayat') ? 'menu-active' : 'menu-normal' }}" title="Riwayat Scan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </a>

            <a href="{{ route('petugas.profil') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('petugas.profil*') ? 'menu-active' : 'menu-normal' }}" title="Profil">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1118.879 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="p-2.5 rounded-xl text-red-500 hover:bg-red-50" title="Logout">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </button>
        </form>
    </aside>

    {{-- ======================================================= --}}
    {{-- MOBILE SLIDE-OVER DRAWER PETUGAS (< md)                 --}}
    {{-- ======================================================= --}}
    <div x-cloak x-show="mobileMenuOpen"
         class="fixed inset-0 z-50 md:hidden flex"
         role="dialog" aria-modal="true">
        {{-- BACKDROP --}}
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- DRAWER CONTENT --}}
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative mr-16 flex-1 w-full max-w-xs bg-white flex flex-col justify-between shadow-2xl z-10">

            <div>
                {{-- DRAWER HEADER --}}
                <div class="h-16 px-5 flex items-center justify-between border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo-itihasa.png') }}" alt="Itihasa" class="w-8 h-8 object-contain">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Itihasa</h2>
                            <p class="text-[9px] uppercase font-bold text-emerald-600">
                                🛡 Petugas Loket
                            </p>
                        </div>
                    </div>
                    <button @click="mobileMenuOpen = false" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- DRAWER USER PROFILE CARD --}}
                <div class="p-4 bg-emerald-50/60 border-b border-emerald-100/60">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 truncate">
                                {{ Auth::user()->name ?? 'Petugas' }}
                            </p>
                            <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                🏛 {{ Auth::user()?->museum?->name ?? 'Petugas Lapangan' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- DRAWER LINKS --}}
                <nav class="p-3 space-y-1 overflow-y-auto max-h-[calc(100vh-230px)]">
                    <a href="{{ route('petugas.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('petugas.dashboard') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('petugas.qrcodes.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('petugas.qrcodes.*') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h5v5H4V4zm11 0h5v5h-5V4zM4 15h5v5H4v-5zm11 0h2v2h-2v-2zm4 4h1v1h-1v-1z"/></svg>
                        <span>Daftar QR Code</span>
                    </a>

                    <a href="{{ route('petugas.validasi') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('petugas.validasi') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Validasi / Scan Tiket</span>
                    </a>

                    <a href="{{ route('petugas.pengunjung') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('petugas.pengunjung') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0"/></svg>
                        <span>Pengunjung Hari Ini</span>
                    </a>

                    <a href="{{ route('petugas.riwayat') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('petugas.riwayat') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Riwayat Scan</span>
                    </a>

                    <a href="{{ route('petugas.profil') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('petugas.profil*') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1118.879 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Profil Saya</span>
                    </a>
                </nav>
            </div>

            {{-- DRAWER FOOTER LOGOUT --}}
            <div class="p-3 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 transition">
                        <span>Logout dari Akun</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- MAIN CONTENT AREA                                       --}}
    {{-- ======================================================= --}}
    <div class="flex-1 flex flex-col min-w-0 pb-16 md:pb-0">

        {{-- TOP NAVBAR --}}
        <header class="h-14 sm:h-16 bg-white border-b border-slate-100 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-20 shadow-sm">
            <div class="flex items-center gap-3">
                {{-- MOBILE HAMBURGER BUTTON --}}
                <button @click="mobileMenuOpen = true"
                        type="button"
                        class="md:hidden p-1.5 -ml-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-800 leading-tight">
                        @yield('title')
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-800 leading-none">
                        {{ Auth::user()->name ?? 'Petugas' }}
                    </p>
                    <p class="text-[9px] text-emerald-600 font-bold uppercase mt-0.5">
                        🛡 {{ Auth::user()?->museum?->name ?? 'Staff Lapangan' }}
                    </p>
                </div>

                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT CONTAINER --}}
        <main class="flex-1 p-3 sm:p-5 lg:p-6 overflow-y-auto">
            <div class="w-full max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

</div>

@stack('scripts')

</body>
</html>
