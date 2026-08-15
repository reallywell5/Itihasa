@extends('layouts.app')

@section('title', $isSuperAdmin ? 'Pusat Pemantauan Ekosistem' : 'Dashboard ' . ($museum->name ?? 'Museum'))

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    @if($isSuperAdmin)
        {{-- ====================================================================== --}}
        {{-- SUPER ADMIN DASHBOARD (GLOBAL ECOSYSTEM MONITORING)                   --}}
        {{-- ====================================================================== --}}

        {{-- TOP HEADER & ACTIONS --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 print:hidden">
            <div class="lg:col-span-2 bg-gradient-to-br from-purple-950 via-purple-900 to-indigo-950 rounded-2xl p-6 text-white shadow-sm flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-purple-200 text-xs font-semibold backdrop-blur-sm mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Super Admin • Pusat Pemantauan & Audit Global
                    </div>
                    <h1 class="text-xl md:text-2xl font-bold tracking-tight">
                        Monitoring Ekosistem Museum Indonesia
                    </h1>
                    <p class="text-xs md:sm text-purple-200 mt-1 max-w-xl leading-relaxed">
                        Pantau seluruh aktivitas operasional, penanggung jawab museum, aliran omzet tiket, serta validasi kehadiran pengunjung se-Indonesia secara real-time.
                    </p>
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-2">
                    <a href="{{ route('users.index') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-purple-950 text-xs font-bold shadow hover:bg-purple-50 transition">
                        <span>👥 Kelola Admin & Petugas</span>
                    </a>

                    <a href="{{ route('museums.index') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-800/80 hover:bg-purple-800 text-white text-xs font-bold transition border border-purple-700/50">
                        <span>🏛 Direktori Museum</span>
                    </a>

                    <button onclick="window.print()"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/10 text-purple-100 hover:bg-white/20 text-xs font-bold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak Laporan</span>
                    </button>
                </div>
            </div>

            {{-- REVENUE CARD --}}
            <div class="bg-white rounded-2xl border border-purple-100 p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Omzet Transaksi Global</span>
                        <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">💰</span>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-800 mt-3">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Pemasukan bruto dari <strong>{{ number_format($paidTransactions) }}</strong> transaksi lunas di seluruh museum.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Transaksi Menunggu Bayar</span>
                    <span class="font-bold text-amber-600">{{ $pendingTransactions }} pesanan</span>
                </div>
            </div>
        </div>

        {{-- METRIK RINGKASAN GLOBAL (6 CARDS) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
            <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-purple-200 transition">
                <p class="text-[11px] font-semibold text-slate-400 uppercase">Museum</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $totalMuseums }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Mitra Terdaftar</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-purple-200 transition">
                <p class="text-[11px] font-semibold text-slate-400 uppercase">Admin</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $totalAdmins }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Pengelola Lokal</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-purple-200 transition">
                <p class="text-[11px] font-semibold text-slate-400 uppercase">Petugas Gate</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $totalStaff }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Staf Scanner</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-purple-200 transition">
                <p class="text-[11px] font-semibold text-slate-400 uppercase">Pengunjung</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $totalVisitors }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Akun Pengguna</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-purple-200 transition">
                <p class="text-[11px] font-semibold text-slate-400 uppercase">Kunjungan Real</p>
                <p class="text-xl font-bold text-emerald-600 mt-1">{{ $totalQrCodes }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Tiket Validasi Gate</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-purple-200 transition">
                <p class="text-[11px] font-semibold text-slate-400 uppercase">Kepuasan</p>
                <p class="text-xl font-bold text-amber-500 mt-1">{{ $averageRating }} ★</p>
                <p class="text-[10px] text-slate-400 mt-0.5">{{ $totalReviews }} Ulasan</p>
            </div>
        </div>

        {{-- GRAFIK ANALISIS PEMANTAUAN SUPER ADMIN --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- GRAFIK TREN OMZET BULANAN GLOBAL --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                            Tren Pendapatan & Omzet Global
                        </h3>
                        <p class="text-[11px] text-slate-400">
                            Grafik akumulasi pendapatan tiket lunas selama 6 bulan terakhir.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 text-[11px] font-bold">
                        📈 Periode 6 Bulan
                    </span>
                </div>
                <div class="h-64">
                    <canvas id="superAdminRevenueChart"></canvas>
                </div>
            </div>

            {{-- GRAFIK KONTRIBUSI OMZET PER MUSEUM --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-1">
                        Distribusi Kontribusi Omzet
                    </h3>
                    <p class="text-[11px] text-slate-400 mb-3">
                        Proporsi pemasukan dari masing-masing museum mitra.
                    </p>
                    <div class="h-56 relative flex items-center justify-center">
                        <canvas id="superAdminMuseumDonutChart"></canvas>
                    </div>
                </div>
                <p class="text-[10px] text-center text-slate-400 mt-2">
                    Berdasarkan transaksi tiket berstatus lunas (Paid).
                </p>
            </div>
        </div>

        {{-- TABEL PEMANTAUAN BREAKDOWN PERFORMA & ADMIN PER MUSEUM --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                        Matriks Kinerja & Penanggung Jawab Museum
                    </h3>
                    <p class="text-[11px] text-slate-400">
                        Rincian data operasional, staf gate, ulasan, transaksi, dan omzet tiap museum.
                    </p>
                </div>
                <span class="text-[11px] text-purple-700 font-bold bg-purple-50 px-3 py-1 rounded-lg">
                    Total {{ count($museumPerformances) }} Museum Aktif
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100 text-[10px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Nama Museum</th>
                            <th class="px-4 py-3">Admin Penanggung Jawab</th>
                            <th class="px-3 py-3 text-center">Petugas</th>
                            <th class="px-3 py-3 text-center">Katalog & Galeri</th>
                            <th class="px-3 py-3 text-center">Rating & Ulasan</th>
                            <th class="px-3 py-3 text-center">Okupansi Kuota</th>
                            <th class="px-3 py-3 text-center">Kunjungan Real</th>
                            <th class="px-4 py-3 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($museumPerformances as $mp)
                            <tr class="hover:bg-purple-50/30 transition align-middle">
                                <td class="px-4 py-3.5 font-bold text-slate-800">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            🏛
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 line-clamp-1">{{ $mp['name'] }}</p>
                                            <p class="text-[10px] text-slate-400 font-normal line-clamp-1">{{ $mp['address'] }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3.5">
                                    @if($mp['admin_name'])
                                        <div class="font-medium text-slate-800">
                                            {{ $mp['admin_name'] }}
                                            <span class="block text-[10px] text-slate-400 font-mono">{{ $mp['admin_email'] }}</span>
                                        </div>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-bold">
                                            Belum Ada Admin
                                        </span>
                                    @endif
                                </td>

                                <td class="px-3 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold text-[11px]">
                                        {{ $mp['staff_count'] }} Staf
                                    </span>
                                </td>

                                <td class="px-3 py-3.5 text-center">
                                    <span class="text-[11px] text-slate-600 font-semibold">
                                        {{ $mp['tickets_count'] }} Tiket • {{ $mp['galleries_count'] }} Foto
                                    </span>
                                </td>

                                <td class="px-3 py-3.5 text-center">
                                    <span class="inline-flex items-center gap-0.5 text-amber-500 font-bold text-[11px]">
                                        ★ {{ $mp['avg_rating'] }}
                                    </span>
                                    <span class="block text-[9px] text-slate-400">
                                        ({{ $mp['reviews_count'] }} ulasan)
                                    </span>
                                </td>

                                <td class="px-3 py-3.5 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-[11px] font-bold text-slate-800">{{ $mp['occupancy_rate'] }}%</span>
                                        <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden mt-1">
                                            <div class="h-full bg-purple-600 rounded-full" style="width: {{ min(100, $mp['occupancy_rate']) }}%"></div>
                                        </div>
                                        <span class="text-[9px] text-slate-400 mt-0.5">{{ number_format($mp['total_quota']) }} kuota</span>
                                    </div>
                                </td>

                                <td class="px-3 py-3.5 text-center font-bold text-emerald-600">
                                    {{ $mp['visitors_count'] }} Orang
                                </td>

                                <td class="px-4 py-3.5 text-right font-extrabold text-slate-900">
                                    Rp {{ number_format($mp['revenue'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada data museum terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- AKTIVITAS TRANSAKSI GLOBAL TERBARU --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                        Aktivitas Transaksi Masuk Terkini Se-Indonesia
                    </h3>
                    <p class="text-[11px] text-slate-400">
                        Pencatatan real-time pemesanan tiket pengunjung di seluruh museum mitra.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100 text-[10px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Invoice</th>
                            <th class="px-4 py-3">Pengunjung</th>
                            <th class="px-4 py-3">Museum Tujuan</th>
                            <th class="px-4 py-3">Total Bayar</th>
                            <th class="px-4 py-3 text-center">Status Pembayaran</th>
                            <th class="px-4 py-3 text-center">Validasi Tiket</th>
                            <th class="px-4 py-3 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentTransactions as $tx)
                            <tr class="hover:bg-purple-50/20 transition align-middle">
                                <td class="px-4 py-3 font-mono font-bold text-slate-800">
                                    {{ $tx->invoice_number ?? 'INV-' . $tx->id }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $tx->booking?->user?->name ?? 'Pengunjung' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold text-[10px]">
                                        🏛 {{ $tx->booking?->museum?->name ?? 'Museum' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-800">
                                    Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($tx->payment_status === 'paid')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                            ✓ Lunas
                                        </span>
                                    @elseif($tx->payment_status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
                                            ⏳ Menunggu
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-800 text-[10px] font-bold">
                                            ✗ Gagal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($tx->used_at)
                                        <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold">
                                            Sudah Masuk ({{ $tx->used_at->format('H:i') }})
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                                            Belum Digunakan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-slate-400 text-[11px]">
                                    {{ $tx->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                    Belum ada catatan transaksi masuk di sistem.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{-- ====================================================================== --}}
        {{-- ADMIN MUSEUM DASHBOARD (SCOPED 100% REAL FOR SPECIFIC MUSEUM)         --}}
        {{-- ====================================================================== --}}

        {{-- TOP HEADER --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 to-blue-950 rounded-2xl p-6 text-white shadow-sm flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-blue-200 text-xs font-semibold backdrop-blur-sm mb-3">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        Admin Museum • {{ $museum->name ?? 'Museum' }}
                    </div>
                    <h1 class="text-xl md:text-2xl font-bold tracking-tight">
                        Panel Operasional {{ $museum->name ?? 'Museum' }}
                    </h1>
                    <p class="text-xs md:text-sm text-blue-200 mt-1 max-w-xl leading-relaxed">
                        Kelola data tiket, unggah foto galeri koleksi, tanggapi ulasan pengunjung, serta monitor pembayaran transaksi masuk.
                    </p>
                </div>

                <div class="mt-5 flex flex-wrap gap-2">
                    <a href="{{ route('tickets.create') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold shadow hover:bg-blue-500 transition">
                        <span>+ Tambah Tiket</span>
                    </a>
                    <a href="{{ route('galleries.create') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/10 text-white text-xs font-bold hover:bg-white/20 transition">
                        <span>+ Upload Galeri</span>
                    </a>
                </div>
            </div>

            {{-- REVENUE CARD --}}
            <div class="bg-white rounded-2xl border border-blue-100 p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pendapatan Museum</span>
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">💰</span>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-800 mt-3">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Pemasukan riil dari {{ number_format($paidTransactions) }} transaksi yang telah lunas.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Transaksi Menunggu Bayar</span>
                    <span class="font-bold text-amber-600">{{ $pendingTransactions }} pesanan</span>
                </div>
            </div>
        </div>

        {{-- STATISTIK SETIAP MENU (TIKET, GALERI, ULASAN, PEMBAYARAN, TRANSAKSI) --}}
        <div>
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Statistik Setiap Menu</h3>
                <span class="text-[11px] text-slate-400">Data Real-time</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">

                {{-- 1. MENU TIKET --}}
                <a href="{{ route('tickets.index') }}" class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-blue-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase">Tiket</span>
                        <span class="text-sm group-hover:scale-110 transition">🎫</span>
                    </div>
                    <p class="text-xl font-bold text-slate-800 mt-2">{{ $totalTickets }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Kuota: {{ number_format($totalQuota) }}</p>
                </a>

                {{-- 2. MENU GALERI --}}
                <a href="{{ route('galleries.index') }}" class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-blue-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase">Galeri</span>
                        <span class="text-sm group-hover:scale-110 transition">🖼</span>
                    </div>
                    <p class="text-xl font-bold text-slate-800 mt-2">{{ $totalGalleries }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Foto Koleksi</p>
                </a>

                {{-- 3. MENU ULASAN --}}
                <a href="{{ route('admin.reviews.index') }}" class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-blue-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase">Ulasan</span>
                        <span class="text-sm group-hover:scale-110 transition">⭐</span>
                    </div>
                    <p class="text-xl font-bold text-slate-800 mt-2">{{ $averageRating }} ★</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $totalReviews }} Ulasan</p>
                </a>

                {{-- 4. MENU PEMBAYARAN --}}
                <a href="{{ route('payments.index') }}" class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-blue-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase">Pembayaran</span>
                        <span class="text-sm group-hover:scale-110 transition">💳</span>
                    </div>
                    <p class="text-xl font-bold text-slate-800 mt-2">{{ $totalPayments }}</p>
                    <p class="text-[10px] text-emerald-600 mt-0.5">{{ $successPayments }} Berhasil</p>
                </a>

                {{-- 5. MENU TRANSAKSI --}}
                <a href="{{ route('transactions.index') }}" class="group bg-white rounded-xl border border-slate-100 p-4 shadow-sm hover:border-blue-500 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase">Transaksi</span>
                        <span class="text-sm group-hover:scale-110 transition">📄</span>
                    </div>
                    <p class="text-xl font-bold text-slate-800 mt-2">{{ $totalTransactions }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $paidTransactions }} Lunas</p>
                </a>

                {{-- 6. QR CODE / SCAN VALIDASI --}}
                <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase">Scan QR</span>
                        <span class="text-sm">🔍</span>
                    </div>
                    <p class="text-xl font-bold text-emerald-600 mt-2">{{ $totalQrCodes }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Tiket Divalidasi</p>
                </div>

            </div>
        </div>

        {{-- GRAFIK DAN TRANSAKSI TERBARU (ADMIN MUSEUM) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- GRAFIK TRANSAKSI BULANAN --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Aktivitas Transaksi Bulanan</h3>
                        <p class="text-[11px] text-slate-400">Total pesanan tiket dalam 6 bulan terakhir</p>
                    </div>
                    <span class="text-xs text-blue-600 font-bold bg-blue-50 px-2.5 py-1 rounded-lg">Realtime</span>
                </div>
                <div class="h-64">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            {{-- TRANSAKSI TERBARU --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Transaksi Terbaru</h3>
                        <a href="{{ route('transactions.index') }}" class="text-[11px] text-blue-600 font-bold hover:underline">Semua →</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentTransactions as $tx)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-blue-50/40 transition">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-slate-800 truncate">
                                        {{ $tx->booking?->user?->name ?? 'Pengunjung' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        {{ $tx->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="text-right ml-3">
                                    <p class="text-xs font-bold text-slate-800">
                                        Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                                    </p>
                                    <span class="text-[9px] font-bold uppercase {{ $tx->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $tx->payment_status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-6">Belum ada transaksi</p>
                        @endforelse
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="mt-4 block text-center py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                    Lihat Seluruh Transaksi
                </a>
            </div>

        </div>

    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
@if($isSuperAdmin)
    // 1. CHART TREN REVENUE 6 BULAN (SUPER ADMIN)
    const revenueCtx = document.getElementById('superAdminRevenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyRevenue['labels']) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($monthlyRevenue['data']) !!},
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#7c3aed',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e1b4b',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        callbacks: {
                            label: function(context) {
                                return ' Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 10 },
                            callback: function(value) {
                                return 'Rp ' + (value >= 1000000 ? (value / 1000000).toFixed(1) + 'M' : (value / 1000) + 'k');
                            }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. CHART DONUT DISTRIBUSI PENDAPATAN PER MUSEUM (SUPER ADMIN)
    const donutCtx = document.getElementById('superAdminMuseumDonutChart');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($museumRevenueDistribution['labels']) !!},
                datasets: [{
                    data: {!! json_encode($museumRevenueDistribution['data']) !!},
                    backgroundColor: [
                        '#7c3aed',
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#6366f1',
                        '#ec4899'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            padding: 10,
                            font: { size: 10, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e1b4b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                cutout: '68%'
            }
        });
    }
@else
    // 3. CHART AKTIVITAS TRANSAKSI (ADMIN MUSEUM)
    const activityCtx = document.getElementById('activityChart');
    if (activityCtx) {
        new Chart(activityCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyTransactions['labels']) !!},
                datasets: [{
                    label: 'Transaksi',
                    data: {!! json_encode($monthlyTransactions['data']) !!},
                    backgroundColor: '#2563eb',
                    borderRadius: 8,
                    barThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }
@endif
</script>
@endpush
