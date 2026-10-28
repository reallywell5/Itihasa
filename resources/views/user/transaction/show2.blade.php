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

<section class="max-w-5xl mx-auto py-8 sm:py-12">

    {{-- HEADER --}}
    <div class="text-center mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#B88A44]/15 text-[#B88A44] text-xs font-bold uppercase tracking-wider mb-2">
            🎟 E-Tiket Resmi Itihasa
        </span>

        <h1 class="font-display text-2xl sm:text-3xl font-bold text-[#102A43]">
            @if($isUsed)
                Tiket Telah Digunakan
            @elseif($isExpired)
                Tiket Kedaluwarsa
            @else
                Tiket Masuk Digital
            @endif
        </h1>

        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
            @if($isUsed)
                Tiket ini telah selesai divalidasi oleh petugas gate museum. Silakan tinggalkan ulasan pengalaman kunjungan Anda.
            @elseif($isExpired)
                Masa berlaku tiket ini telah berakhir karena melewati jadwal kunjungan.
            @else
                Tunjukkan QR Code ini kepada petugas loket/gate saat tiba di area museum.
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[380px_1fr] gap-8 items-start">

        {{-- LEFT COLUMN: QR CODE / USED STAMP / REVIEW BOX --}}
        <div class="bg-white rounded-3xl border border-[#EADBC8] shadow-sm p-6 text-center">

            @if($isUsed)
                {{-- STATUS SUDAH DIGUNAKAN --}}
                <div class="w-full bg-emerald-50/80 rounded-2xl border-2 border-emerald-300 p-6 mb-5 text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-emerald-600 text-white flex items-center justify-center text-3xl font-bold mb-3 shadow-md">
                        ✓
                    </div>
                    <h3 class="font-bold text-base text-emerald-950">Tiket Telah Digunakan</h3>
                    <p class="text-xs text-emerald-800 mt-1">
                        Divalidasi pada {{ $transaction->used_at->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                    <div class="mt-3">
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-200/80 text-emerald-900 text-[10px] font-bold tracking-wider uppercase">
                            QR Dinonaktifkan
                        </span>
                    </div>
                </div>

                {{-- REVIEW SECTION --}}
                @if($transaction->booking->review)
                    <div class="bg-[#F6F1E8]/60 rounded-2xl border border-[#EADBC8] p-4 text-left">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] uppercase font-bold text-[#B88A44]">Ulasan Anda</span>
                            <div class="flex text-[#B88A44] text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $transaction->booking->review->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-slate-700 italic mt-1">
                            "{{ $transaction->booking->review->comment ?? 'Pengalaman berkunjung yang sangat berkesan.' }}"
                        </p>
                        <p class="text-[10px] text-slate-400 mt-2">
                            ✓ Terima kasih telah memberikan ulasan.
                        </p>
                    </div>
                @else
                    <div class="bg-[#FAF7F2] rounded-2xl border border-[#EADBC8] p-5 text-left">
                        <h4 class="font-bold text-sm text-[#102A43] mb-1">Beri Ulasan Museum</h4>
                        <p class="text-[11px] text-slate-500 mb-3">
                            Bagikan pengalaman Anda berkunjung ke {{ $transaction->booking->museum->name }}.
                        </p>

                        <form action="{{ route('reviews.store', $transaction->booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3" x-data="{ photoCount: 0, previews: [] }">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Rating Kunjungan</label>
                                <div class="flex flex-row-reverse justify-end gap-1">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden peer" required>
                                        <label for="star{{ $i }}" class="cursor-pointer text-2xl text-slate-300 peer-checked:text-[#B88A44] hover:text-[#B88A44] hover:[&~label]:text-[#B88A44] transition">★</label>
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Komentar / Pengalaman</label>
                                <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman menarik selama berkunjung..."
                                          class="w-full rounded-xl border border-[#EADBC8] p-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#B88A44] bg-white"></textarea>
                            </div>

                            {{-- FOTO UPLOAD --}}
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">📷 Foto Pengalaman (Maks 3)</label>
                                <label class="w-full px-3 py-2 rounded-xl bg-white border border-[#EADBC8] text-xs text-slate-600 hover:border-[#B88A44] transition cursor-pointer flex items-center justify-between shadow-sm">
                                    <span class="text-[11px]" x-text="photoCount > 0 ? photoCount + ' foto dipilih' : 'Pilih hingga 3 foto (JPG, PNG, WEBP)'"></span>
                                    <span class="px-2 py-0.5 rounded bg-[#F6F1E8] text-[#102A43] text-[10px] font-bold">Upload</span>
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

                            <button type="submit"
                                    class="w-full py-2.5 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white text-xs font-bold transition shadow-sm">
                                Kirim Ulasan & Foto ⭐
                            </button>
                        </form>
                    </div>
                @endif

            @elseif($isExpired)
                {{-- STATUS KEDALUWARSA --}}
                <div class="w-full bg-red-50 rounded-2xl border-2 border-red-200 p-6 mb-5 text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-red-500 text-white flex items-center justify-center text-3xl font-bold mb-3 shadow-md">
                        ✕
                    </div>
                    <h3 class="font-bold text-base text-red-950">Tiket Kedaluwarsa</h3>
                    <p class="text-xs text-red-700 mt-1">
                        Jadwal kunjungan telah berakhir pada {{ $visitDate->translatedFormat('d F Y') }}
                    </p>
                    <div class="mt-3">
                        <span class="inline-block px-3 py-1 rounded-full bg-red-200 text-red-900 text-[10px] font-bold tracking-wider uppercase">
                            Tidak Dapat Digunakan
                        </span>
                    </div>
                </div>

                <a href="{{ route('museum.detail', $transaction->booking->museum_id) }}"
                   class="w-full inline-block py-2.5 rounded-xl bg-[#102A43] hover:bg-[#0c2238] text-white font-bold text-xs transition shadow-sm">
                    Pesan Tiket Baru
                </a>

            @else
                {{-- STATUS AKTIF: SCAN QR CODE --}}
                <div class="w-60 h-60 mx-auto bg-[#F9F7F2] rounded-2xl border border-[#EADBC8] flex items-center justify-center p-3 mb-4 shadow-inner">
                    {!! QrCode::size(210)->generate($transaction->invoice_code) !!}
                </div>

                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">
                    Kode Invoice Tiket
                </p>

                <h2 class="text-base sm:text-lg font-bold font-mono text-[#102A43]">
                    {{ $transaction->invoice_code }}
                </h2>

                <div class="mt-2 inline-flex px-3 py-1 rounded-xl bg-[#F6F1E8] border border-[#EADBC8] text-[#B88A44] font-bold text-xs">
                    {{ $transaction->booking->museum->name }}
                </div>

                @if ($transaction->booking->is_rombongan)
                    <div class="mt-1.5 block">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 font-semibold text-[11px]">
                            Rombongan · {{ $transaction->booking->jumlah_anggota }} orang
                        </span>
                    </div>
                @endif

                <div class="mt-5 space-y-2">
                    <a href="{{ route('user.ticket.download', $transaction->id) }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-[#102A43] hover:bg-[#0c2238] text-white px-4 py-2.5 rounded-xl shadow transition font-bold text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4"/>
                        </svg>
                        Unduh Tiket Digital (Gambar)
                    </a>

                    <p class="text-[10px] text-slate-400 leading-relaxed">
                        💡 Simpan gambar tiket di galeri HP untuk kemudahan scan tanpa koneksi internet.
                    </p>
                </div>
            @endif

        </div>

        {{-- RIGHT COLUMN: DETAIL KUNJUNGAN & MANIFEST --}}
        <div class="space-y-6">

            <div class="bg-white rounded-3xl border border-[#EADBC8] p-6 shadow-sm">
                <h2 class="font-display text-base font-bold text-[#102A43] mb-4">
                    Informasi Tiket & Kunjungan
                </h2>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Nama Pemesan</span>
                        <span class="font-bold text-[#102A43]">{{ $transaction->booking->user->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Museum Tujuan</span>
                        <span class="font-bold text-[#102A43]">{{ $transaction->booking->museum->name }}</span>
                    </div>

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Tanggal Kunjungan</span>
                        <span class="font-bold text-[#102A43]">
                            {{ $transaction->booking->visit_date ? \Carbon\Carbon::parse($transaction->booking->visit_date)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>

                    <div class="py-2 border-b border-slate-100 space-y-1.5">
                        <p class="text-[10px] uppercase font-bold text-[#B88A44]">Rincian Varian Tiket</p>
                        <div class="bg-[#F9F7F2] p-3 rounded-xl border border-[#EADBC8] space-y-1">
                            @foreach($transaction->booking->ticket_items ?? [] as $item)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-slate-800">{{ $item['ticket_name'] ?? 'Tiket' }} x{{ $item['qty'] ?? 1 }}</span>
                                    <span class="font-bold text-[#102A43]">
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
                        <div class="py-2 border-b border-slate-100 space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] uppercase font-bold text-[#B88A44]">Manifes Peserta Rombongan</span>
                                <span class="text-[10px] font-bold text-slate-500">{{ count($transaction->booking->manifest) }} orang</span>
                            </div>
                            <div class="bg-[#F9F7F2] p-3 rounded-xl border border-[#EADBC8] max-h-44 overflow-y-auto">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                    @foreach ($transaction->booking->manifest as $index => $nama)
                                        <div class="flex items-center gap-1.5 text-slate-700">
                                            <span class="text-[10px] font-bold text-[#B88A44] w-4 shrink-0">{{ $index + 1 }}.</span>
                                            <span class="truncate">{{ $nama }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 text-slate-600">
                        <span>Total Pembayaran</span>
                        <span class="font-extrabold text-[#B88A44] text-sm">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-1.5">
                        <span class="text-slate-600">Status Gate Tiket</span>
                        @if($isUsed)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                ✓ Sudah Digunakan
                            </span>
                        @elseif($isExpired)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                ✕ Kedaluwarsa
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                ● Siap Discan di Gate
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('user.profile') }}"
                   class="py-2.5 rounded-xl border border-[#B88A44] text-[#B88A44] font-bold text-xs text-center hover:bg-[#F6F1E8] transition">
                    ← Riwayat Tiket
                </a>

                <a href="{{ route('user.home') }}"
                   class="py-2.5 rounded-xl bg-[#102A43] text-white font-bold text-xs text-center hover:bg-[#0c2238] transition shadow">
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>

</section>

@endsection
