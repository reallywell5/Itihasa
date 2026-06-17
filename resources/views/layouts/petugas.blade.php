<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Petugas Panel</title>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            background: #f5f7fb;
        }

        .menu-active {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
        }

        .menu-active svg {
            color: #ffffff;
        }

        .menu-normal {
            color: #64748b;
        }

        .menu-normal svg {
            color: #94a3b8;
        }

        .menu-normal:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        .menu-normal:hover svg {
            color: #2563eb;
        }

        aside {
            box-shadow: 4px 0 25px rgba(15, 23, 42, 0.04);
        }
    </style>
</head>

<body class="text-slate-700 font-sans antialiased">
<div class="flex min-h-screen">

    {{-- SIDEBAR PETUGAS --}}
    <aside class="w-72 bg-white border-r border-blue-50 flex flex-col shrink-0">

        {{-- LOGO --}}
        <div class="h-16 px-6 flex items-center gap-3 border-b border-blue-50">
            <img src="{{ asset('images/logo-itihasa.png') }}"
                alt="Itihasa Logo"
                class="w-11 h-11 object-contain">

            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Itihasa</h1>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mt-1">
                    Petugas Panel
                </p>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

            <p class="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Operasional
            </p>

            <a href="{{ route('petugas.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('petugas.dashboard') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('petugas.qrcodes.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('petugas.qrcodes.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 4h5v5H4V4zm11 0h5v5h-5V4zM4 15h5v5H4v-5zm11 0h2v2h-2v-2zm4 4h1v1h-1v-1z"/>
                </svg>
                <span>QR Code</span>
            </a>

            <a href="{{ route('petugas.validasi') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('petugas.validasi') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Validasi Tiket</span>
            </a>

            <a href="{{ route('petugas.pengunjung') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('petugas.pengunjung') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0"/>
                </svg>
                <span>Pengunjung Hari Ini</span>
            </a>

            <a href="{{ route('petugas.riwayat') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('petugas.riwayat') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Riwayat Scan</span>
            </a>

            <a href="{{ route('petugas.profil') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                {{ request()->routeIs('petugas.profil') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.121 17.804A9 9 0 1118.879 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Profil Saya</span>
            </a>

        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- TOP NAVBAR --}}
        <header class="h-16 bg-white border-b border-blue-50 px-8 flex items-center justify-between shadow-sm">

            <div>
                <p class="text-xs text-slate-400 font-semibold">
                    Petugas / <span class="text-blue-600">@yield('title')</span>
                </p>
                <h2 class="text-lg font-bold text-slate-800">
                    @yield('title')
                </h2>
            </div>

            <div class="flex items-center gap-4">

                <div class="hidden md:flex items-center bg-blue-50 rounded-xl px-4 py-2 w-72">
                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                    </svg>

                    <input type="text"
                           placeholder="Search..."
                           class="bg-transparent outline-none text-sm w-full text-slate-600">
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800 leading-none">
                            Petugas
                        </p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">
                            Staff Account
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-md">
                        P
                    </div>
                </div>

            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-8 overflow-y-auto">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>
