@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')

<section class="max-w-7xl mx-auto py-8 sm:py-12">

    {{-- HEADER PROFIL --}}
    <div class="mb-8">
        <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#B88A44] block mb-1">Akun Saya</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#102A43]">
            Profil & Paspor Pengunjung
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Kelola tiket museum, kumpulkan stempel paspor digital, dan buka lencana penjelajah budaya nusantara.
        </p>
    </div>

    {{-- GRID UTAMA --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- LEFT SIDEBAR (USER CARD & STATISTIK) --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl border border-[#EADBC8] overflow-hidden shadow-sm">
                {{-- AVATAR & NAMA --}}
                <div class="p-6 text-center border-b border-[#EADBC8]/70 bg-gradient-to-b from-[#FAF7F2] to-white">
                    <div class="w-20 h-20 mx-auto rounded-full bg-[#102A43] text-white flex items-center justify-center text-2xl font-bold border-4 border-white shadow-md mb-3 ring-2 ring-[#B88A44]/30">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <h2 class="font-bold text-lg text-[#102A43]">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $user->email }}
                    </p>

                    <div class="mt-3 flex items-center justify-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-[#102A43] text-white shadow-sm">
                            <span>🏛️</span>
                            <span>{{ $passportData['rank_name'] }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            ⭐ {{ $passportData['unlocked_badges_count'] }}/{{ $passportData['total_badges'] }} Lencana
                        </span>
                    </div>
                </div>

                {{-- STATISTIK RINGKAS --}}
                <div class="p-6 space-y-3.5 text-xs">
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Total Transaksi</span>
                        <span class="font-bold text-[#102A43]">
                            {{ $totalTransactions }} Pesanan
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-slate-500">
                        <span>Total Pengunjung (Pax)</span>
                        <span class="font-bold text-[#102A43]">
                            {{ $totalVisitors }} Orang
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-slate-500">
                        <span>Stempel Paspor</span>
                        <span class="font-bold text-[#B88A44]">
                            {{ $passportData['total_stamps'] }} Stempel
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-slate-500">
                        <span>Favorit Wishlist</span>
                        <span class="font-bold text-[#102A43]">
                            {{ $wishlists->count() }} Museum
                        </span>
                    </div>
                </div>

                {{-- AKSI --}}
                <div class="border-t border-[#EADBC8]/70 p-4 space-y-2 bg-[#F6F1E8]/30">
                    <a href="{{ route('user.profile.edit') }}"
                       class="w-full flex justify-center py-2.5 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white font-bold text-xs transition shadow-sm">
                        Edit Profil
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2 rounded-xl border border-red-200 text-red-600 font-bold text-xs hover:bg-red-50 transition text-center">
                            Keluar Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT CONTENT (MAIN NAVIGATION: TIKET vs PASPOR BUDAYA) --}}
        <div class="lg:col-span-8 space-y-6" x-data="{ mainTab: 'tiket', cancelModalOpen: false, cancelBookingId: null, cancelMuseumName: '', reviewModalOpen: false, reviewBookingId: null, reviewMuseumName: '', photoCount: 0, previews: [] }">

            {{-- TOP NAVIGATION BUTTONS --}}
            <div class="flex items-center gap-3 p-1.5 bg-white rounded-2xl border border-[#EADBC8] shadow-sm">
                <button type="button"
                        @click="mainTab = 'tiket'"
                        :class="mainTab === 'tiket' ? 'bg-[#102A43] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#F6F1E8] font-semibold'"
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-2">
                    <span>🎟️ E-Tiket & Riwayat</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px]" :class="mainTab === 'tiket' ? 'bg-white/20 text-white' : 'bg-[#F6F1E8] text-[#102A43]'">
                        {{ $transactions->count() }}
                    </span>
                </button>

                <button type="button"
                        @click="mainTab = 'paspor'"
                        :class="mainTab === 'paspor' ? 'bg-[#102A43] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#F6F1E8] font-semibold'"
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-2">
                    <span>📜 Paspor & Lencana</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-[#B88A44] text-white font-bold">
                        {{ $passportData['unlocked_badges_count'] }} 🏆
                    </span>
                </button>
            </div>

            {{-- ======================================================== --}}
            {{-- TAB 1: E-TIKET & RIWAYAT KUNJUNGAN                     --}}
            {{-- ======================================================== --}}
            <div x-show="mainTab === 'tiket'" class="space-y-6">

                {{-- MENUNGGU PEMBAYARAN --}}
                @if($pendingTransactions->count() > 0)
                <div>
                    <div class="mb-3">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700 block">Menunggu Pembayaran</span>
                        <h2 class="font-display text-lg font-bold text-[#102A43]">
                            Transaksi Belum Selesai
                        </h2>
                    </div>

                    <div class="space-y-2.5">
                        @foreach($pendingTransactions as $pending)
                            @php
                                $isExpiringSoon = $pending->expired_at && now()->lt($pending->expired_at);
                            @endphp

                            <div class="bg-white rounded-2xl border border-amber-300 p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-bold text-sm text-[#102A43] truncate">
                                            {{ $pending->booking->museum->name ?? '-' }}
                                        </h3>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 text-amber-900 shrink-0">
                                            Pending
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500">
                                        Kunjungan: {{ \Carbon\Carbon::parse($pending->booking->visit_date)->translatedFormat('d M Y') }}
                                        • Invoice: <span class="font-mono text-slate-700">{{ $pending->invoice_code }}</span>
                                        • Total: <strong class="text-[#B88A44]">Rp {{ number_format($pending->total_amount, 0, ',', '.') }}</strong>
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    @if($isExpiringSoon)
                                        <button type="button"
                                                @click="cancelModalOpen = true; cancelBookingId = {{ $pending->booking->id }}; cancelMuseumName = '{{ addslashes($pending->booking->museum->name ?? '-') }}'"
                                                class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                                            Batal
                                        </button>
                                        <a href="{{ route('user.payment.show3', $pending->id) }}"
                                           class="px-4 py-1.5 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white text-xs font-bold transition shadow-sm">
                                            Bayar Sekarang →
                                        </a>
                                    @else
                                        <span class="px-3 py-1 rounded-xl bg-gray-100 text-gray-500 text-xs font-semibold">
                                            Waktu Habis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- RIWAYAT KUNJUNGAN & FILTER --}}
                <div x-data="{ tab: 'semua' }">

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">E-Tiket</span>
                            <h2 class="font-display text-xl font-bold text-[#102A43]">
                                Riwayat Kunjungan & Tiket
                            </h2>
                        </div>

                        {{-- TAB FILTER --}}
                        <div class="flex items-center gap-1 bg-white p-1 rounded-2xl border border-[#EADBC8] shadow-sm self-start sm:self-auto text-xs flex-wrap">
                            <button type="button"
                                    @click="tab = 'semua'"
                                    :class="tab === 'semua' ? 'bg-[#102A43] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#F6F1E8]'"
                                    class="px-3 py-1.5 rounded-xl transition">
                                Semua
                            </button>

                            <button type="button"
                                    @click="tab = 'aktif'"
                                    :class="tab === 'aktif' ? 'bg-[#102A43] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#F6F1E8]'"
                                    class="px-3 py-1.5 rounded-xl transition">
                                Tiket Aktif
                            </button>

                            <button type="button"
                                    @click="tab = 'dipakai'"
                                    :class="tab === 'dipakai' ? 'bg-[#102A43] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#F6F1E8]'"
                                    class="px-3 py-1.5 rounded-xl transition">
                                Sudah Dipakai
                            </button>

                            <button type="button"
                                    @click="tab = 'expired'"
                                    :class="tab === 'expired' ? 'bg-[#102A43] text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-[#F6F1E8]'"
                                    class="px-3 py-1.5 rounded-xl transition">
                                Kedaluwarsa
                            </button>
                        </div>
                    </div>

                    {{-- BOARDING PASS LIST --}}
                    <div class="space-y-3.5">
                        @forelse($transactions as $transaction)
                            @php
                                $visitDate = \Carbon\Carbon::parse($transaction->booking->visit_date);
                                $closingTime = $transaction->booking->museum->closing_time ?? '23:59:59';
                                $closingDateTime = \Carbon\Carbon::parse($transaction->booking->visit_date . ' ' . $closingTime);
                                $isExpired = now()->greaterThan($closingDateTime) || $transaction->booking->status === 'expired';

                                if ($transaction->booking->status === 'cancelled') {
                                    $statusTag = 'cancelled';
                                    $statusLabel = '✕ Dibatalkan';
                                    $statusClass = 'bg-red-50 text-red-700 border border-red-200';
                                } elseif ($transaction->used_at) {
                                    $statusTag = 'dipakai';
                                    $statusLabel = '✓ Sudah Dipakai';
                                    $statusClass = 'bg-slate-100 text-slate-600 border border-slate-200';
                                } elseif ($isExpired) {
                                    $statusTag = 'expired';
                                    $statusLabel = '✕ Kedaluwarsa';
                                    $statusClass = 'bg-red-50 text-red-700 border border-red-200';
                                } else {
                                    $statusTag = 'aktif';
                                    $statusLabel = '● Tiket Aktif';
                                    $statusClass = 'bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold';
                                }

                                $totalPax = ($transaction->booking->adult_qty ?? 0) + ($transaction->booking->student_qty ?? 0) + ($transaction->booking->child_qty ?? 0);
                            @endphp

                            <div x-show="tab === 'semua' || tab === '{{ $statusTag }}'"
                                 class="bg-white rounded-2xl border border-[#EADBC8] shadow-sm overflow-hidden flex flex-col sm:flex-row transition hover:border-[#B88A44]/60">

                                {{-- DATE STUB (KIRI) --}}
                                <div class="sm:w-28 bg-[#FAF7F2] p-4 flex flex-row sm:flex-col items-center justify-between sm:justify-center text-center border-b sm:border-b-0 sm:border-r border-[#EADBC8]/70 shrink-0">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#B88A44] block">
                                            {{ $visitDate->translatedFormat('M Y') }}
                                        </span>
                                        <span class="text-2xl font-black text-[#102A43] leading-none block my-0.5">
                                            {{ $visitDate->format('d') }}
                                        </span>
                                        <span class="text-[10px] text-slate-500 font-medium">
                                            {{ $visitDate->translatedFormat('l') }}
                                        </span>
                                    </div>
                                    <span class="sm:hidden px-2.5 py-0.5 rounded-full text-[10px] {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                {{-- INFO STUB (TENGAH) --}}
                                <div class="p-4 sm:p-5 flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <div class="hidden sm:flex items-center gap-2 mb-1.5">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-mono">
                                                {{ $transaction->invoice_code }}
                                            </span>
                                        </div>

                                        <h3 class="font-bold text-base text-[#102A43] truncate">
                                            {{ $transaction->booking->museum->name ?? '-' }}
                                        </h3>

                                        <p class="text-xs text-slate-500 truncate mt-0.5">
                                            {{ $transaction->booking->museum->address ?? '-' }}
                                        </p>
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-[#EADBC8]/60 flex items-center justify-between text-xs text-slate-600">
                                        <div class="flex items-center gap-3">
                                            <span>👥 <strong>{{ $totalPax }} Pax</strong></span>
                                            <span>•</span>
                                            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION STUB (KANAN) --}}
                                <div class="bg-[#FAF7F2]/50 p-4 flex sm:flex-col items-center justify-center gap-2 border-t sm:border-t-0 sm:border-l border-[#EADBC8]/70 shrink-0 sm:w-36">
                                    @if($transaction->used_at)
                                        @if($transaction->booking->review)
                                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-200 text-center w-full">
                                                ⭐ Dinilai ({{ $transaction->booking->review->rating }}★)
                                            </span>
                                        @else
                                            <button type="button"
                                                    @click="reviewModalOpen = true; reviewBookingId = {{ $transaction->booking->id }}; reviewMuseumName = '{{ addslashes($transaction->booking->museum->name ?? '-') }}'"
                                                    class="w-full py-2 px-3 rounded-xl bg-[#B88A44] hover:bg-[#a67c3b] text-white font-bold text-xs transition shadow-sm text-center">
                                                Beri Ulasan ⭐
                                            </button>
                                        @endif
                                        <a href="{{ route('user.ticket', $transaction->id) }}"
                                           class="w-full py-1.5 px-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-white transition text-center">
                                            Lihat Struk
                                        </a>
                                    @elseif($isExpired)
                                        <span class="text-[11px] font-semibold text-red-600 bg-red-50 px-2.5 py-1.5 rounded-xl border border-red-200 text-center w-full">
                                            Kedaluwarsa
                                        </span>
                                        <a href="{{ route('user.ticket', $transaction->id) }}"
                                           class="w-full py-1.5 px-3 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-white transition text-center">
                                            Detail Tiket
                                        </a>
                                    @elseif($transaction->booking->status === 'cancelled')
                                        <span class="text-[11px] font-semibold text-slate-500 text-center w-full">
                                            Dibatalkan
                                        </span>
                                    @else
                                        <a href="{{ route('user.ticket', $transaction->id) }}"
                                           class="w-full py-2 px-3 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white font-bold text-xs transition shadow-sm text-center">
                                            Buka E-Tiket QR
                                        </a>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="bg-white rounded-2xl border border-[#EADBC8] p-8 text-center text-slate-400 text-xs">
                                Belum ada riwayat tiket yang sesuai.
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

            {{-- ======================================================== --}}
            {{-- TAB 2: PASPOR BUDAYA & LENCANA WISATAWAN (FITUR C USER) --}}
            {{-- ======================================================== --}}
            <div x-show="mainTab === 'paspor'" x-cloak class="space-y-8">

                {{-- PASSPORT COVER BANNER --}}
                <div class="relative overflow-hidden rounded-3xl bg-[#102A43] text-white p-6 sm:p-8 border-2 border-[#B88A44]/40 shadow-lg">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full border-[20px] border-white/5 pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-[#EADBC8] text-[10px] font-bold uppercase tracking-widest mb-2 border border-white/20">
                                <span>🏛️</span>
                                <span>PASPOR BUDAYA NUSANTARA</span>
                            </span>
                            <h2 class="font-display text-2xl sm:text-3xl font-bold text-white tracking-wide">
                                {{ $passportData['rank_name'] }}
                            </h2>
                            <p class="text-xs text-slate-300 mt-1 max-w-md">
                                @if($passportData['next_target'])
                                    Kunjungi {{ $passportData['next_target'] - $passportData['distinct_museums'] }} museum lagi untuk naik ke tingkat berikutnya.
                                @else
                                    Selamat! Anda telah meraih gelar tertinggi penjelajah museum nusantara.
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 text-center border border-white/20 min-w-[90px]">
                                <span class="text-2xl font-black text-[#E8C581] block">{{ $passportData['total_stamps'] }}</span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-slate-300">Stempel</span>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 text-center border border-white/20 min-w-[90px]">
                                <span class="text-2xl font-black text-[#E8C581] block">{{ $passportData['unlocked_badges_count'] }}/{{ $passportData['total_badges'] }}</span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-slate-300">Lencana</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BUKU STEMPEL PASPOR DIGITAL --}}
                <div class="bg-white rounded-3xl border border-[#EADBC8] p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">Koleksi Cap Loket</span>
                            <h3 class="font-display text-lg sm:text-xl font-bold text-[#102A43]">
                                Lembar Stempel Kunjungan Museum
                            </h3>
                        </div>
                        <span class="text-xs text-slate-500 font-semibold">
                            {{ count($passportData['stamps']) }} Museum Terverifikasi
                        </span>
                    </div>

                    @if(empty($passportData['stamps']))
                        <div class="text-center py-12 px-4 border-2 border-dashed border-[#EADBC8] rounded-2xl bg-[#FAF7F2]/50">
                            <div class="text-4xl mb-3">📑</div>
                            <h4 class="font-bold text-sm text-[#102A43]">Belum Ada Stempel Paspor</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                Tiket yang telah di-scan oleh petugas loket di pintu masuk museum akan otomatis meninggalkan stempel resmi di paspor digitalmu!
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($passportData['stamps'] as $stamp)
                                <div class="relative bg-gradient-to-br from-[#FAF7F2] to-white rounded-2xl border-2 border-dashed p-4 shadow-sm flex flex-col justify-between"
                                     style="border-color: {{ $stamp['color'] }}40;">
                                    
                                    {{-- STEMPEL BULAT OTENTIK --}}
                                    <div class="flex items-start justify-between gap-2 mb-3">
                                        <div class="w-12 h-12 rounded-full border-2 flex flex-col items-center justify-center text-center p-1"
                                             style="border-color: {{ $stamp['color'] }}; color: {{ $stamp['color'] }};">
                                            <span class="text-[8px] font-black uppercase tracking-tighter">ITIHASA</span>
                                            <span class="text-[7px] font-bold">★ VERIFIED ★</span>
                                        </div>

                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-mono font-bold"
                                              style="background-color: {{ $stamp['color'] }}15; color: {{ $stamp['color'] }};">
                                            {{ $stamp['city'] }}
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-sm text-[#102A43] line-clamp-1">
                                            {{ $stamp['museum_name'] }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            Tanggal: <strong>{{ $stamp['date'] }}</strong> ({{ $stamp['time'] }} WIB)
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">
                                            Kode: {{ $stamp['stamp_code'] }} • {{ $stamp['pax'] }} Pax
                                        </p>
                                    </div>

                                    <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                                        <span class="text-emerald-700 font-bold flex items-center gap-1">
                                            <span>✓</span> Stempel Sah
                                        </span>
                                        @if($stamp['review'])
                                            <span class="text-amber-700 font-bold">⭐ {{ $stamp['review']->rating }}★</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- GALERI LENCANA WISATAWAN (EXPLORER BADGES) --}}
                <div class="bg-white rounded-3xl border border-[#EADBC8] p-6 sm:p-8 shadow-sm">
                    <div class="mb-6">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">Prestasi Wisata</span>
                        <h3 class="font-display text-lg sm:text-xl font-bold text-[#102A43]">
                            Lencana Penjelajah Budaya (Explorer Badges)
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Buka seluruh lencana dengan mengunjungi beragam museum tematik di Indonesia.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($passportData['badges'] as $badge)
                            <div class="rounded-2xl p-4 border transition-all duration-200 flex gap-3.5 {{ $badge['is_unlocked'] ? 'bg-gradient-to-br from-amber-50/60 via-white to-amber-50/30 border-[#B88A44] shadow-sm' : 'bg-slate-50/80 border-slate-200 opacity-75' }}">
                                
                                {{-- BADGE ICON --}}
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shrink-0 {{ $badge['is_unlocked'] ? 'bg-[#102A43] text-white shadow-md ring-2 ring-[#B88A44]' : 'bg-slate-200 text-slate-400 grayscale' }}">
                                    {{ $badge['icon'] }}
                                </div>

                                {{-- BADGE INFO --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <h4 class="font-bold text-xs sm:text-sm text-[#102A43] truncate">
                                            {{ $badge['name'] }}
                                        </h4>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold shrink-0 {{ $badge['is_unlocked'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $badge['is_unlocked'] ? '✓ Terbuka' : 'Terkunci' }}
                                        </span>
                                    </div>

                                    <p class="text-[11px] text-slate-500 leading-snug">
                                        {{ $badge['description'] }}
                                    </p>

                                    {{-- PROGRESS BAR --}}
                                    <div class="mt-2.5">
                                        <div class="flex justify-between items-center text-[10px] text-slate-500 mb-1 font-medium">
                                            <span>Progres: {{ $badge['current'] }}/{{ $badge['target'] }}</span>
                                            <span>{{ $badge['progress_percent'] }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500 {{ $badge['is_unlocked'] ? 'bg-[#B88A44]' : 'bg-slate-400' }}"
                                                 style="width: {{ $badge['progress_percent'] }}%;"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ======================================================== --}}
            {{-- MODALS                                                   --}}
            {{-- ======================================================== --}}

            {{-- MODAL ULASAN DENGAN UNGGAH FOTO (FITUR B USER) --}}
            <div x-show="reviewModalOpen" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                @click.self="reviewModalOpen = false">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl border border-[#EADBC8]">
                    <h3 class="text-base font-bold text-[#102A43] mb-0.5">Beri Ulasan Kunjungan</h3>
                    <p class="text-xs text-slate-500 mb-4" x-text="reviewMuseumName"></p>

                    <form :action="'/bookings/' + reviewBookingId + '/review'" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-row-reverse justify-end mb-3">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="modalStar{{ $i }}"
                                    class="hidden peer" required>
                                <label for="modalStar{{ $i }}"
                                    class="cursor-pointer text-2xl text-[#D9CDBB] peer-checked:text-[#B88A44] hover:text-[#B88A44] hover:[&~label]:text-[#B88A44]">★</label>
                            @endfor
                        </div>

                        <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman kunjungan kamu..."
                                class="w-full rounded-xl border border-[#EADBC8] p-3 text-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-[#B88A44] mb-3"></textarea>

                        {{-- FOTO INPUT --}}
                        <div class="mb-4">
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">📷 Unggah Foto Pengalaman (Maks 3)</label>
                            <label class="w-full px-3 py-2 rounded-xl bg-[#FAF7F2] border border-[#EADBC8] text-xs text-slate-600 hover:border-[#B88A44] transition cursor-pointer flex items-center justify-between shadow-sm">
                                <span class="text-[11px]" x-text="photoCount > 0 ? photoCount + ' foto dipilih' : 'Pilih hingga 3 foto (JPG, PNG, WEBP)'"></span>
                                <span class="px-2 py-0.5 rounded bg-white text-[#102A43] text-[10px] font-bold border border-[#EADBC8]">Pilih</span>
                                <input type="file" name="photos[]" multiple accept="image/*" class="hidden"
                                       @change="
                                           previews = [];
                                           let files = Array.from($event.target.files).slice(0, 3);
                                           photoCount = files.length;
                                           files.forEach(f => {
                                               let r = new FileReader();
                                               r.onload = (e) => previews.push(e.target.result);
                                               r.readAsDataURL(f);
                                           });
                                       ">
                            </label>
                            <template x-if="previews.length > 0">
                                <div class="flex gap-2 mt-2">
                                    <template x-for="(p, idx) in previews" :key="idx">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-[#B88A44] shadow-sm">
                                            <img :src="p" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" @click="reviewModalOpen = false"
                                    class="flex-1 py-2.5 rounded-xl border border-[#EADBC8] text-slate-500 text-xs font-semibold hover:bg-slate-50">
                                Batal
                            </button>
                            <button type="submit"
                                    class="flex-1 py-2.5 rounded-xl bg-[#102A43] text-white text-xs font-bold hover:bg-[#0c2238] transition shadow">
                                Kirim Ulasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MODAL PEMBATALAN BOOKING --}}
            <div x-show="cancelModalOpen" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                @click.self="cancelModalOpen = false">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl border border-[#EADBC8]">
                    <h3 class="text-base font-bold text-[#102A43] mb-0.5">Batalkan Booking Tiket</h3>
                    <p class="text-xs text-slate-500 mb-3" x-text="cancelMuseumName"></p>

                    <div class="bg-red-50 border border-red-100 text-red-700 text-xs rounded-xl p-3 mb-3">
                        ⚠️ Untuk booking yang sudah dibayar, pembatalan hanya bisa dilakukan maksimal 12 jam sebelum jadwal kunjungan.
                    </div>

                    <form :action="'/bookings/' + cancelBookingId + '/cancel'" method="POST">
                        @csrf
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Alasan Pembatalan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="cancellation_reason" rows="3" required
                                placeholder="Tuliskan alasan pembatalan..."
                                class="w-full rounded-xl border border-[#EADBC8] p-3 text-slate-700 text-xs focus:outline-none focus:ring-2 focus:ring-red-400 mb-3"></textarea>

                        <div class="flex gap-2">
                            <button type="button" @click="cancelModalOpen = false"
                                    class="flex-1 py-2.5 rounded-xl border border-[#EADBC8] text-slate-500 text-xs font-semibold hover:bg-slate-50">
                                Tutup
                            </button>
                            <button type="submit"
                                class="flex-1 py-2.5 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition shadow">
                                Ya, Batalkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

</section>

@endsection
