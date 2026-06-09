<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Itihasa Control Panel</title>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-[#FAFAFA] text-zinc-800 font-sans antialiased">
    <div class="flex min-h-screen">

        <!-- Sidebar Utama Modern - Premium Zinc Style -->
        <aside class="w-64 bg-white border-r border-zinc-200/80 flex flex-col shrink-0">
            <div class="p-5 border-b border-zinc-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-xs tracking-wider shadow-xs">
                    AD
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-zinc-900 tracking-tight leading-none">Itihasa</span>
                    <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mt-1">Admin Panel</span>
                </div>
            </div>

            <!-- Sidebar Navigation Menu -->
            <nav class="flex-1 space-y-1 p-4 overflow-y-auto">
                <!-- Section Label -->
                <span class="px-3 text-[9px] font-bold uppercase tracking-widest text-zinc-400 block mb-2">Main Menu</span>

                <!-- 1. Menu Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">
                    <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Menu Users -->
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('users.*') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">
                    <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('users.*') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Users</span>
                </a>

                <a href="{{ route('admin.petugas.index') }}"
                    class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('petugas.*') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">
                        <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('petugas.*') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14c3.866 0 7 1.79 7 4v1H5v-1c0-2.21 3.134-4 7-4zM12 12a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                        <span>Petugas</span>
                </a>

                <!-- Section Label -->
                <span class="pt-4 px-3 text-[9px] font-bold uppercase tracking-widest text-zinc-400 block mb-2">Data Operasional</span>

                <!-- 3. Menu Museums -->
                <a href="{{ route('museums.index') }}"
                   class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('museums.*') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">
                    <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('museums.*') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Museums</span>
                </a>

                <!-- 4. Menu Reviews -->
                <a href="{{ route('reviews.index') }}"
                   class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('reviews.*') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">
                    <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('reviews.*') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span>Reviews</span>
                </a>

                <!-- 5. Menu Tickets -->
                <a href="{{ route('tickets.index') }}"
                   class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('tickets.*') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">
                    <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('tickets.*') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <span>Tickets</span>
                </a>

                        <!-- 6. Menu QR Code -->
                <a href="{{ route('qrcodes.index') }}"
                class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('qrcodes.*') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">

                    <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('qrcodes.*') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4h5v5H4V4zm11 0h5v5h-5V4zM4 15h5v5H4v-5zm8 0h2m2 0h4m-8 4h4m2-2h2m-6-6h6v2h-6v-2z"/>
                    </svg>

                    <span>QR Code</span>
                </a>

                <!-- Section Label -->
                <span class="pt-4 px-3 text-[9px] font-bold uppercase tracking-widest text-zinc-400 block mb-2">Keuangan</span>

                <!-- 6. Menu Pembayaran -->
                <a href="{{ route('payments.index') }}"
                   class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('payments.*') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">
                    <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('payments.*') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Pembayaran</span>
                </a>

                <!-- 7. Menu Transaksi -->
                <a href="{{ route('transactions.index') }}"
                   class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('transactions.*') ? 'bg-zinc-900 text-white font-semibold shadow-xs' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100/60' }}">
                    <svg class="w-4 h-4 shrink-0 transition-colors {{ request()->routeIs('transactions.*') ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-900' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Transactions</span>
                </a>
            </nav>
        </aside>

        <!-- Kanan: Konten Utama -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top Navbar Minimalis Elegan -->
            <header class="bg-white border-b border-zinc-200/80 h-16 px-8 flex justify-between items-center shrink-0">
                <!-- Breadcrumb Pratinjau Posisi -->
                <div class="flex items-center gap-2 text-xs font-semibold text-zinc-400 uppercase tracking-wider">
                    <span>Console</span>
                    <span class="text-zinc-300 font-normal">/</span>
                    <span class="text-zinc-900 font-bold">@yield('title')</span>
                </div>

                <!-- Informasi Akun Admin -->
                <div class="flex items-center gap-3">
                    <div class="flex flex-col text-right sm:flex">
                        <span class="text-sm font-bold text-zinc-900 leading-none">Administrator</span>
                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider mt-1">Super Account</span>
                    </div>
                    <!-- Lingkaran Profil Monokrom Murni -->
                    <div class="w-7 h-7 rounded bg-zinc-900 text-white font-mono text-xs flex items-center justify-center font-bold shadow-xs select-none">
                        A
                    </div>
                </div>
            </header>

            <!-- Container Suntikan Halaman Konten Konten -->
            <main class="flex-1 p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>
</body>
</html>
