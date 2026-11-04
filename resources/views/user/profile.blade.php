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
        <div class="lg:col-span-8 space-y-6" x-data="{ 
            mainTab: '{{ request('tab', 'tiket') }}', 
            cancelModalOpen: false, 
            cancelBookingId: null, 
            cancelMuseumName: '', 
            reviewModalOpen: false, 
            reviewBookingId: null, 
            reviewMuseumName: '', 
            photoCount: 0, 
            previews: [],
            stampModalOpen: false,
            selectedStamp: null,
            certificateModalOpen: false,
            perkModalOpen: false,
            selectedBadge: null,
            copiedPromo: null,
            copyCode(code) {
                if (!code) return;
                navigator.clipboard.writeText(code);
                this.copiedPromo = code;
                setTimeout(() => { if (this.copiedPromo === code) this.copiedPromo = null; }, 2500);
            }
        }">

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

                {{-- PASSPORT COVER IDENTITY BANNER --}}
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0c2033] via-[#102A43] to-[#1e3a5a] text-white p-6 sm:p-8 border-2 border-[#B88A44]/60 shadow-xl">
                    <div class="absolute -right-12 -bottom-12 w-56 h-56 rounded-full border-[18px] border-[#B88A44]/15 pointer-events-none"></div>
                    <div class="absolute -right-4 -top-8 w-36 h-36 rounded-full border-[10px] border-white/5 pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#B88A44]/25 text-[#EADBC8] text-[10px] font-bold uppercase tracking-widest border border-[#B88A44]/40">
                                    <span>🏛️</span>
                                    <span>DOKUMEN RESMI PELESTARI BUDAYA</span>
                                </span>
                                <span class="px-2.5 py-1 rounded-full bg-white/10 text-slate-300 font-mono text-[10px] border border-white/15">
                                    {{ $passportData['passport_serial'] }}
                                </span>
                            </div>

                            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-white tracking-wide flex items-center gap-2.5">
                                <span>{{ $passportData['rank_name'] }}</span>
                                <span class="text-base sm:text-lg font-bold text-[#E8C581] px-2.5 py-0.5 rounded-full bg-white/10 border border-[#B88A44]/40">
                                    Tingkat {{ $passportData['rank_level'] }}/4
                                </span>
                            </h2>

                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-500/15 border border-amber-400/30 text-amber-200 text-xs font-medium">
                                <span>🎁</span>
                                <span><strong>Benefit Aktif:</strong> {{ $passportData['rank_perk'] }}</span>
                            </div>

                            <p class="text-xs text-slate-300 max-w-xl leading-relaxed">
                                @if($passportData['next_target'])
                                    Kunjungi <strong>{{ $passportData['next_target'] - $passportData['distinct_museums'] }} museum lagi</strong> untuk naik tingkat dan membuka hadiah kupon diskon yang lebih tinggi serta cinderamata eksklusif di loket!
                                @else
                                    Selamat! Anda telah meraih gelar tertinggi Mahapatih Budaya Nusantara. Seluruh akses jalur cepat VIP dan diskon maksimal telah aktif di akun Anda!
                                @endif
                            </p>

                            @if($passportData['distinct_museums'] >= 1)
                                <div class="pt-1">
                                    <button type="button"
                                            @click="certificateModalOpen = true"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#B88A44] hover:bg-[#a67c3b] text-white text-xs font-bold transition shadow-md">
                                        <span>📜</span>
                                        <span>Buka Sertifikat Penjelajah Budaya (Resmi)</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- STAT CHIPS --}}
                        <div class="grid grid-cols-3 gap-2.5 w-full lg:w-auto shrink-0">
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 text-center border border-white/20 min-w-[85px]">
                                <span class="text-2xl sm:text-3xl font-black text-[#E8C581] block">{{ $passportData['total_stamps'] }}</span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-slate-300">Stempel Sah</span>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 text-center border border-white/20 min-w-[85px]">
                                <span class="text-2xl sm:text-3xl font-black text-[#E8C581] block">{{ $passportData['distinct_museums'] }}</span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-slate-300">Museum Unik</span>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 text-center border border-white/20 min-w-[85px]">
                                <span class="text-2xl sm:text-3xl font-black text-[#E8C581] block">{{ $passportData['unlocked_badges_count'] }}/{{ $passportData['total_badges'] }}</span>
                                <span class="text-[9px] uppercase font-bold tracking-wider text-slate-300">Lencana & Hadiah</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BUKU LEMBAR STEMPEL PASPOR DIGITAL --}}
                <div class="bg-[#FFFDF9] rounded-3xl border-2 border-[#EADBC8] p-6 sm:p-8 shadow-sm relative overflow-hidden">
                    {{-- DECORATIVE BORDER MOTIF --}}
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#B88A44] via-[#102A43] to-[#B88A44]"></div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-5 border-b border-[#EADBC8] mb-6">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">Lembar Validasi Kedatangan Resmi</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-mono bg-amber-100 text-amber-900 font-bold">HALAMAN 01-02</span>
                            </div>
                            <h3 class="font-display text-lg sm:text-xl font-bold text-[#102A43] mt-0.5">
                                Paspor Cap Loket Kunjungan Museum
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Setiap tiket yang di-scan oleh petugas gate akan dicap permanen di paspor digital ini dengan stempel resmi berautentikasi unik.
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold shrink-0">
                            <span>✓</span>
                            <span>{{ count($passportData['stamps']) }} Cap Loket Terverifikasi</span>
                        </span>
                    </div>

                    @if(empty($passportData['stamps']))
                        <div class="text-center py-14 px-4 border-2 border-dashed border-[#D9CDBB] rounded-2xl bg-[#FAF7F2]/60">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-[#102A43]/5 flex items-center justify-center text-3xl">
                                📑
                            </div>
                            <h4 class="font-bold text-base text-[#102A43]">Lembar Paspor Belum Memiliki Cap</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                                Pesan tiket museum pertamamu dan tunjukkan QR e-tiket ke petugas loket pintu masuk. Stempel vintage otentik akan langsung tertera di lembar paspor ini!
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('museums.index') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white text-xs font-bold transition shadow-sm">
                                    <span>🏛️</span>
                                    <span>Jelajahi Museum Nusantara Sekarang</span>
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- GRID OF AUTHENTIC VINTAGE STAMPS --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($passportData['stamps'] as $stamp)
                                <div @click="selectedStamp = {{ json_encode($stamp) }}; stampModalOpen = true"
                                     class="group relative bg-[#FAF7F2] rounded-2xl border-2 p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between cursor-pointer hover:-translate-y-0.5"
                                     style="border-color: {{ $stamp['color'] }}60;">

                                    {{-- HEADER STAMP CARD --}}
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-mono font-bold uppercase tracking-wider {{ $stamp['badge_class'] }}">
                                            📍 {{ $stamp['city'] }}
                                        </span>
                                        <span class="text-[9px] font-mono text-slate-400">
                                            {{ $stamp['stamp_code'] }}
                                        </span>
                                    </div>

                                    {{-- REALISTIC RUBBER INK STAMP SEAL WITH ORGANIC TILT --}}
                                    <div class="my-2 py-3 flex items-center justify-center">
                                        <div class="relative transition-transform duration-300 group-hover:rotate-0"
                                             style="transform: rotate({{ $stamp['rotation'] }}deg);">
                                            
                                            {{-- OUTER CIRCULAR GUILLOCHE POSTMARK --}}
                                            <div class="w-32 h-32 rounded-full border-4 flex flex-col items-center justify-center text-center p-2 relative shadow-inner select-none"
                                                 style="border-color: {{ $stamp['color'] }}; color: {{ $stamp['color_text'] }}; background-color: {{ $stamp['color_bg'] }}60;">
                                                
                                                {{-- INNER DOUBLE RING --}}
                                                <div class="absolute inset-1 rounded-full border border-dashed" style="border-color: {{ $stamp['color'] }}80;"></div>

                                                {{-- POSTMARK ARCH LABELS --}}
                                                <span class="text-[7px] font-black uppercase tracking-widest block leading-tight">
                                                    ★ ITIHASA RESMI ★
                                                </span>
                                                
                                                {{-- LARGE CITY INK IMPRINT --}}
                                                <span class="text-sm font-black uppercase tracking-wider my-0.5 block font-mono" style="color: {{ $stamp['color'] }};">
                                                    {{ strtoupper($stamp['city']) }}
                                                </span>

                                                {{-- CANCELLATION DATE BOX --}}
                                                <div class="px-2 py-0.5 my-0.5 border-y-2 border-current text-[8px] font-black font-mono tracking-tight bg-white/70">
                                                    {{ strtoupper($stamp['date']) }}
                                                </div>

                                                {{-- GATE VALIDATION SUBTEXT --}}
                                                <span class="text-[7px] font-bold uppercase tracking-tight block">
                                                    GATE VALIDATED • {{ $stamp['time'] }}
                                                </span>
                                            </div>

                                            {{-- WAVY POSTAL CANCELLATION LINES --}}
                                            <div class="absolute -right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-80" style="color: {{ $stamp['color'] }};">
                                                <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M0 10 Q10 5, 20 10 T40 10" />
                                                    <path d="M0 20 Q10 15, 20 20 T40 20" />
                                                    <path d="M0 30 Q10 25, 20 30 T40 30" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- MUSEUM INFO --}}
                                    <div class="pt-2 border-t border-slate-200/80">
                                        <h4 class="font-bold text-xs sm:text-sm text-[#102A43] line-clamp-1 group-hover:text-[#B88A44] transition-colors">
                                            {{ $stamp['museum_name'] }}
                                        </h4>
                                        <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                                            <span>👥 <strong>{{ $stamp['pax'] }} Pax</strong> Masuk</span>
                                            <span class="font-semibold text-emerald-700 flex items-center gap-1">
                                                <span>✓</span> Dicap Loket
                                            </span>
                                        </div>
                                    </div>

                                    {{-- HOVER HINT --}}
                                    <div class="mt-2 text-center text-[9px] text-slate-400 font-medium group-hover:text-[#102A43] transition-colors">
                                        Klik untuk perbesar cap & nomor seri 🔍
                                    </div>

                                </div>
                            @endforeach

                            {{-- EMPTY STAMP PLACEHOLDER SLOTS --}}
                            @for($i = 1; $i <= $passportData['empty_slots_count']; $i++)
                                <div class="relative bg-white/50 rounded-2xl border-2 border-dashed border-[#D9CDBB] p-5 flex flex-col items-center justify-center text-center min-h-[220px]">
                                    <div class="w-16 h-16 rounded-full border-2 border-dashed border-slate-300 flex items-center justify-center text-slate-300 mb-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5m-15 10.5V10.5" />
                                        </svg>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 block">
                                        Slot Lembar Kosong #{{ count($passportData['stamps']) + $i }}
                                    </span>
                                    <p class="text-[11px] text-slate-500 mt-1 max-w-[190px] leading-snug">
                                        Kunjungi museum berikutnya untuk mengecap lembar paspor ini!
                                    </p>
                                    <a href="{{ route('museums.index') }}"
                                       class="mt-3 px-3 py-1 rounded-xl bg-[#FAF7F2] hover:bg-[#F6F1E8] border border-[#EADBC8] text-[#102A43] font-bold text-[10px] transition">
                                        Pesan Tiket →
                                    </a>
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>

                {{-- GALERI LENCANA WISATAWAN DENGAN HADIAH NYATA (EXPLORER BADGES & REAL PERKS) --}}
                <div class="bg-white rounded-3xl border border-[#EADBC8] p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6 pb-4 border-b border-[#EADBC8]/70">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">Prestasi Wisata & Hadiah Nyata</span>
                            <h3 class="font-display text-lg sm:text-xl font-bold text-[#102A43]">
                                Lencana Penjelajah & Insentif Kunjungan
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Kumpulkan lencana untuk membuka kupon diskon tiket seumur hidup, merchandise pin fisik di loket, dan akses VIP gate bebas antre!
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold shrink-0">
                            <span>🏆</span>
                            <span>{{ $passportData['unlocked_badges_count'] }}/{{ $passportData['total_badges'] }} Terbuka</span>
                        </span>
                    </div>

                    {{-- BADGES GRID --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($passportData['badges'] as $badge)
                            <div class="rounded-2xl p-5 border transition-all duration-200 flex flex-col justify-between {{ $badge['is_unlocked'] ? 'bg-gradient-to-br from-amber-50/60 via-white to-amber-50/30 border-[#B88A44] shadow-sm ring-1 ring-[#B88A44]/20' : 'bg-slate-50/80 border-slate-200 opacity-80' }}">
                                
                                <div>
                                    {{-- HEADER CARD: ICON, TIER, STATUS --}}
                                    <div class="flex items-start gap-3.5 mb-3">
                                        <div class="w-13 h-13 rounded-2xl flex items-center justify-center text-2xl shrink-0 {{ $badge['is_unlocked'] ? 'bg-[#102A43] text-white shadow-md ring-2 ring-[#B88A44]' : 'bg-slate-200 text-slate-400 grayscale' }}">
                                            {{ $badge['icon'] }}
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1.5 mb-0.5">
                                                <h4 class="font-bold text-sm text-[#102A43] truncate">
                                                    {{ $badge['name'] }}
                                                </h4>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold shrink-0 {{ $badge['is_unlocked'] ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                    {{ $badge['is_unlocked'] ? '✓ Terbuka' : 'Terkunci' }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-1.5 text-[10px]">
                                                <span class="font-bold uppercase tracking-wider px-2 py-0.2 rounded text-[8px] {{ $badge['tier'] === 'Diamond' ? 'bg-purple-100 text-purple-800' : ($badge['tier'] === 'Gold' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                                    {{ $badge['tier'] }} Tier
                                                </span>
                                                <span class="text-slate-400">•</span>
                                                <span class="text-slate-500 font-medium">{{ $badge['category'] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-xs text-slate-600 leading-relaxed mb-3">
                                        {{ $badge['description'] }}
                                    </p>

                                    {{-- PROGRESS BAR --}}
                                    <div class="mb-3">
                                        <div class="flex justify-between items-center text-[10px] text-slate-500 mb-1 font-medium">
                                            <span>Progres Kunjungan: {{ $badge['current'] }}/{{ $badge['target'] }}</span>
                                            <span class="font-bold text-[#102A43]">{{ $badge['progress_percent'] }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500 {{ $badge['is_unlocked'] ? 'bg-[#B88A44]' : 'bg-slate-400' }}"
                                                 style="width: {{ $badge['progress_percent'] }}%;"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- HADIAH NYATA / BENEFIT SECTION --}}
                                @if($badge['is_unlocked'])
                                    <div class="mt-2 pt-3 border-t border-[#EADBC8]/70 bg-white/80 rounded-xl p-3 border border-[#EADBC8]/80">
                                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-amber-800 mb-1">
                                            <span>🎁</span>
                                            <span>HADIAH TERBUKA: {{ $badge['reward_badge'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-600 leading-snug">
                                            {{ $badge['reward_description'] }}
                                        </p>

                                        <div class="mt-2.5 flex flex-wrap items-center gap-2">
                                            {{-- SALIN KODE PROMO --}}
                                            <button type="button"
                                                    @click="copyCode('{{ $badge['promo_code'] }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#102A43] hover:bg-[#0c2238] text-white text-[11px] font-bold transition shadow-sm">
                                                <span x-text="copiedPromo === '{{ $badge['promo_code'] }}' ? '✓ Tersalin!' : 'Salin Kupon: {{ $badge['promo_code'] }}'"></span>
                                            </button>

                                            {{-- KLAIM DI LOKET BUTTON --}}
                                            <button type="button"
                                                    @click="selectedBadge = {{ json_encode($badge) }}; perkModalOpen = true"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-[#B88A44] text-[#B88A44] hover:bg-[#FAF7F2] text-[11px] font-bold transition">
                                                <span>🏷️ Klaim di Loket</span>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2 pt-3 border-t border-slate-200/80 p-2.5 rounded-xl bg-slate-100/70 text-[11px] text-slate-500">
                                        <div class="font-bold text-slate-700 flex items-center gap-1 mb-0.5">
                                            <span>🔒</span>
                                            <span>Hadiah Terkunci: {{ $badge['reward_badge'] }}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-500 leading-tight">
                                            Kunjungi {{ $badge['target'] - $badge['current'] }} museum lagi untuk membuka kode diskon & hadiah fisik loket!
                                        </p>
                                    </div>
                                @endif

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

            {{-- ======================================================== --}}
            {{-- MODAL DETAIL STEMPEL RESMI PASPOR DIGITAL                 --}}
            {{-- ======================================================== --}}
            <div x-show="stampModalOpen" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
                 @click.self="stampModalOpen = false">
                <div class="bg-[#FFFDF9] rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border-2 border-[#B88A44]/60 relative overflow-hidden"
                     x-show="selectedStamp">
                    
                    {{-- ORNAMENTAL CORNERS --}}
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#B88A44] via-[#102A43] to-[#B88A44]"></div>

                    <div class="flex items-center justify-between pb-3 border-b border-[#EADBC8]">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44]">Stempel Paspor Resmi</span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-amber-100 text-amber-900" x-text="selectedStamp?.stamp_code"></span>
                        </div>
                        <button type="button" @click="stampModalOpen = false" class="text-slate-400 hover:text-slate-700 text-lg font-bold">
                            ✕
                        </button>
                    </div>

                    {{-- LARGE ENLARGED AUTHENTIC POSTMARK --}}
                    <div class="py-6 flex flex-col items-center justify-center text-center">
                        <div class="w-40 h-40 rounded-full border-4 flex flex-col items-center justify-center text-center p-3 relative shadow-inner select-none transition-transform hover:scale-105 duration-300"
                             :style="{ borderColor: selectedStamp?.color, color: selectedStamp?.color_text, backgroundColor: selectedStamp?.color_bg }">
                            
                            <div class="absolute inset-1.5 rounded-full border border-dashed" :style="{ borderColor: selectedStamp?.color }"></div>

                            <span class="text-[8px] font-black uppercase tracking-widest block leading-tight">
                                ★ ITIHASA RESMI ★
                            </span>
                            
                            <span class="text-base font-black uppercase tracking-wider my-1 block font-mono"
                                  :style="{ color: selectedStamp?.color }"
                                  x-text="selectedStamp?.city">
                            </span>

                            <div class="px-2.5 py-0.5 my-1 border-y-2 border-current text-[9px] font-black font-mono tracking-tight bg-white/80"
                                 x-text="selectedStamp?.date">
                            </div>

                            <span class="text-[8px] font-bold uppercase tracking-tight block"
                                  x-text="'GATE VALIDATED • ' + (selectedStamp?.time || '') + ' WIB'">
                            </span>
                        </div>
                    </div>

                    {{-- METADATA INFO --}}
                    <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 space-y-2 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Destinasi Museum:</span>
                            <strong class="text-[#102A43] text-right truncate max-w-[200px]" x-text="selectedStamp?.museum_name"></strong>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Kota Lokasi:</span>
                            <span class="font-bold text-slate-700" x-text="selectedStamp?.city"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Jumlah Pengunjung:</span>
                            <span class="font-bold text-slate-700" x-text="(selectedStamp?.pax || 1) + ' Orang (Pax)'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Status Validasi:</span>
                            <span class="font-bold text-emerald-700 flex items-center gap-1">
                                <span>✓</span> Sah & Terverifikasi Gate Loket
                            </span>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-4 flex gap-2">
                        <button type="button"
                                @click="copyCode(selectedStamp?.stamp_code)"
                                class="flex-1 py-2.5 rounded-xl border border-[#B88A44] text-[#B88A44] hover:bg-amber-50 text-xs font-bold transition">
                            <span x-text="copiedPromo === selectedStamp?.stamp_code ? '✓ Tersalin!' : 'Salin Kode Stempel'"></span>
                        </button>
                        <button type="button" @click="stampModalOpen = false"
                                class="flex-1 py-2.5 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white text-xs font-bold transition shadow-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            {{-- ======================================================== --}}
            {{-- MODAL SERTIFIKAT DIGITAL PENJELAJAH BUDAYA (A4 PRINTABLE) --}}
            {{-- ======================================================== --}}
            <div x-show="certificateModalOpen" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm overflow-y-auto"
                 @click.self="certificateModalOpen = false">
                <div class="bg-[#FFFDF9] rounded-3xl p-6 sm:p-10 max-w-2xl w-full shadow-2xl border-4 border-[#B88A44] relative my-auto">
                    
                    {{-- ORNAMENTAL INNER FRAME --}}
                    <div class="border-2 border-dashed border-[#B88A44]/60 p-6 sm:p-8 rounded-2xl relative text-center bg-white/60">
                        
                        {{-- WATERMARK CREST --}}
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-[#102A43] text-[#E8C581] flex items-center justify-center text-3xl font-serif font-black shadow-md border-2 border-[#B88A44]">
                            🏛️
                        </div>

                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-[#B88A44] block">
                            REPUBLIK INDONESIA • ITIHASA CULTURAL HERITAGE
                        </span>

                        <h2 class="font-serif text-xl sm:text-2xl font-black text-[#102A43] tracking-wider mt-1 uppercase">
                            Sertifikat Apresiasi Penjelajah Budaya
                        </h2>

                        <p class="text-xs text-slate-500 font-serif italic mt-0.5">
                            Official Digital Recognition of Indonesian Museum Exploration
                        </p>

                        <div class="my-6 py-2 border-y border-[#EADBC8]">
                            <p class="text-xs text-slate-500 font-sans uppercase tracking-widest mb-1">Dianugerahkan Dengan Bangga Kepada:</p>
                            <h3 class="font-serif text-2xl sm:text-3xl font-extrabold text-[#102A43] tracking-wide">
                                {{ $user->name }}
                            </h3>
                            <p class="text-xs text-slate-600 mt-2 max-w-lg mx-auto font-sans leading-relaxed">
                                Atas dedikasi, minat, dan kontribusi aktif dalam menjelajahi serta melestarikan 
                                <strong>{{ $passportData['distinct_museums'] }} museum bersejarah</strong> di nusantara dengan meraih gelar kehormatan:
                            </p>
                            <div class="mt-2 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#102A43] text-[#E8C581] text-xs font-bold uppercase tracking-wider shadow-sm">
                                <span>👑</span>
                                <span>{{ $passportData['rank_name'] }} (Tingkat {{ $passportData['rank_level'] }}/4)</span>
                            </div>
                        </div>

                        {{-- FOOTER CREDENTIALS & SEAL --}}
                        <div class="grid grid-cols-2 gap-4 text-left pt-2 border-t border-[#EADBC8] text-xs">
                            <div>
                                <span class="text-[9px] uppercase font-bold text-slate-400 block font-mono">Nomor Registrasi Sertifikat:</span>
                                <span class="font-mono font-bold text-[#102A43] text-[11px]">{{ $passportData['certificate_number'] }}</span>
                                <span class="text-[9px] text-slate-400 block mt-0.5">Diterbitkan: {{ now()->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] uppercase font-bold text-slate-400 block font-mono">Otentikasi Paspor:</span>
                                <span class="font-mono font-bold text-emerald-700 text-[11px]">TERVERIFIKASI SAH ✓</span>
                                <span class="text-[9px] text-slate-400 block mt-0.5">Konsorsium Museum Indonesia</span>
                            </div>
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-5 flex gap-3 print:hidden">
                        <button type="button"
                                @click="window.print()"
                                class="flex-1 py-2.5 rounded-xl bg-[#B88A44] hover:bg-[#a67c3b] text-white text-xs font-bold transition shadow-md flex items-center justify-center gap-2">
                            <span>🖨️</span>
                            <span>Cetak / Simpan PDF</span>
                        </button>
                        <button type="button" @click="certificateModalOpen = false"
                                class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>

            {{-- ======================================================== --}}
            {{-- MODAL KLAIM HADIAH FISIK DI LOKET MUSEUM                  --}}
            {{-- ======================================================== --}}
            <div x-show="perkModalOpen" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
                 @click.self="perkModalOpen = false">
                <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-md w-full shadow-2xl border border-[#EADBC8] relative"
                     x-show="selectedBadge">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-[#EADBC8]">
                        <h3 class="font-bold text-base text-[#102A43] flex items-center gap-2">
                            <span>🎁</span>
                            <span>Klaim Cinderamata di Loket</span>
                        </h3>
                        <button type="button" @click="perkModalOpen = false" class="text-slate-400 hover:text-slate-700 text-lg font-bold">
                            ✕
                        </button>
                    </div>

                    <div class="py-4 text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-[#102A43] text-white flex items-center justify-center text-3xl shadow-md border-2 border-[#B88A44]">
                            <span x-text="selectedBadge?.icon"></span>
                        </div>
                        <h4 class="font-bold text-base text-[#102A43]" x-text="selectedBadge?.name"></h4>
                        <div class="mt-2 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 font-semibold text-center"
                             x-text="selectedBadge?.physical_perk">
                        </div>
                    </div>

                    {{-- VERIFICATION BARCODE TOKEN --}}
                    <div class="bg-[#FAF7F2] rounded-2xl border border-[#EADBC8] p-4 text-center mb-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">
                            Kode Verifikasi Anggota
                        </span>
                        <span class="font-mono text-base font-black text-[#102A43] tracking-widest block py-1 bg-white rounded-lg border border-[#EADBC8]">
                            {{ 'ITH-MEMBER-' . str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                        <p class="text-[10px] text-slate-500 mt-2 leading-relaxed">
                            Tunjukkan layar ponsel ini kepada petugas loket atau meja informasi saat kamu berkunjung ke museum mitra Itihasa untuk mengambil cinderamata gratis!
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" @click="copyCode(selectedBadge?.promo_code)"
                                class="flex-1 py-2.5 rounded-xl border border-[#B88A44] text-[#B88A44] hover:bg-amber-50 text-xs font-bold transition">
                            <span x-text="copiedPromo === selectedBadge?.promo_code ? '✓ Kupon Tersalin!' : 'Salin Kupon: ' + (selectedBadge?.promo_code || '')"></span>
                        </button>
                        <button type="button" @click="perkModalOpen = false"
                                class="flex-1 py-2.5 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white text-xs font-bold transition shadow-sm">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>

        </div>

    </div>

</section>

@endsection
