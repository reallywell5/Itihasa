<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Itihasa Control Panel</title>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/main.js'])
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

    {{-- SIDEBAR --}}
    <aside class="w-72 bg-white border-r border-blue-50 flex flex-col shrink-0">

        {{-- LOGO --}}
        <div class="h-16 px-6 flex items-center gap-3 border-b border-blue-50">
            <img src="{{ asset('images/logo-itihasa.png') }}"
                alt="Itihasa Logo"
                class="w-11 h-11 object-contain">

            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Itihasa</h1>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mt-1">
                    Admin Panel
                </p>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

            <p class="px-3 pt-2 pb-1 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Main Menu
            </p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('admin.dashboard') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('users.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0m6 0a4 4 0 11-8 0"/>
                </svg>
                <span>User</span>
            </a>

            <a href="{{ route('admin.petugas.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('admin.petugas.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 14c3.866 0 7 1.79 7 4v1H5v-1c0-2.21 3.134-4 7-4zM12 12a4 4 0 100-8 4 4 0 000 8z"/>
                </svg>
                <span>Petugas</span>
            </a>

            <p class="px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Data Operasional
            </p>

            <a href="{{ route('museums.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('museums.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
                <span>Museum</span>
            </a>

            <a href="{{ route('tickets.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('tickets.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 010 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 010-4V7a2 2 0 012-2z"/>
                </svg>
                <span>Tiket</span>
            </a>

            <p class="px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Keuangan
            </p>

            <a href="{{ route('payments.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('payments.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span>Pembayaran</span>
            </a>

            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
               {{ request()->routeIs('transactions.*') ? 'menu-active' : 'menu-normal' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>Transaksi</span>
            </a>

        </nav>

        {{-- LOGOUT BUTTON --}}
        <div class="p-4 border-t border-blue-50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all w-full text-left text-red-600 hover:bg-red-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- TOP NAVBAR --}}
        <header class="h-16 bg-white border-b border-blue-50 px-8 flex items-center justify-between shadow-sm">

            <div>
                <p class="text-xs text-slate-400 font-semibold">
                    Console / <span class="text-blue-600">@yield('title')</span>
                </p>
                <h2 class="text-lg font-bold text-slate-800">
                    @yield('title')
                </h2>
            </div>

            <div class="flex items-center gap-4">

                <div class="hidden md:flex items-center bg-blue-50 rounded-xl px-4 py-2 w-72">
                    <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                    </svg>

                    <input type="text"
                           placeholder="Search..."
                           class="bg-transparent outline-none text-sm w-full text-slate-600">
                </div>

                <button class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center hover:bg-blue-100 transition">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 17h5l-1.4-1.4A2 2 0 0118 14.17V11a6 6 0 10-12 0v3.17a2 2 0 01-.6 1.43L4 17h5m6 0a3 3 0 11-6 0h6z"/>
                    </svg>
                </button>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800 leading-none">
                            {{ Auth::user()->name ?? 'Administrator' }}
                        </p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">
                            {{ Auth::user()->role ?? 'Super Account' }}
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-md">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
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
