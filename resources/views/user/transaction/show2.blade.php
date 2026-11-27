@extends('layouts.user')

@section('title', 'Tiket QR Digital')

@section('content')

@php
    $visitDate = \Carbon\Carbon::parse($transaction->booking->visit_date);
    $closingTime = $transaction->booking->museum->closing_time ?? '23:59:59';
    $closingDateTime = \Carbon\Carbon::parse($transaction->booking->visit_date . ' ' . $closingTime);
    $isExpired = now()->greaterThan($closingDateTime) || $transaction->booking->status === 'expired';
    $isUsed = (bool) $transaction->used_at;
@endphp

<section class="max-w-5xl mx-auto py-4 sm:py-8">

    {{-- HEADER --}}
    <div class="text-center mb-10 pb-6 border-b border-neutral-200">
        <span class="inline-block text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] mb-2">
            E-Tiket Resmi Itihasa
        </span>

        <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[#1A1A1A] tracking-tight">
            @if($isUsed)
                Tiket Telah Digunakan
            @elseif($isExpired)
                Tiket Kedaluwarsa
            @else
                Tiket Masuk Digital
            @endif
        </h1>

        <p class="text-xs sm:text-sm text-[#5A5A5A] mt-2 max-w-lg mx-auto leading-relaxed">
            @if($isUsed)
                Tiket ini telah divalidasi oleh petugas gerbang museum. Silakan bagikan ulasan pengalaman kunjungan Anda.
            @elseif($isExpired)
                Masa berlaku tiket ini telah berakhir karena melewati jadwal kunjungan yang ditetapkan.
            @else
                Tunjukkan QR Code ini kepada petugas loket atau gerbang saat tiba di area museum.
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[380px_1fr] gap-8 items-start">

        {{-- LEFT COLUMN: QR / USED STAMP / REVIEW --}}
        <div class="bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6 sm:p-7 text-center">

            @if($isUsed)
                <div class="w-full bg-white rounded-sm border border-emerald-300 p-6 mb-5 text-center">
                    <div class="w-12 h-12 mx-auto rounded-sm bg-emerald-700 text-white flex items-center justify-center text-xl font-bold mb-3">
                        ✓
                    </div>
                    <h3 class="font-serif text-lg font-semibold text-[#1A1A1A]">Tiket Telah Digunakan</h3>
                    <p class="text-xs text-[#5A5A5A] mt-1">
                        Divalidasi pada {{ $transaction->used_at->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                    <span class="inline-block mt-3 px-3 py-1 rounded-sm bg-neutral-100 text-[#5A5A5A] text-[10px] font-semibold tracking-wider uppercase">
                        QR Dinonaktifkan
                    </span>
                </div>

                @if($transaction->booking->review)
                    <div class="bg-white rounded-sm border border-neutral-200 p-5 text-left">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] uppercase tracking-wider font-semibold text-[#B8860B]">Ulasan Anda</span>
                            <div class="flex text-[#B8860B] text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $transaction->booking->review->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-[#1A1A1A] italic leading-relaxed">
                            "{{ $transaction->booking->review->comment ?? 'Pengalaman berkunjung yang sangat berkesan.' }}"
                        </p>
                        <p class="text-[10px] text-[#5A5A5A] mt-2">
                            ✓ Terima kasih telah memberikan ulasan.
                        </p>
                    </div>
                @else
                    <div class="bg-white rounded-sm border border-neutral-200 p-5 text-left">
                        <h4 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-1">Beri Ulasan Museum</h4>
                        <p class="text-xs text-[#5A5A5A] mb-4">
                            Bagikan kesan kunjungan Anda ke {{ $transaction->booking->museum->name }}.
                        </p>

                        <form action="{{ route('reviews.store', $transaction->booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3" x-data="{ photoCount: 0, previews: [] }">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-[#1A1A1A] mb-1">Rating Kunjungan</label>
                                <div class="flex flex-row-reverse justify-end gap-1">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden peer" required>
                                        <label for="star{{ $i }}" class="cursor-pointer text-2xl text-neutral-300 peer-checked:text-[#B8860B] hover:text-[#B8860B] hover:[&~label]:text-[#B8860B] transition">★</label>
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#1A1A1A] mb-1">Komentar / Pengalaman</label>
                                <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman menarik selama berkunjung..."
                                          class="w-full rounded-sm border border-neutral-200 p-2.5 text-xs text-[#1A1A1A] focus:outline-none focus:border-[#B8860B] bg-[#F8F7F4] focus:bg-white"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#1A1A1A] mb-1">Foto Pengalaman (Maks 3)</label>
                                <label class="w-full px-3 py-2 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs text-[#5A5A5A] hover:border-[#B8860B] transition cursor-pointer flex items-center justify-between">
                                    <span class="text-[11px]" x-text="photoCount > 0 ? photoCount + ' foto dipilih' : 'Pilih hingga 3 foto (JPG, PNG, WEBP)'"></span>
                                    <span class="px-2 py-0.5 rounded-sm bg-white text-[#1A1A1A] text-[10px] font-semibold border border-neutral-200">Upload</span>
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

                            <button type="submit"
                                    class="w-full py-2.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                @endif

            @elseif($isExpired)
                <div class="w-full bg-white rounded-sm border border-rose-200 p-6 mb-5 text-center">
                    <div class="w-12 h-12 mx-auto rounded-sm bg-[#8C3B2B] text-white flex items-center justify-center text-xl font-bold mb-3">
                        ✕
                    </div>
                    <h3 class="font-serif text-lg font-semibold text-[#1A1A1A]">Tiket Kedaluwarsa</h3>
                    <p class="text-xs text-[#5A5A5A] mt-1">
                        Jadwal kunjungan telah berakhir pada {{ $visitDate->translatedFormat('d F Y') }}
                    </p>
                    <span class="inline-block mt-3 px-3 py-1 rounded-sm bg-rose-50 text-[#8C3B2B] text-[10px] font-semibold tracking-wider uppercase">
                        Tidak Dapat Digunakan
                    </span>
                </div>

                <a href="{{ route('museum.detail', $transaction->booking->museum_id) }}"
                   class="w-full inline-block py-3 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300">
                    Pesan Tiket Baru
                </a>

            @else
                <div class="w-60 h-60 mx-auto bg-white rounded-sm border border-neutral-200 flex items-center justify-center p-3 mb-4">
                    {!! QrCode::size(210)->generate($transaction->invoice_code) !!}
                </div>

                <p class="text-[10px] text-[#5A5A5A] font-semibold uppercase tracking-widest mb-1">
                    Kode Invoice Tiket
                </p>

                <h2 class="text-base sm:text-lg font-bold font-mono text-[#1A1A1A]">
                    {{ $transaction->invoice_code }}
                </h2>

                <div class="mt-2.5 inline-flex px-3 py-1 rounded-sm bg-white border border-neutral-200 text-[#B8860B] font-semibold text-xs">
                    {{ $transaction->booking->museum->name }}
                </div>

                @if ($transaction->booking->is_rombongan)
                    <div class="mt-2 block">
                        <span class="inline-flex px-2.5 py-0.5 rounded-sm bg-white border border-neutral-200 text-[#1A1A1A] font-semibold text-[11px]">
                            Rombongan · {{ $transaction->booking->jumlah_anggota }} orang
                        </span>
                    </div>
                @endif

                <div class="mt-6 space-y-2.5">
                    <a href="{{ route('user.ticket.download', $transaction->id) }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-[#1A1A1A] hover:bg-[#B8860B] text-white px-4 py-3 rounded-sm transition duration-300 font-semibold text-xs uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4"/>
                        </svg>
                        <span>Unduh Tiket Digital</span>
                    </a>

                    <p class="text-[11px] text-[#5A5A5A] leading-relaxed">
                        Simpan gambar tiket di perangkat Anda untuk pemindaian luring di pintu masuk.
                    </p>
                </div>
            @endif

        </div>

        {{-- RIGHT COLUMN: DETAIL KUNJUNGAN --}}
        <div class="space-y-6">

            <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8">
                <h2 class="font-serif text-xl font-semibold text-[#1A1A1A] mb-5 pb-3 border-b border-neutral-200">
                    Informasi Tiket &amp; Kunjungan
                </h2>

                <div class="space-y-3 text-xs sm:text-sm">
                    <div class="flex justify-between items-center py-1.5 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Nama Pemesan</span>
                        <span class="font-semibold text-[#1A1A1A]">{{ $transaction->booking->user->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Museum Tujuan</span>
                        <span class="font-semibold text-[#1A1A1A]">{{ $transaction->booking->museum->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Tanggal Kunjungan</span>
                        <span class="font-semibold text-[#1A1A1A]">
                            {{ $transaction->booking->visit_date ? \Carbon\Carbon::parse($transaction->booking->visit_date)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>

                    <div class="py-2 border-b border-neutral-100 space-y-2">
                        <p class="text-[10px] uppercase tracking-wider font-semibold text-[#B8860B]">Rincian Varian Tiket</p>
                        <div class="bg-[#F8F7F4] p-3.5 rounded-sm border border-neutral-200 space-y-1.5 text-xs">
                            @foreach($transaction->booking->ticket_items ?? [] as $item)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-[#1A1A1A]">{{ $item['ticket_name'] ?? 'Tiket' }} x{{ $item['qty'] ?? 1 }}</span>
                                    <span class="font-semibold text-[#1A1A1A]">
                                        @if(($item['subtotal'] ?? 0) > 0)
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        @else
                                            {{ $item['qty'] ?? 1 }} Tiket
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($transaction->booking->is_rombongan && !empty($transaction->booking->manifest))
                        <div class="py-2 border-b border-neutral-100 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] uppercase tracking-wider font-semibold text-[#B8860B]">Manifes Peserta Rombongan</span>
                                <span class="text-[11px] font-semibold text-[#5A5A5A]">{{ count($transaction->booking->manifest) }} orang</span>
                            </div>
                            <div class="bg-[#F8F7F4] p-3.5 rounded-sm border border-neutral-200 max-h-44 overflow-y-auto text-xs">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                    @foreach ($transaction->booking->manifest as $index => $nama)
                                        <div class="flex items-center gap-1.5 text-[#1A1A1A]">
                                            <span class="text-[10px] font-semibold text-[#B8860B] w-4 shrink-0">{{ $index + 1 }}.</span>
                                            <span class="truncate">{{ $nama }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center py-1.5 border-b border-neutral-100 text-[#5A5A5A]">
                        <span>Total Pembayaran</span>
                        <span class="font-semibold text-[#B8860B] text-base">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-1.5">
                        <span class="text-[#5A5A5A]">Status Gerbang</span>
                        @if($isUsed)
                            <span class="px-2.5 py-0.5 rounded-sm text-[10px] font-semibold uppercase tracking-wider bg-neutral-100 text-[#5A5A5A] border border-neutral-200">
                                ✓ Sudah Digunakan
                            </span>
                        @elseif($isExpired)
                            <span class="px-2.5 py-0.5 rounded-sm text-[10px] font-semibold uppercase tracking-wider bg-rose-50 text-[#8C3B2B] border border-rose-200">
                                ✕ Kedaluwarsa
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-sm text-[10px] font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
                                ● Siap Dipindai
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('user.profile') }}"
                   class="py-3 rounded-sm border border-neutral-300 text-[#1A1A1A] font-semibold text-xs uppercase tracking-wider text-center hover:bg-[#F8F7F4] transition duration-300">
                    ← Riwayat Tiket
                </a>

                <a href="{{ route('user.home') }}"
                   class="py-3 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider text-center transition duration-300">
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>

</section>

@endsection
