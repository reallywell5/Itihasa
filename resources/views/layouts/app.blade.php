<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') - Itihasa Control Panel</title>

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
            background: {{ Auth::user()?->isSuperAdmin() ? '#7c3aed' : '#2563eb' }};
            color: #ffffff !important;
            box-shadow: 0 4px 14px {{ Auth::user()?->isSuperAdmin() ? 'rgba(124, 58, 237, 0.25)' : 'rgba(37, 99, 235, 0.25)' }};
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
            background: {{ Auth::user()?->isSuperAdmin() ? '#faf5ff' : '#eff6ff' }};
            color: {{ Auth::user()?->isSuperAdmin() ? '#7c3aed' : '#2563eb' }};
        }

        .menu-normal:hover svg {
            color: {{ Auth::user()?->isSuperAdmin() ? '#7c3aed' : '#2563eb' }};
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ mobileMenuOpen: false }" class="text-slate-700 font-sans antialiased">
<div class="flex min-h-screen">

    {{-- ======================================================= --}}
    {{-- DESKTOP SIDEBAR (lg+)                                   --}}
    {{-- ======================================================= --}}
    <aside class="hidden lg:flex w-64 xl:w-72 bg-white border-r border-slate-100 flex-col shrink-0 sticky top-0 h-screen z-30 shadow-sm">
        {{-- LOGO --}}
        <div class="h-16 px-5 flex items-center gap-3 border-b border-slate-100">
            <img src="{{ asset('images/logo-itihasa.png') }}"
                 alt="Itihasa Logo"
                 class="w-9 h-9 object-contain">
            <div>
                <h1 class="text-base font-bold text-slate-800 leading-none">Itihasa</h1>
                <p class="text-[9px] uppercase tracking-widest text-slate-400 font-bold mt-1">
                    {{ Auth::user()?->isSuperAdmin() ? 'Super Admin' : 'Admin Panel' }}
                </p>
            </div>
        </div>

        {{-- NAVIGATION LINKS --}}
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <p class="px-3 pt-2 pb-1 text-[9px] font-bold uppercase tracking-widest text-slate-400">
                Menu Utama
            </p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('admin.dashboard') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/>
                </svg>
                <span>Dashboard</span>
            </a>

            @if(Auth::user()?->isSuperAdmin())
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
                   {{ request()->routeIs('users.*') ? 'menu-active' : 'menu-normal' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0m6 0a4 4 0 11-8 0"/>
                    </svg>
                    <span>User & Admin</span>
                </a>
            @endif

            <p class="px-3 pt-4 pb-1 text-[9px] font-bold uppercase tracking-widest text-slate-400">
                Data Operasional
            </p>

            @if(Auth::user()?->isAdmin())
                <a href="{{ route('admin.petugas.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
                   {{ request()->routeIs('admin.petugas.*') ? 'menu-active' : 'menu-normal' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Petugas Loket</span>
                </a>
            @endif

            <a href="{{ route('tickets.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('tickets.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 010 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 010-4V7a2 2 0 012-2z"/>
                </svg>
                <span>Tiket</span>
            </a>

            <a href="{{ route('galleries.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('galleries.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/>
                </svg>
                <span>Galeri</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
                {{ request()->routeIs('admin.reviews.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.539 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span>Ulasan</span>
            </a>

            <p class="px-3 pt-4 pb-1 text-[9px] font-bold uppercase tracking-widest text-slate-400">
                Keuangan
            </p>

            <a href="{{ route('payments.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('payments.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span>Pembayaran</span>
            </a>

            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all
               {{ request()->routeIs('transactions.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>Transaksi</span>
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
    {{-- TABLET ICON SIDEBAR (md to lg)                          --}}
    {{-- ======================================================= --}}
    <aside class="hidden md:flex lg:hidden w-16 bg-white border-r border-slate-100 flex-col items-center py-4 shrink-0 sticky top-0 h-screen z-30 shadow-sm">
        <a href="{{ route('admin.dashboard') }}" class="mb-4">
            <img src="{{ asset('images/logo-itihasa.png') }}" alt="Itihasa" class="w-8 h-8 object-contain">
        </a>

        <div class="flex-1 space-y-2 flex flex-col items-center">
            <a href="{{ route('admin.dashboard') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'menu-active' : 'menu-normal' }}" title="Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/></svg>
            </a>

            @if(Auth::user()?->isSuperAdmin())
                <a href="{{ route('users.index') }}"
                   class="p-2.5 rounded-xl transition {{ request()->routeIs('users.*') ? 'menu-active' : 'menu-normal' }}" title="User & Admin">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0m6 0a4 4 0 11-8 0"/></svg>
                </a>
            @endif

            @if(Auth::user()?->isAdmin())
                <a href="{{ route('admin.petugas.index') }}"
                   class="p-2.5 rounded-xl transition {{ request()->routeIs('admin.petugas.*') ? 'menu-active' : 'menu-normal' }}" title="Petugas Loket">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </a>
            @endif

            <a href="{{ route('tickets.index') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('tickets.*') ? 'menu-active' : 'menu-normal' }}" title="Tiket">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 010 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 010-4V7a2 2 0 012-2z"/></svg>
            </a>

            <a href="{{ route('galleries.index') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('galleries.*') ? 'menu-active' : 'menu-normal' }}" title="Galeri">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
            </a>

            <a href="{{ route('admin.reviews.index') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('admin.reviews.*') ? 'menu-active' : 'menu-normal' }}" title="Ulasan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.539 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </a>

            <a href="{{ route('payments.index') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('payments.*') ? 'menu-active' : 'menu-normal' }}" title="Pembayaran">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </a>

            <a href="{{ route('transactions.index') }}"
               class="p-2.5 rounded-xl transition {{ request()->routeIs('transactions.*') ? 'menu-active' : 'menu-normal' }}" title="Transaksi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
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
    {{-- MOBILE SLIDE-OVER DRAWER (< md)                         --}}
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
                            <p class="text-[9px] uppercase font-bold text-slate-400">
                                {{ Auth::user()?->isSuperAdmin() ? 'Super Admin' : 'Admin Panel' }}
                            </p>
                        </div>
                    </div>
                    <button @click="mobileMenuOpen = false" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- DRAWER USER PROFILE CARD --}}
                <div class="p-4 bg-slate-50/80 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full {{ Auth::user()?->isSuperAdmin() ? 'bg-purple-600' : 'bg-blue-600' }} text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 truncate">
                                {{ Auth::user()->name }}
                            </p>
                            <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold uppercase {{ Auth::user()?->isSuperAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ Auth::user()?->isSuperAdmin() ? 'Super Admin' : (Auth::user()?->museum?->name ?? 'Admin') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- DRAWER LINKS --}}
                <nav class="p-3 space-y-1 overflow-y-auto max-h-[calc(100vh-230px)]">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('admin.dashboard') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/></svg>
                        <span>Dashboard</span>
                    </a>

                    @if(Auth::user()?->isSuperAdmin())
                        <a href="{{ route('users.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                           {{ request()->routeIs('users.*') ? 'menu-active' : 'menu-normal' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0m6 0a4 4 0 11-8 0"/></svg>
                            <span>User & Admin</span>
                        </a>
                    @endif

                    @if(Auth::user()?->isAdmin())
                        <a href="{{ route('admin.petugas.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                           {{ request()->routeIs('admin.petugas.*') ? 'menu-active' : 'menu-normal' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Petugas Loket</span>
                        </a>
                    @endif

                    <a href="{{ route('tickets.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('tickets.*') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 010 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 010-4V7a2 2 0 012-2z"/></svg>
                        <span>Tiket</span>
                    </a>

                    <a href="{{ route('galleries.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('galleries.*') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                        <span>Galeri</span>
                    </a>

                    <a href="{{ route('admin.reviews.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('admin.reviews.*') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.539 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        <span>Ulasan</span>
                    </a>

                    <a href="{{ route('payments.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('payments.*') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Pembayaran</span>
                    </a>

                    <a href="{{ route('transactions.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold transition
                       {{ request()->routeIs('transactions.*') ? 'menu-active' : 'menu-normal' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Transaksi</span>
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
                        {{ Auth::user()->name ?? 'Administrator' }}
                    </p>
                    <div class="mt-0.5">
                        @if(Auth::user()?->isSuperAdmin())
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-purple-100 text-purple-700">
                                Super Admin
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-blue-100 text-blue-700">
                                Admin • {{ Auth::user()?->museum?->name ?? 'Museum' }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full {{ Auth::user()?->isSuperAdmin() ? 'bg-purple-600' : 'bg-blue-600' }} text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
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
