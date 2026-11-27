@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')

<section class="py-4 sm:py-8">

    {{-- HEADER PROFIL --}}
    <div class="pb-6 mb-8 border-b border-neutral-200">
        <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] block mb-1.5">Akun Pengunjung</span>
        <h1 class="font-serif text-3xl sm:text-4xl font-normal text-[#1A1A1A] tracking-[-0.015em]">
            Profil &amp; <span class="italic">Paspor Pengunjung</span>
        </h1>
        <p class="text-xs sm:text-sm text-[#5A5A5A] mt-1.5">
            Kelola tiket museum, kumpulkan stempel paspor digital, dan buka lencana penjelajah budaya Nusantara.
        </p>
    </div>

    {{-- GRID UTAMA --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- LEFT SIDEBAR (USER CARD & STATISTIK) --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-sm border border-neutral-200 overflow-hidden">
                {{-- AVATAR & NAMA --}}
                <div class="p-6 text-center border-b border-neutral-200 bg-[#F8F7F4]">
                    <div class="w-18 h-18 sm:w-20 sm:h-20 mx-auto rounded-full bg-[#1A1A1A] text-white flex items-center justify-center font-serif text-2xl font-semibold border-2 border-[#B8860B] mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <h2 class="font-serif font-semibold text-xl text-[#1A1A1A]">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs text-[#5A5A5A] mt-0.5">
                        {{ $user->email }}
                    </p>

                    <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-sm text-[10px] font-semibold uppercase tracking-wider bg-[#1A1A1A] text-white">
                            <span>{{ $passportData['rank_name'] }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-sm text-[10px] font-semibold bg-white text-[#B8860B] border border-neutral-200">
                            ★ {{ $passportData['unlocked_badges_count'] }}/{{ $passportData['total_badges'] }} Lencana
                        </span>
                    </div>
                </div>

                {{-- STATISTIK RINGKAS --}}
                <div class="p-6 space-y-3.5 text-xs">
                    <div class="flex justify-between items-center text-[#5A5A5A]">
                        <span>Total Transaksi</span>
                        <span class="font-semibold text-[#1A1A1A]">
                            {{ $totalTransactions }} Pesanan
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-[#5A5A5A]">
                        <span>Total Pengunjung (Pax)</span>
                        <span class="font-semibold text-[#1A1A1A]">
                            {{ $totalVisitors }} Orang
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-[#5A5A5A]">
                        <span>Stempel Paspor</span>
                        <span class="font-semibold text-[#B8860B]">
                            {{ $passportData['total_stamps'] }} Stempel
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-[#5A5A5A]">
                        <span>Favorit Wishlist</span>
                        <span class="font-semibold text-[#1A1A1A]">
                            {{ $wishlists->count() }} Museum
                        </span>
                    </div>
                </div>

                {{-- AKSI --}}
                <div class="border-t border-neutral-200 p-4 space-y-2.5 bg-[#F8F7F4]/50">
                    <a href="{{ route('user.profile.edit') }}"
                       class="w-full flex justify-center py-2.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300">
                        Edit Profil
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 rounded-sm border border-neutral-300 text-[#8C3B2B] font-semibold text-xs uppercase tracking-wider hover:bg-white hover:border-[#8C3B2B] transition duration-300 text-center">
                            Keluar Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT CONTENT --}}
        <div class="lg:col-span-8 space-y-6" x-data="{ mainTab: 'tiket', cancelModalOpen: false, cancelBookingId: null, cancelMuseumName: '', reviewModalOpen: false, reviewBookingId: null, reviewMuseumName: '', photoCount: 0, previews: [] }">

            {{-- TOP NAVIGATION TABS --}}
            <div class="flex items-center gap-2 p-1.5 bg-[#F8F7F4] rounded-sm border border-neutral-200">
                <button type="button"
                        @click="mainTab = 'tiket'"
                        :class="mainTab === 'tiket' ? 'bg-[#1A1A1A] text-white font-semibold' : 'text-[#5A5A5A] hover:text-[#1A1A1A] font-medium'"
                        class="flex-1 py-2.5 px-4 rounded-sm text-xs sm:text-sm transition duration-300 flex items-center justify-center gap-2">
                    <span>E-Tiket &amp; Riwayat</span>
                    <span class="px-2 py-0.5 rounded-sm text-[10px]" :class="mainTab === 'tiket' ? 'bg-white/20 text-white' : 'bg-white border border-neutral-200 text-[#1A1A1A]'">
                        {{ $transactions->count() }}
                    </span>
                </button>

                <button type="button"
                        @click="mainTab = 'paspor'"
                        :class="mainTab === 'paspor' ? 'bg-[#1A1A1A] text-white font-semibold' : 'text-[#5A5A5A] hover:text-[#1A1A1A] font-medium'"
                        class="flex-1 py-2.5 px-4 rounded-sm text-xs sm:text-sm transition duration-300 flex items-center justify-center gap-2">
                    <span>Paspor &amp; Lencana</span>
                    <span class="px-2 py-0.5 rounded-sm text-[10px] bg-[#B8860B] text-white font-semibold">
                        {{ $passportData['unlocked_badges_count'] }} ★
                    </span>
                </button>
            </div>

            {{-- ======================================================== --}}
            {{-- TAB 1: E-TIKET & RIWAYAT KUNJUNGAN                     --}}
            {{-- ======================================================== --}}
            <div x-show="mainTab === 'tiket'" class="space-y-8">

                {{-- MENUNGGU PEMBAYARAN --}}
                @if($pendingTransactions->count() > 0)
                <div>
                    <div class="mb-3">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#8C3B2B] block">Menunggu Pembayaran</span>
                        <h2 class="font-serif text-xl font-semibold text-[#1A1A1A]">
                            Transaksi Belum Selesai
                        </h2>
                    </div>

                    <div class="space-y-3">
                        @foreach($pendingTransactions as $pending)
                            @php
                                $isExpiringSoon = $pending->expired_at && now()->lt($pending->expired_at);
                            @endphp

                            <div class="bg-[#F8F7F4] rounded-sm border border-[#B8860B]/60 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-serif font-semibold text-base text-[#1A1A1A] truncate">
                                            {{ $pending->booking->museum->name ?? '-' }}
                                        </h3>
                                        <span class="px-2 py-0.5 rounded-sm text-[9px] font-semibold uppercase tracking-wider bg-amber-100 text-amber-900 shrink-0">
                                            Pending
                                        </span>
                                    </div>
                                    <p class="text-xs text-[#5A5A5A]">
                                        Kunjungan: {{ \Carbon\Carbon::parse($pending->booking->visit_date)->translatedFormat('d M Y') }}
                                        • Invoice: <span class="font-mono text-[#1A1A1A]">{{ $pending->invoice_code }}</span>
                                        • Total: <strong class="text-[#B8860B]">Rp {{ number_format($pending->total_amount, 0, ',', '.') }}</strong>
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    @if($isExpiringSoon)
                                        <button type="button"
                                                @click="cancelModalOpen = true; cancelBookingId = {{ $pending->booking->id }}; cancelMuseumName = '{{ addslashes($pending->booking->museum->name ?? '-') }}'"
                                                class="px-3.5 py-2 rounded-sm border border-neutral-300 bg-white text-[#5A5A5A] text-xs font-semibold hover:border-[#8C3B2B] hover:text-[#8C3B2B] transition duration-200">
                                            Batal
                                        </button>
                                        <a href="{{ route('user.payment.show3', $pending->id) }}"
                                           class="px-4 py-2 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                                            Bayar Sekarang →
                                        </a>
                                    @else
                                        <span class="px-3 py-1 rounded-sm bg-neutral-200 text-[#5A5A5A] text-xs font-semibold">
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

                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-5 pb-4 border-b border-neutral-200">
                        <div>
                            <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#B8860B] block">E-Tiket</span>
                            <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A]">
                                Riwayat Kunjungan &amp; Tiket
                            </h2>
                        </div>

                        {{-- TAB FILTER --}}
                        <div class="flex items-center gap-1 bg-[#F8F7F4] p-1 rounded-sm border border-neutral-200 self-start sm:self-auto text-xs flex-wrap">
                            <button type="button"
                                    @click="tab = 'semua'"
                                    :class="tab === 'semua' ? 'bg-[#1A1A1A] text-white font-semibold' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'"
                                    class="px-3 py-1.5 rounded-sm transition duration-200">
                                Semua
                            </button>

                            <button type="button"
                                    @click="tab = 'aktif'"
                                    :class="tab === 'aktif' ? 'bg-[#1A1A1A] text-white font-semibold' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'"
                                    class="px-3 py-1.5 rounded-sm transition duration-200">
                                Tiket Aktif
                            </button>

                            <button type="button"
                                    @click="tab = 'dipakai'"
                                    :class="tab === 'dipakai' ? 'bg-[#1A1A1A] text-white font-semibold' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'"
                                    class="px-3 py-1.5 rounded-sm transition duration-200">
                                Sudah Dipakai
                            </button>

                            <button type="button"
                                    @click="tab = 'expired'"
                                    :class="tab === 'expired' ? 'bg-[#1A1A1A] text-white font-semibold' : 'text-[#5A5A5A] hover:text-[#1A1A1A]'"
                                    class="px-3 py-1.5 rounded-sm transition duration-200">
                                Kedaluwarsa
                            </button>
                        </div>
                    </div>

                    {{-- TICKET LIST --}}
                    <div class="space-y-4">
                        @forelse($transactions as $transaction)
                            @php
                                $visitDate = \Carbon\Carbon::parse($transaction->booking->visit_date);
                                $closingTime = $transaction->booking->museum->closing_time ?? '23:59:59';
                                $closingDateTime = \Carbon\Carbon::parse($transaction->booking->visit_date . ' ' . $closingTime);
                                $isExpired = now()->greaterThan($closingDateTime) || $transaction->booking->status === 'expired';

                                if ($transaction->booking->status === 'cancelled') {
                                    $statusTag = 'cancelled';
                                    $statusLabel = 'Dibatalkan';
                                    $statusClass = 'bg-rose-50 text-[#8C3B2B] border border-rose-200';
                                } elseif ($transaction->used_at) {
                                    $statusTag = 'dipakai';
                                    $statusLabel = '✓ Sudah Dipakai';
                                    $statusClass = 'bg-neutral-100 text-[#5A5A5A] border border-neutral-200';
                                } elseif ($isExpired) {
                                    $statusTag = 'expired';
                                    $statusLabel = 'Kedaluwarsa';
                                    $statusClass = 'bg-rose-50 text-[#8C3B2B] border border-rose-200';
                                } else {
                                    $statusTag = 'aktif';
                                    $statusLabel = '● Tiket Aktif';
                                    $statusClass = 'bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold';
                                }

                                $totalPax = ($transaction->booking->adult_qty ?? 0) + ($transaction->booking->student_qty ?? 0) + ($transaction->booking->child_qty ?? 0);
                            @endphp

                            <div x-show="tab === 'semua' || tab === '{{ $statusTag }}'"
                                 class="bg-white rounded-sm border border-neutral-200 hover:border-[#B8860B] overflow-hidden flex flex-col sm:flex-row transition duration-300">

                                {{-- DATE STUB --}}
                                <div class="sm:w-28 bg-[#F8F7F4] p-4 flex flex-row sm:flex-col items-center justify-between sm:justify-center text-center border-b sm:border-b-0 sm:border-r border-neutral-200 shrink-0">
                                    <div>
                                        <span class="text-[10px] font-semibold uppercase tracking-wider text-[#B8860B] block">
                                            {{ $visitDate->translatedFormat('M Y') }}
                                        </span>
                                        <span class="font-serif text-3xl font-semibold text-[#1A1A1A] leading-none block my-1">
                                            {{ $visitDate->format('d') }}
                                        </span>
                                        <span class="text-[11px] text-[#5A5A5A] font-medium">
                                            {{ $visitDate->translatedFormat('l') }}
                                        </span>
                                    </div>
                                    <span class="sm:hidden px-2.5 py-0.5 rounded-sm text-[10px] {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                {{-- INFO STUB --}}
                                <div class="p-4 sm:p-5 flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <div class="hidden sm:flex items-center gap-2 mb-1.5">
                                            <span class="px-2.5 py-0.5 rounded-sm text-[10px] uppercase tracking-wider {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                            <span class="text-[11px] text-[#5A5A5A] font-mono">
                                                {{ $transaction->invoice_code }}
                                            </span>
                                        </div>

                                        <h3 class="font-serif font-semibold text-lg text-[#1A1A1A] truncate">
                                            {{ $transaction->booking->museum->name ?? '-' }}
                                        </h3>

                                        <p class="text-xs text-[#5A5A5A] truncate mt-0.5">
                                            {{ $transaction->booking->museum->address ?? '-' }}
                                        </p>
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-neutral-200 flex items-center gap-3 text-xs text-[#5A5A5A]">
                                        <span><strong class="text-[#1A1A1A]">{{ $totalPax }} Pax</strong></span>
                                        <span>•</span>
                                        <span class="font-semibold text-[#1A1A1A]">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                {{-- ACTION STUB --}}
                                <div class="bg-[#F8F7F4]/60 p-4 flex sm:flex-col items-center justify-center gap-2 border-t sm:border-t-0 sm:border-l border-neutral-200 shrink-0 sm:w-40">
                                    @if($transaction->used_at)
                                        @if($transaction->booking->review)
                                            <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-1.5 rounded-sm border border-emerald-200 text-center w-full">
                                                ★ Dinilai ({{ $transaction->booking->review->rating }}★)
                                            </span>
                                        @else
                                            <button type="button"
                                                    @click="reviewModalOpen = true; reviewBookingId = {{ $transaction->booking->id }}; reviewMuseumName = '{{ addslashes($transaction->booking->museum->name ?? '-') }}'"
                                                    class="w-full py-2 px-3 rounded-sm bg-[#B8860B] hover:bg-[#8C3B2B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300 text-center">
                                                Beri Ulasan
                                            </button>
                                        @endif
                                        <a href="{{ route('user.ticket', $transaction->id) }}"
                                           class="w-full py-2 px-3 rounded-sm border border-neutral-300 text-[#1A1A1A] font-semibold text-xs hover:bg-white transition duration-200 text-center">
                                            Lihat Struk
                                        </a>
                                    @elseif($isExpired)
                                        <span class="text-[11px] font-semibold text-[#8C3B2B] bg-rose-50 px-2.5 py-1.5 rounded-sm border border-rose-200 text-center w-full">
                                            Kedaluwarsa
                                        </span>
                                        <a href="{{ route('user.ticket', $transaction->id) }}"
                                           class="w-full py-2 px-3 rounded-sm border border-neutral-300 text-[#1A1A1A] font-semibold text-xs hover:bg-white transition duration-200 text-center">
                                            Detail Tiket
                                        </a>
                                    @elseif($transaction->booking->status === 'cancelled')
                                        <span class="text-[11px] font-semibold text-[#5A5A5A] text-center w-full">
                                            Dibatalkan
                                        </span>
                                    @else
                                        <a href="{{ route('user.ticket', $transaction->id) }}"
                                           class="w-full py-2.5 px-3 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300 text-center">
                                            Buka E-Tiket QR
                                        </a>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="bg-[#F8F7F4] rounded-sm border border-neutral-200 p-10 text-center text-[#5A5A5A] text-xs sm:text-sm">
                                Belum ada riwayat tiket yang sesuai.
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

            {{-- ======================================================== --}}
            {{-- TAB 2: PASPOR BUDAYA & LENCANA WISATAWAN                 --}}
            {{-- ======================================================== --}}
            <div x-show="mainTab === 'paspor'" x-cloak class="space-y-8">

                {{-- PASSPORT COVER BANNER --}}
                <div class="relative overflow-hidden rounded-sm bg-[#121212] text-white p-6 sm:p-8 border border-neutral-800">
                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                        <div>
                            <span class="inline-block text-[10px] font-semibold uppercase tracking-[0.22em] text-[#C59B27] mb-2">
                                PASPOR BUDAYA NUSANTARA
                            </span>
                            <h2 class="font-serif text-2xl sm:text-3xl font-semibold text-white tracking-tight">
                                {{ $passportData['rank_name'] }}
                            </h2>
                            <p class="text-xs text-neutral-300 mt-1.5 max-w-md leading-relaxed">
                                @if($passportData['next_target'])
                                    Kunjungi {{ $passportData['next_target'] - $passportData['distinct_museums'] }} museum lagi untuk naik ke tingkat penjelajah berikutnya.
                                @else
                                    Selamat! Anda telah meraih gelar tertinggi penjelajah museum Nusantara.
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <div class="bg-white/5 rounded-sm p-3.5 text-center border border-white/15 min-w-[90px]">
                                <span class="font-serif text-2xl font-semibold text-[#C59B27] block">{{ $passportData['total_stamps'] }}</span>
                                <span class="text-[10px] uppercase font-semibold tracking-wider text-neutral-300">Stempel</span>
                            </div>
                            <div class="bg-white/5 rounded-sm p-3.5 text-center border border-white/15 min-w-[90px]">
                                <span class="font-serif text-2xl font-semibold text-[#C59B27] block">{{ $passportData['unlocked_badges_count'] }}/{{ $passportData['total_badges'] }}</span>
                                <span class="text-[10px] uppercase font-semibold tracking-wider text-neutral-300">Lencana</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BUKU STEMPEL PASPOR DIGITAL --}}
                <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8">
                    <div class="flex items-end justify-between mb-6 pb-4 border-b border-neutral-200">
                        <div>
                            <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#B8860B] block mb-1">Koleksi Cap Loket</span>
                            <h3 class="font-serif text-xl font-semibold text-[#1A1A1A]">
                                Lembar Stempel Kunjungan Museum
                            </h3>
                        </div>
                        <span class="text-xs text-[#5A5A5A] font-medium">
                            {{ count($passportData['stamps']) }} Museum Terverifikasi
                        </span>
                    </div>

                    @if(empty($passportData['stamps']))
                        <div class="text-center py-12 px-4 border border-dashed border-neutral-300 rounded-sm bg-[#F8F7F4]">
                            <h4 class="font-serif font-semibold text-base text-[#1A1A1A]">Belum Ada Stempel Paspor</h4>
                            <p class="text-xs text-[#5A5A5A] mt-1.5 max-w-sm mx-auto leading-relaxed">
                                Tiket yang telah dipindai oleh petugas loket di pintu masuk museum akan otomatis mencetak stempel resmi pada paspor digital Anda.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($passportData['stamps'] as $stamp)
                                <div class="relative bg-[#F8F7F4] rounded-sm border border-dashed p-4 flex flex-col justify-between"
                                     style="border-color: {{ $stamp['color'] }}50;">
                                    <div class="flex items-start justify-between gap-2 mb-3">
                                        <div class="w-12 h-12 rounded-full border-2 flex flex-col items-center justify-center text-center p-1"
                                             style="border-color: {{ $stamp['color'] }}; color: {{ $stamp['color'] }};">
                                            <span class="text-[8px] font-bold uppercase tracking-tighter">ITIHASA</span>
                                            <span class="text-[7px] font-bold">★ SAH ★</span>
                                        </div>

                                        <span class="px-2 py-0.5 rounded-sm text-[9px] font-mono font-semibold"
                                              style="background-color: {{ $stamp['color'] }}15; color: {{ $stamp['color'] }};">
                                            {{ $stamp['city'] }}
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="font-serif font-semibold text-sm text-[#1A1A1A] line-clamp-1">
                                            {{ $stamp['museum_name'] }}
                                        </h4>
                                        <p class="text-[11px] text-[#5A5A5A] mt-1">
                                            Tanggal: <strong>{{ $stamp['date'] }}</strong> ({{ $stamp['time'] }} WIB)
                                        </p>
                                        <p class="text-[10px] text-[#5A5A5A] font-mono mt-0.5">
                                            Kode: {{ $stamp['stamp_code'] }} • {{ $stamp['pax'] }} Pax
                                        </p>
                                    </div>

                                    <div class="mt-3 pt-2 border-t border-neutral-200 flex items-center justify-between text-[10px]">
                                        <span class="text-emerald-700 font-semibold">✓ Stempel Sah</span>
                                        @if($stamp['review'])
                                            <span class="text-[#B8860B] font-semibold">★ {{ $stamp['review']->rating }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- GALERI LENCANA WISATAWAN --}}
                <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8">
                    <div class="mb-6 pb-4 border-b border-neutral-200">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#B8860B] block mb-1">Prestasi Wisata</span>
                        <h3 class="font-serif text-xl font-semibold text-[#1A1A1A]">
                            Lencana Penjelajah Budaya
                        </h3>
                        <p class="text-xs text-[#5A5A5A] mt-1">
                            Buka seluruh lencana dengan mengunjungi beragam museum tematik di Indonesia.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($passportData['badges'] as $badge)
                            <div class="rounded-sm p-4 border transition duration-300 flex gap-3.5 {{ $badge['is_unlocked'] ? 'bg-[#F8F7F4] border-[#B8860B]' : 'bg-white border-neutral-200 opacity-75' }}">
                                <div class="w-11 h-11 rounded-sm flex items-center justify-center text-xl shrink-0 {{ $badge['is_unlocked'] ? 'bg-[#1A1A1A] text-[#C59B27]' : 'bg-neutral-100 text-neutral-400 grayscale' }}">
                                    {{ $badge['icon'] }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <h4 class="font-serif font-semibold text-sm text-[#1A1A1A] truncate">
                                            {{ $badge['name'] }}
                                        </h4>
                                        <span class="px-2 py-0.5 rounded-sm text-[9px] font-semibold uppercase tracking-wider shrink-0 {{ $badge['is_unlocked'] ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-neutral-100 text-[#5A5A5A]' }}">
                                            {{ $badge['is_unlocked'] ? 'Terbuka' : 'Terkunci' }}
                                        </span>
                                    </div>

                                    <p class="text-[11px] text-[#5A5A5A] leading-snug">
                                        {{ $badge['description'] }}
                                    </p>

                                    <div class="mt-2.5">
                                        <div class="flex justify-between items-center text-[10px] text-[#5A5A5A] mb-1 font-medium">
                                            <span>Progres: {{ $badge['current'] }}/{{ $badge['target'] }}</span>
                                            <span>{{ $badge['progress_percent'] }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-neutral-200 rounded-sm overflow-hidden">
                                            <div class="h-full transition-all duration-500 {{ $badge['is_unlocked'] ? 'bg-[#B8860B]' : 'bg-neutral-400' }}"
                                                 style="width: {{ $badge['progress_percent'] }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- MODAL ULASAN --}}
            <div x-show="reviewModalOpen" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-[#121212]/70 p-4"
                 @click.self="reviewModalOpen = false">
                <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8 max-w-md w-full">
                    <h3 class="font-serif text-xl font-semibold text-[#1A1A1A] mb-1">Beri Ulasan Kunjungan</h3>
                    <p class="text-xs text-[#5A5A5A] mb-5" x-text="reviewMuseumName"></p>

                    <form :action="'/bookings/' + reviewBookingId + '/review'" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-row-reverse justify-end mb-3">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="modalStar{{ $i }}"
                                       class="hidden peer" required>
                                <label for="modalStar{{ $i }}"
                                       class="cursor-pointer text-2xl text-neutral-300 peer-checked:text-[#B8860B] hover:text-[#B8860B] hover:[&~label]:text-[#B8860B]">★</label>
                            @endfor
                        </div>

                        <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman kunjungan kamu..."
                                  class="w-full rounded-sm border border-neutral-200 p-3 text-[#1A1A1A] text-xs focus:outline-none focus:border-[#B8860B] mb-3 bg-[#F8F7F4] focus:bg-white"></textarea>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-[#1A1A1A] mb-1">Unggah Foto Pengalaman (Maks 3)</label>
                            <label class="w-full px-3 py-2 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs text-[#5A5A5A] hover:border-[#B8860B] transition cursor-pointer flex items-center justify-between">
                                <span class="text-[11px]" x-text="photoCount > 0 ? photoCount + ' foto dipilih' : 'Pilih hingga 3 foto (JPG, PNG, WEBP)'"></span>
                                <span class="px-2 py-0.5 rounded-sm bg-white text-[#1A1A1A] text-[10px] font-semibold border border-neutral-200">Pilih</span>
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
                                        <div class="w-12 h-12 rounded-sm overflow-hidden border border-[#B8860B]">
                                            <img :src="p" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" @click="reviewModalOpen = false"
                                    class="flex-1 py-2.5 rounded-sm border border-neutral-300 text-[#5A5A5A] text-xs font-semibold hover:bg-[#F8F7F4]">
                                Batal
                            </button>
                            <button type="submit"
                                    class="flex-1 py-2.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                                Kirim Ulasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MODAL PEMBATALAN BOOKING --}}
            <div x-show="cancelModalOpen" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-[#121212]/70 p-4"
                 @click.self="cancelModalOpen = false">
                <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8 max-w-md w-full">
                    <h3 class="font-serif text-xl font-semibold text-[#1A1A1A] mb-1">Batalkan Booking Tiket</h3>
                    <p class="text-xs text-[#5A5A5A] mb-4" x-text="cancelMuseumName"></p>

                    <div class="bg-rose-50 border border-rose-200 text-[#8C3B2B] text-xs rounded-sm p-3.5 mb-4">
                        Untuk booking yang sudah dibayar, pembatalan hanya dapat dilakukan maksimal 12 jam sebelum jadwal kunjungan.
                    </div>

                    <form :action="'/bookings/' + cancelBookingId + '/cancel'" method="POST">
                        @csrf
                        <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                            Alasan Pembatalan <span class="text-[#8C3B2B]">*</span>
                        </label>
                        <textarea name="cancellation_reason" rows="3" required
                                  placeholder="Tuliskan alasan pembatalan..."
                                  class="w-full rounded-sm border border-neutral-200 p-3 text-[#1A1A1A] text-xs focus:outline-none focus:border-[#8C3B2B] mb-4 bg-[#F8F7F4] focus:bg-white"></textarea>

                        <div class="flex gap-2">
                            <button type="button" @click="cancelModalOpen = false"
                                    class="flex-1 py-2.5 rounded-sm border border-neutral-300 text-[#5A5A5A] text-xs font-semibold hover:bg-[#F8F7F4]">
                                Tutup
                            </button>
                            <button type="submit"
                                    class="flex-1 py-2.5 rounded-sm bg-[#8C3B2B] text-white text-xs font-semibold uppercase tracking-wider hover:bg-[#1A1A1A] transition duration-300">
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
