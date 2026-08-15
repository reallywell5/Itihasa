@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')

<section class="max-w-7xl mx-auto py-6 sm:py-10">

    <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-6 lg:gap-8 items-start">

        {{-- LEFT PROFILE CARD --}}
        <div>
            <div class="bg-white rounded-2xl border border-[#EADBC8] overflow-hidden shadow-sm">
                {{-- HEADER --}}
                <div class="bg-[#102A43] p-6 text-center relative">
                    <div class="w-20 h-20 mx-auto rounded-full bg-white flex items-center justify-center text-2xl font-bold text-[#102A43] shadow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <h2 class="text-white text-lg font-bold mt-3">
                        {{ auth()->user()->name }}
                    </h2>

                    <p class="text-white/70 text-xs mt-0.5">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                {{-- INFORMASI --}}
                <div class="p-4 sm:p-5 space-y-3 text-xs">
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Peran</span>
                        <span class="font-bold text-[#102A43]">Pengunjung</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-500">
                        <span>Bergabung</span>
                        <span class="font-bold text-[#102A43]">
                            {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-slate-500">
                        <span>Total Transaksi</span>
                        <span class="font-bold text-[#102A43]">
                            {{ $transactions->count() }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-slate-500">
                        <span>Favorit Wishlist</span>
                        <span class="font-bold text-[#102A43]">
                            {{ $wishlists->count() }}
                        </span>
                    </div>
                </div>

                {{-- AKSI --}}
                <div class="border-t border-[#EADBC8] p-4 space-y-2">
                    <a href="{{ route('user.profile.edit') }}"
                       class="w-full flex justify-center py-2.5 rounded-xl bg-[#102A43] text-white font-bold text-xs hover:bg-[#0c2238] transition shadow">
                        Edit Profil
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full py-2.5 rounded-xl border border-red-200 text-red-600 font-bold text-xs hover:bg-red-50 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT CONTENT --}}
        <div class="space-y-6" x-data="{ cancelModalOpen: false, cancelBookingId: null, cancelMuseumName: '' }">

            {{-- MENUNGGU PEMBAYARAN --}}
            @if($pendingTransactions->count() > 0)
            <div>
                <div class="mb-3">
                    <h2 class="text-base sm:text-lg font-bold text-[#102A43]">
                        Menunggu Pembayaran
                    </h2>
                    <p class="text-slate-500 text-xs">
                        Transaksi yang belum diselesaikan. Lanjutkan pembayaran sebelum waktu habis.
                    </p>
                </div>

                <div class="space-y-3">
                    @foreach($pendingTransactions as $pending)
                        @php
                            $isExpiringSoon = $pending->expired_at && now()->lt($pending->expired_at);
                        @endphp

                        <div class="bg-amber-50/50 rounded-2xl border border-amber-200 p-4 sm:p-5 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-sm text-[#102A43]">
                                        {{ $pending->booking->museum->name ?? '-' }}
                                    </h3>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        Kunjungan: <strong>{{ \Carbon\Carbon::parse($pending->booking->visit_date)->format('d M Y') }}</strong>
                                        • Invoice: <span class="font-mono">{{ $pending->invoice_code }}</span>
                                    </p>
                                </div>

                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold self-start sm:self-auto bg-amber-100 text-amber-800 border border-amber-200">
                                    ● Menunggu Pembayaran
                                </span>
                            </div>

                            <div class="mt-3 pt-3 border-t border-amber-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Total Tagihan</span>
                                    <p class="font-bold text-base text-[#B88A44]">
                                        Rp {{ number_format($pending->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>

                                @if($isExpiringSoon)
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                @click="cancelModalOpen = true; cancelBookingId = {{ $pending->booking->id }}; cancelMuseumName = '{{ addslashes($pending->booking->museum->name ?? '-') }}'"
                                                class="px-3.5 py-2 rounded-xl border border-red-200 text-red-600 text-xs font-bold hover:bg-red-50 transition">
                                            Batalkan
                                        </button>
                                        <a href="{{ route('user.payment.show3', $pending->id) }}"
                                           class="px-4 py-2 rounded-xl bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 transition shadow">
                                            Bayar Sekarang
                                        </a>
                                    </div>
                                @else
                                    <span class="px-3 py-1.5 rounded-xl bg-gray-200 text-gray-500 text-xs font-semibold">
                                        Waktu Habis
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- RIWAYAT KUNJUNGAN --}}
            <div x-data="{ tab: 'semua', reviewModalOpen: false, reviewBookingId: null, reviewMuseumName: '' }">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-[#102A43]">
                            Riwayat Kunjungan
                        </h2>
                        <p class="text-slate-500 text-xs">
                            Daftar museum yang pernah atau akan kamu kunjungi.
                        </p>
                    </div>

                    {{-- TAB FILTER --}}
                    <div class="flex flex-wrap items-center gap-1.5 bg-white p-1 rounded-xl border border-[#EADBC8] shadow-sm self-start sm:self-auto text-xs">
                        <button type="button"
                                @click="tab = 'semua'"
                                :class="tab === 'semua' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-[#F9F7F2]'"
                                class="px-3 py-1.5 rounded-lg font-bold transition">
                            Semua
                        </button>

                        <button type="button"
                                @click="tab = 'aktif'"
                                :class="tab === 'aktif' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-[#F9F7F2]'"
                                class="px-3 py-1.5 rounded-lg font-bold transition">
                            Aktif
                        </button>

                        <button type="button"
                                @click="tab = 'dipakai'"
                                :class="tab === 'dipakai' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-[#F9F7F2]'"
                                class="px-3 py-1.5 rounded-lg font-bold transition">
                            Sudah Dipakai
                        </button>

                        <button type="button"
                                @click="tab = 'expired'"
                                :class="tab === 'expired' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-[#F9F7F2]'"
                                class="px-3 py-1.5 rounded-lg font-bold transition">
                            Kedaluwarsa
                        </button>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($transactions as $transaction)
                        @php
                            if ($transaction->booking->status === 'expired') {
                                $statusTag = 'expired';
                            } else {
                                $statusTag = $transaction->used_at ? 'dipakai' : 'aktif';
                            }
                        @endphp

                        <div x-show="tab === 'semua' || tab === '{{ $statusTag }}'"
                             x-transition
                             class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-5 shadow-sm hover:shadow transition">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-sm text-[#102A43]">
                                        {{ $transaction->booking->museum->name }}
                                    </h3>

                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        Kunjungan: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($transaction->booking->visit_date)->format('d M Y') }}</strong>
                                        • Invoice: <span class="font-mono text-slate-500">{{ $transaction->invoice_code }}</span>
                                    </p>
                                </div>

                                @if($transaction->booking->status === 'expired')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold self-start sm:self-auto bg-red-100 text-red-700 border border-red-200">
                                        ● Kedaluwarsa
                                    </span>
                                @elseif($transaction->used_at)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold self-start sm:self-auto bg-slate-100 text-slate-600 border border-slate-200">
                                        ✓ Sudah Dipakai
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold self-start sm:self-auto bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        ● Aktif
                                    </span>
                                @endif
                            </div>

                            {{-- RINCIAN TIKET --}}
                            <div class="mt-3 py-2.5 border-t border-b border-[#EADBC8]/60 space-y-1 text-xs">
                                <p class="text-[10px] uppercase font-bold tracking-wider text-[#B88A44]">Rincian Tiket:</p>
                                @foreach($transaction->booking->ticket_items ?? [] as $item)
                                    <div class="flex justify-between text-slate-700">
                                        <span>• {{ $item['ticket_name'] ?? 'Tiket' }} x{{ $item['qty'] ?? 1 }}</span>
                                        <span class="font-semibold text-[#102A43]">
                                            @if(($item['subtotal'] ?? 0) > 0)
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            @else
                                                {{ $item['qty'] ?? 1 }} Tiket
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Total Pembayaran</span>
                                    <p class="font-bold text-base text-[#B88A44]">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>

                                @if($transaction->booking->status !== 'expired')
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('user.ticket', $transaction->id) }}"
                                           class="px-4 py-2 rounded-xl bg-[#102A43] text-white text-xs font-bold hover:bg-[#0c2238] transition shadow">
                                            Lihat Tiket QR
                                        </a>

                                        @if(!$transaction->used_at)
                                            <button type="button"
                                                    @click="cancelModalOpen = true; cancelBookingId = {{ $transaction->booking->id }}; cancelMuseumName = '{{ addslashes($transaction->booking->museum->name) }}'"
                                                    class="px-3.5 py-2 rounded-xl border border-red-200 text-red-600 text-xs font-bold hover:bg-red-50 transition">
                                                Batalkan
                                            </button>
                                        @endif

                                        @if($transaction->used_at && !$transaction->booking->review)
                                            <button type="button"
                                                    @click="reviewModalOpen = true; reviewBookingId = {{ $transaction->booking->id }}; reviewMuseumName = '{{ addslashes($transaction->booking->museum->name) }}'"
                                                    class="px-3.5 py-2 rounded-xl border border-[#B88A44] text-[#B88A44] text-xs font-bold hover:bg-[#F6F1E8] transition">
                                                Beri Ulasan
                                            </button>
                                        @elseif($transaction->used_at && $transaction->booking->review)
                                            <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold">
                                                ✓ Direview
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <button disabled class="px-3.5 py-2 rounded-xl bg-gray-200 text-gray-400 text-xs font-semibold cursor-not-allowed">
                                        Tiket Hangus
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl border border-[#EADBC8] p-8 text-center text-xs text-slate-400">
                            Belum ada riwayat transaksi tiket.
                        </div>
                    @endforelse
                </div>
                    {{-- MODAL ULASAN --}}
                    <div x-show="reviewModalOpen" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                        @click.self="reviewModalOpen = false">
                        <div class="bg-white rounded-2xl p-5 sm:p-6 max-w-md w-full shadow-xl">
                            <h3 class="text-base font-bold text-[#102A43] mb-0.5">Beri Ulasan Kunjungan</h3>
                            <p class="text-xs text-slate-500 mb-4" x-text="reviewMuseumName"></p>

                            <form :action="'/bookings/' + reviewBookingId + '/review'" method="POST">
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
                                <div class="flex gap-2">
                                    <button type="button" @click="reviewModalOpen = false"
                                            class="flex-1 py-2 rounded-xl border border-[#EADBC8] text-slate-500 text-xs font-semibold hover:bg-slate-50">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="flex-1 py-2 rounded-xl bg-[#102A43] text-white text-xs font-bold hover:bg-[#0c2238] transition shadow">
                                        Kirim Ulasan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL PEMBATALAN BOOKING --}}
            <div x-show="cancelModalOpen" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                @click.self="cancelModalOpen = false">
                <div class="bg-white rounded-2xl p-5 sm:p-6 max-w-md w-full shadow-xl">
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
                                    class="flex-1 py-2 rounded-xl border border-[#EADBC8] text-slate-500 text-xs font-semibold hover:bg-slate-50">
                                Tutup
                            </button>
                            <button type="submit"
                                    class="flex-1 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition shadow">
                                Ya, Batalkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- WISHLIST FAVORIT --}}
            <div>
                <div class="mb-3">
                    <h2 class="text-base sm:text-lg font-bold text-[#102A43]">
                        Museum Favorit
                    </h2>
                    <p class="text-slate-500 text-xs">
                        Museum yang kamu simpan untuk dikunjungi nanti.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($wishlists as $wishlist)
                        <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 shadow-sm flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-[#102A43] line-clamp-1">
                                    {{ $wishlist->museum->name }}
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1">
                                    {{ $wishlist->museum->address }}
                                </p>
                            </div>

                            <div class="mt-3 pt-3 border-t border-[#EADBC8]/60 flex items-center justify-between">
                                <a href="{{ route('museum.detail', $wishlist->museum->id) }}"
                                   class="px-3.5 py-1.5 rounded-xl bg-[#102A43] text-white text-xs font-semibold hover:bg-[#0c2238] transition shadow">
                                    Lihat Museum
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white rounded-2xl border border-[#EADBC8] p-8 text-center text-xs text-slate-400">
                            Belum ada museum favorit di wishlist kamu.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</section>

@endsection
