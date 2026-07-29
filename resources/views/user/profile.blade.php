@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')

<section class="max-w-7xl mx-auto px-6 lg:px-8 py-14">

    <div class="grid lg:grid-cols-[320px_1fr] gap-10">

        {{-- LEFT PROFILE --}}
        <div>

            {{-- KARTU PROFIL --}}
            <div class="bg-white rounded-[32px] border border-[#EADBC8] overflow-hidden shadow-lg">

                {{-- HEADER --}}
                <div class="bg-[#102A43] px-6 py-8 text-center relative">

                    <div class="w-24 h-24 mx-auto rounded-full bg-white flex items-center justify-center text-3xl font-bold text-[#102A43] shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <h2 class="text-white text-2xl font-bold mt-5">
                        {{ auth()->user()->name }}
                    </h2>

                    <p class="text-white/70 text-sm mt-1">
                        {{ auth()->user()->email }}
                    </p>

                </div>

                {{-- INFORMASI --}}
                <div class="p-6 space-y-5">

                    <div class="flex justify-between">
                        <span class="text-slate-500">Peran</span>
                        <span class="font-semibold text-[#102A43]">Pengunjung</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Bergabung</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Total Tiket</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $transactions->count() }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Wishlist</span>
                        <span class="font-semibold text-[#102A43]">
                            {{ $wishlists->count() }}
                        </span>
                    </div>

                </div>

                {{-- AKSI --}}
                <div class="border-t border-[#EADBC8] p-5 space-y-3">

                    <a href="{{ route('user.profile.edit') }}"
                       class="w-full flex justify-center py-3 rounded-xl bg-[#102A43] text-white font-semibold">
                        Edit Profil
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full py-3 rounded-xl border border-red-200 text-red-500 font-semibold hover:bg-red-50 transition">
                            Keluar
                        </button>
                    </form>

                </div>

            </div>

        </div>

        {{-- RIGHT CONTENT --}}
        <div class="space-y-10">

            {{-- RIWAYAT --}}
            <div x-data="{ tab: 'semua' }">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-[#102A43]">
                            Riwayat Kunjungan
                        </h2>
                        <p class="text-slate-500 mt-1 text-sm">
                            Daftar museum yang pernah atau akan kamu kunjungi.
                        </p>
                    </div>

                    {{-- TAB FILTER --}}
                    <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl border border-[#EADBC8] shadow-sm self-start sm:self-auto">
                        <button type="button"
                                @click="tab = 'semua'"
                                :class="tab === 'semua' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-[#F9F7F2]'"
                                class="px-4 py-2 rounded-xl text-xs font-semibold transition">
                            Semua
                        </button>

                        <button type="button"
                                @click="tab = 'aktif'"
                                :class="tab === 'aktif' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-[#F9F7F2]'"
                                class="px-4 py-2 rounded-xl text-xs font-semibold transition">
                            Aktif
                        </button>

                        <button type="button"
                                @click="tab = 'dipakai'"
                                :class="tab === 'dipakai' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-[#F9F7F2]'"
                                class="px-4 py-2 rounded-xl text-xs font-semibold transition">
                            Sudah Dipakai
                        </button>
                    </div>
                </div>

                <div class="space-y-5">

                    @forelse($transactions as $transaction)
                        @php
                            $statusTag = $transaction->used_at ? 'dipakai' : 'aktif';
                        @endphp

                        <div x-show="tab === 'semua' || tab === '{{ $statusTag }}'"
                             x-transition
                             class="bg-white rounded-[24px] border border-[#EADBC8] p-6 shadow-sm hover:shadow-md transition">

                            <div class="flex justify-between items-start">

                                <div>
                                    <h3 class="font-bold text-lg text-[#102A43]">
                                        {{ $transaction->booking->museum->name }}
                                    </h3>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Tanggal Kunjungan: <strong class="text-slate-600">{{ \Carbon\Carbon::parse($transaction->booking->visit_date)->format('d M Y') }}</strong>
                                    </p>

                                    <p class="text-xs font-mono text-slate-400 mt-0.5">
                                        Invoice: {{ $transaction->invoice_code }}
                                    </p>
                                </div>

                                <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                    {{ $transaction->used_at ? 'bg-gray-100 text-gray-600 border border-gray-200' : 'bg-green-100 text-green-700 border border-green-200' }}">
                                    {{ $transaction->used_at ? '✓ Sudah Dipakai' : '● Aktif' }}
                                </span>

                            </div>

                            {{-- RINCIAN TIKET PADA CARD RIWAYAT --}}
                            <div class="mt-4 py-3 border-t border-b border-[#EADBC8]/60 space-y-1.5">
                                <p class="text-[11px] uppercase font-bold tracking-wider text-[#B88A44]">Rincian Tiket:</p>
                                @foreach($transaction->booking->ticket_items as $item)
                                    <div class="flex justify-between text-xs text-slate-700">
                                        <span>• {{ $item['ticket_name'] }} x {{ $item['qty'] }}</span>
                                        <span class="font-semibold text-[#102A43]">
                                            @if($item['subtotal'] > 0)
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            @else
                                                {{ $item['qty'] }} Tiket
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 flex justify-between items-center">

                                <div>
                                    <span class="text-xs text-slate-400 block">Total Pembayaran</span>
                                    <p class="font-bold text-lg text-[#B88A44]">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>

                                <a href="{{ route('user.ticket', $transaction->id) }}"
                                   class="px-5 py-2.5 rounded-xl bg-[#102A43] text-white text-xs font-semibold hover:bg-[#0c2238] transition shadow-sm">
                                    Lihat Tiket QR
                                </a>

                            </div>

                        </div>

                    @empty

                        <div class="bg-white rounded-[24px] border border-[#EADBC8] p-8 text-center text-slate-500">
                            Belum ada riwayat kunjungan.
                        </div>

                    @endforelse

                </div>

            </div>

            {{-- WISHLIST --}}
            <div>

                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-[#102A43]">
                        Museum Favorit
                    </h2>
                    <p class="text-slate-500 mt-2">
                        Museum yang kamu simpan untuk dikunjungi nanti.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-5">

                    @forelse($wishlists as $wishlist)

                    <div class="bg-white rounded-[24px] border border-[#EADBC8] p-5 shadow-sm">

                        <h3 class="font-bold text-[#102A43] mb-2">
                            {{ $wishlist->museum->name }}
                        </h3>

                        <p class="text-sm text-slate-500 mb-4">
                            {{ $wishlist->museum->address }}
                        </p>

                        <a href="{{ route('museum.detail', $wishlist->museum->id) }}"
                           class="inline-flex px-4 py-2 rounded-xl bg-[#102A43] text-white text-sm font-semibold">
                            Lihat Detail
                        </a>

                    </div>

                    @empty

                    <div class="bg-white rounded-[24px] border border-[#EADBC8] p-8 text-center text-slate-500">
                        Belum ada wishlist.
                    </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
