@extends('layouts.user')

@section('title', 'Pemesanan Tiket')

@section('content')

<form action="{{ route('user.booking.store', $museum->id) }}" method="POST">
@csrf

<section class="py-4 sm:py-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-6 mb-8 border-b border-neutral-200">
        <div>
            <a href="{{ route('museum.detail', $museum->id) }}"
               class="inline-flex items-center gap-1.5 text-xs text-[#5A5A5A] font-semibold uppercase tracking-wider hover:text-[#1A1A1A] transition duration-200 mb-3">
                <span>←</span>
                <span>Kembali ke Detail Museum</span>
            </a>
            <h1 class="font-serif text-3xl sm:text-4xl font-normal text-[#1A1A1A] tracking-[-0.015em]">
                Pemesanan Tiket <span class="italic">Kunjungan</span>
            </h1>
        </div>

        <p class="text-xs font-medium text-[#B8860B] uppercase tracking-[0.2em]">
            Langkah 1 dari 3
        </p>
    </div>

    {{-- INSTITUTIONAL STEPPER --}}
    <div class="flex justify-center mb-10">
        <div class="flex items-center gap-3 sm:gap-5 w-full max-w-xl">
            <div class="flex flex-col items-center flex-1">
                <div class="w-9 h-9 rounded-sm bg-[#1A1A1A] text-white flex items-center justify-center font-semibold text-xs">
                    1
                </div>
                <span class="mt-2 text-[11px] font-semibold uppercase tracking-wider text-[#1A1A1A] text-center">
                    Jadwal &amp; Tiket
                </span>
            </div>

            <div class="flex-1 h-[1px] bg-neutral-300"></div>

            <div class="flex flex-col items-center flex-1">
                <div class="w-9 h-9 rounded-sm border border-neutral-300 text-[#5A5A5A] flex items-center justify-center font-semibold text-xs bg-[#F8F7F4]">
                    2
                </div>
                <span class="mt-2 text-[11px] font-medium uppercase tracking-wider text-[#5A5A5A] text-center">
                    Pembayaran
                </span>
            </div>

            <div class="flex-1 h-[1px] bg-neutral-300"></div>

            <div class="flex flex-col items-center flex-1">
                <div class="w-9 h-9 rounded-sm border border-neutral-300 text-[#5A5A5A] flex items-center justify-center font-semibold text-xs bg-[#F8F7F4]">
                    3
                </div>
                <span class="mt-2 text-[11px] font-medium uppercase tracking-wider text-[#5A5A5A] text-center">
                    E-Tiket QR
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-8 items-start">

        {{-- LEFT COLUMN --}}
        <div class="space-y-6">

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="p-4 bg-rose-50 text-[#8C3B2B] border border-rose-200 rounded-sm text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-rose-50 text-[#8C3B2B] border border-rose-200 rounded-sm text-xs">
                    {{ session('error') }}
                </div>
            @endif

            {{-- VISIT DATE --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6">
                <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">01. Jadwal</p>
                <h2 class="font-serif text-xl font-semibold text-[#1A1A1A] mb-4">
                    Pilih Tanggal Kunjungan
                </h2>

                <input type="date"
                       name="visit_date"
                       id="visit-date"
                       required
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">

                <p id="quota-loading" class="text-xs text-[#5A5A5A] mt-2 hidden">
                    Memeriksa ketersediaan kuota tiket...
                </p>
            </div>

            {{-- VISITOR DETAILS --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6 space-y-4">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">02. Identitas Pemesan</p>
                    <h2 class="font-serif text-xl font-semibold text-[#1A1A1A]">
                        Data Kunjungan
                    </h2>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                        Nama Lengkap Penanggung Jawab <span class="text-[#8C3B2B]">*</span>
                    </label>
                    <input type="text"
                           name="nama_penanggung_jawab"
                           id="nama_penanggung_jawab"
                           value="{{ old('nama_penanggung_jawab', auth()->user()->name) }}"
                           required
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                            Jumlah Orang (Pax) <span class="text-[#8C3B2B]">*</span>
                        </label>
                        <input type="number"
                               name="jumlah_anggota"
                               id="jumlah_anggota"
                               value="{{ old('jumlah_anggota', 1) }}"
                               min="1"
                               required
                               placeholder="Total pengunjung"
                               class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
                        <p class="text-[11px] text-[#5A5A5A] mt-1">
                            Harus sesuai dengan jumlah total tiket.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                            Kota Asal <span class="text-[#8C3B2B]">*</span>
                        </label>
                        <input type="text"
                               name="kota_asal"
                               value="{{ old('kota_asal') }}"
                               required
                               placeholder="Contoh: Bandung"
                               class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                        Nomor WhatsApp / HP <span class="text-[#8C3B2B]">*</span>
                    </label>
                    <input type="tel"
                           name="no_hp"
                           value="{{ old('no_hp') }}"
                           required
                           placeholder="08xxxxxxxxxx"
                           pattern="[0-9+]{9,15}"
                           class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
                </div>
            </div>

            {{-- MANIFEST GROUP SECTION (AUTO SHOWN WHEN >= 6) --}}
            <div id="manifest-section" class="bg-white rounded-sm border border-neutral-200 p-6 hidden">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="font-serif text-xl font-semibold text-[#1A1A1A]">
                        Manifes Rombongan
                    </h2>
                    <span id="manifest-count" class="text-xs font-semibold text-[#B8860B]"></span>
                </div>

                <p class="text-[#5A5A5A] text-xs mb-4">
                    Pemesanan untuk 6 orang atau lebih wajib mencantumkan nama seluruh anggota rombongan.
                </p>

                <div id="manifest-inputs" class="space-y-2.5"></div>
            </div>

            {{-- NOTIFICATION EMAIL --}}
            <div class="bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6">
                <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-1">
                    Pengiriman E-Tiket Digital
                </h2>
                <p class="text-[#5A5A5A] text-xs mb-3">
                    E-Tiket dan kode QR terenkripsi akan dikirimkan serta disimpan ke akun email Anda:
                </p>
                <div class="w-full px-3.5 py-2.5 rounded-sm border border-neutral-200 text-xs bg-white text-[#1A1A1A] font-medium">
                    {{ auth()->user()->email }}
                </div>
            </div>

            {{-- TICKET LIST --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6">
                <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">03. Pilihan Tiket</p>
                <h2 class="font-serif text-xl font-semibold text-[#1A1A1A] mb-1">
                    Kategori Tiket Masuk
                </h2>
                <p class="text-[#5A5A5A] text-xs mb-5">
                    Sesuaikan jumlah tiket dengan kuota yang tersedia pada tanggal kunjungan.
                </p>

                <div class="divide-y divide-neutral-200">
                    @foreach($museum->tickets as $ticket)
                        <div class="flex items-center justify-between py-4" id="ticket-row-{{ $ticket->id }}">
                            <div>
                                <h3 class="font-serif font-semibold text-base text-[#1A1A1A]">{{ $ticket->ticket_name }}</h3>
                                <p class="text-xs font-semibold text-[#5A5A5A] mt-0.5">
                                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </p>
                                <p class="text-[11px] font-medium text-[#B8860B] mt-1"
                                   id="ticket-{{ $ticket->id }}-quota">
                                    Kuota: {{ $ticket->slot }} pax/hari
                                </p>
                            </div>

                            <input type="hidden"
                                   name="ticket_{{ $ticket->id }}"
                                   id="ticket-{{ $ticket->id }}-input"
                                   value="0"
                                   data-slot="{{ $ticket->slot }}">

                            <div class="flex items-center gap-2">
                                <button type="button"
                                        onclick="changeQty({{ $ticket->id }}, -1)"
                                        class="w-8 h-8 rounded-sm border border-neutral-300 bg-[#F8F7F4] text-[#1A1A1A] font-semibold text-sm hover:border-[#1A1A1A] transition flex items-center justify-center">
                                    −
                                </button>

                                <span id="ticket-{{ $ticket->id }}-qty"
                                      class="text-sm font-semibold w-7 text-center text-[#1A1A1A]">
                                    0
                                </span>

                                <button type="button"
                                        id="ticket-{{ $ticket->id }}-plus"
                                        onclick="changeQty({{ $ticket->id }}, 1)"
                                        class="w-8 h-8 rounded-sm border border-neutral-300 bg-[#F8F7F4] text-[#1A1A1A] font-semibold text-sm hover:border-[#1A1A1A] transition flex items-center justify-center">
                                    +
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- RIGHT SIDEBAR SUMMARY --}}
        <div>
            <div class="sticky top-28 bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6">
                <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">Ringkasan</p>
                <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A] mb-5 pb-4 border-b border-neutral-200">
                    Detail Pesanan
                </h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="font-serif font-semibold text-lg text-[#1A1A1A]">
                            {{ $museum->name }}
                        </h3>
                        <p id="summary-date" class="text-xs text-[#5A5A5A] mt-1">
                            Tanggal: -
                        </p>
                    </div>

                    <div id="ticket-summary" class="border-t border-neutral-200 pt-3 space-y-2 text-xs"></div>

                    {{-- KODE PROMO --}}
                    <div class="border-t border-neutral-200 pt-4">
                        <label class="text-[11px] font-semibold text-[#1A1A1A] mb-2 flex items-center justify-between">
                            <span>Kupon Promo / Diskon</span>
                            <span id="promo-applied-badge" class="hidden text-[10px] text-emerald-700 font-semibold bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-sm">
                                ✓ Kupon Aktif
                            </span>
                        </label>
                        <div class="flex gap-1.5">
                            <input type="text"
                                   name="promo_code"
                                   id="promo-code-input"
                                   placeholder="KODE PROMO"
                                   class="flex-1 uppercase font-semibold px-3 py-2 rounded-sm bg-white border border-neutral-200 text-xs focus:outline-none focus:border-[#B8860B]">
                            <button type="button"
                                    id="btn-apply-promo"
                                    onclick="applyPromo()"
                                    class="px-3.5 py-2 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                                Pakai
                            </button>
                        </div>
                        <p id="promo-msg" class="text-[11px] mt-1.5 hidden"></p>
                    </div>

                    {{-- RINCIAN HARGA --}}
                    <div class="border-t border-neutral-200 pt-3 space-y-2 text-xs text-[#5A5A5A]">
                        <div class="flex justify-between">
                            <span>Subtotal Tiket</span>
                            <span id="summary-subtotal" class="font-semibold text-[#1A1A1A]">Rp 0</span>
                        </div>
                        <div id="discount-row" class="flex justify-between text-emerald-700 font-semibold hidden">
                            <span id="discount-label">Diskon Promo</span>
                            <span id="discount-amount">- Rp 0</span>
                        </div>
                    </div>

                    <div class="border-t border-neutral-200 pt-4 flex justify-between items-baseline">
                        <span class="font-semibold text-sm text-[#1A1A1A]">
                            Total Bayar
                        </span>
                        <span id="total-price" class="font-sans text-2xl font-semibold text-[#B8860B]">
                            Rp 0
                        </span>
                    </div>
                </div>

                {{-- BLACKOUT NOTICE --}}
                <div id="blackout-notice" class="hidden mt-4 p-3.5 bg-rose-50 border border-rose-200 rounded-sm text-[#8C3B2B] text-xs font-medium">
                    Museum tutup pada tanggal yang dipilih. Silakan pilih tanggal lain.
                </div>

                <button type="submit"
                        id="btn-submit-booking"
                        class="mt-6 w-full py-3.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300 disabled:opacity-40 disabled:cursor-not-allowed">
                    Lanjut ke Pembayaran →
                </button>
            </div>
        </div>

    </div>

</section>

</form>

<script>
const quotaUrl = "{{ route('user.booking.quota', $museum->id) }}";
const checkPromoUrl = "{{ route('user.booking.check-promo', $museum->id) }}";
const csrfToken = "{{ csrf_token() }}";

let remainingQuota = {};
let currentSubtotal = 0;
let appliedDiscount = 0;
let appliedPromoCode = '';

function changeQty(ticketId, change) {
    let input = document.getElementById('ticket-' + ticketId + '-input');
    let qtyText = document.getElementById('ticket-' + ticketId + '-qty');

    let current = parseInt(input.value);
    let updated = current + change;

    if (updated < 0) updated = 0;

    if (change > 0 && remainingQuota[ticketId] !== undefined && updated > remainingQuota[ticketId]) {
        alert('Sisa kuota tiket ini cuma ' + remainingQuota[ticketId] + ' untuk tanggal yang dipilih.');
        return;
    }

    input.value = updated;
    qtyText.innerText = updated;

    updateTotal();
}

function updateTotal() {
    let total = 0;
    let totalQty = 0;
    let summaryHtml = '';

    @foreach($museum->tickets as $ticket)
        let qty{{ $ticket->id }} = parseInt(
            document.getElementById('ticket-{{ $ticket->id }}-input').value
        );

        if (qty{{ $ticket->id }} > 0) {
            totalQty += qty{{ $ticket->id }};
            let subtotal = qty{{ $ticket->id }} * {{ $ticket->price }};
            total += subtotal;

            summaryHtml += `
                <div class="flex justify-between items-center py-2 border-b border-neutral-200/70">
                    <div>
                        <p class="font-semibold text-[#1A1A1A]">
                            {{ $ticket->ticket_name }} x ${qty{{ $ticket->id }}}
                        </p>
                        <p class="text-[11px] text-[#5A5A5A]">
                            Rp {{ number_format($ticket->price, 0, ',', '.') }} / tiket
                        </p>
                    </div>
                    <span class="font-semibold text-[#1A1A1A]">
                        Rp ${subtotal.toLocaleString('id-ID')}
                    </span>
                </div>
            `;
        }
    @endforeach

    currentSubtotal = total;

    if (totalQty > 0) {
        document.getElementById('jumlah_anggota').value = totalQty;
    }

    document.getElementById('ticket-summary').innerHTML =
        summaryHtml || `
            <div class="py-3 text-center text-[#5A5A5A]">
                Belum ada tiket dipilih
            </div>
        `;

    document.getElementById('summary-subtotal').innerText =
        'Rp ' + currentSubtotal.toLocaleString('id-ID');

    if (appliedDiscount > 0 && appliedPromoCode) {
        recalculatePromo();
    } else {
        renderFinalPrice();
    }
}

function renderFinalPrice() {
    let finalPrice = Math.max(0, currentSubtotal - appliedDiscount);
    document.getElementById('total-price').innerText =
        'Rp ' + finalPrice.toLocaleString('id-ID');

    let discountRow = document.getElementById('discount-row');
    let discountAmount = document.getElementById('discount-amount');
    let promoBadge = document.getElementById('promo-applied-badge');

    if (appliedDiscount > 0) {
        discountRow.classList.remove('hidden');
        discountAmount.innerText = '- Rp ' + appliedDiscount.toLocaleString('id-ID');
        promoBadge.classList.remove('hidden');
    } else {
        discountRow.classList.add('hidden');
        promoBadge.classList.add('hidden');
    }
}

async function applyPromo() {
    const promoInput = document.getElementById('promo-code-input');
    const promoCode = promoInput.value.trim().toUpperCase();
    const promoMsg = document.getElementById('promo-msg');
    const visitDate = document.getElementById('visit-date').value;

    if (!promoCode) {
        promoMsg.className = 'text-[11px] mt-1.5 text-amber-700 block';
        promoMsg.innerText = 'Masukkan kode promo terlebih dahulu.';
        return;
    }

    if (currentSubtotal <= 0) {
        promoMsg.className = 'text-[11px] mt-1.5 text-amber-700 block';
        promoMsg.innerText = 'Pilih minimal 1 tiket sebelum menerapkan kupon.';
        return;
    }

    promoMsg.className = 'text-[11px] mt-1.5 text-[#5A5A5A] block';
    promoMsg.innerText = 'Memeriksa kupon promo...';

    try {
        const res = await fetch(checkPromoUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                promo_code: promoCode,
                total_price: currentSubtotal,
                visit_date: visitDate,
            }),
        });

        const data = await res.json();

        if (res.ok && data.valid) {
            appliedDiscount = data.discount;
            appliedPromoCode = data.code;
            promoMsg.className = 'text-[11px] mt-1.5 text-emerald-700 font-semibold block';
            promoMsg.innerText = '✓ ' + data.message + ' (Hemat Rp ' + data.discount.toLocaleString('id-ID') + ')';
            renderFinalPrice();
        } else {
            appliedDiscount = 0;
            appliedPromoCode = '';
            promoMsg.className = 'text-[11px] mt-1.5 text-[#8C3B2B] block';
            promoMsg.innerText = data.message || 'Kupon tidak dapat digunakan.';
            renderFinalPrice();
        }
    } catch (err) {
        console.error(err);
        promoMsg.className = 'text-[11px] mt-1.5 text-[#8C3B2B] block';
        promoMsg.innerText = 'Terjadi kesalahan saat memeriksa kode promo.';
    }
}

async function recalculatePromo() {
    if (!appliedPromoCode) return;
    const visitDate = document.getElementById('visit-date').value;

    try {
        const res = await fetch(checkPromoUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                promo_code: appliedPromoCode,
                total_price: currentSubtotal,
                visit_date: visitDate,
            }),
        });

        const data = await res.json();
        if (res.ok && data.valid) {
            appliedDiscount = data.discount;
        } else {
            appliedDiscount = 0;
        }
        renderFinalPrice();
    } catch (err) {
        renderFinalPrice();
    }
}

async function fetchQuota(visitDate) {
    const loadingEl = document.getElementById('quota-loading');
    const blackoutNotice = document.getElementById('blackout-notice');
    const submitBtn = document.getElementById('btn-submit-booking');

    loadingEl.classList.remove('hidden');

    try {
        const res = await fetch(quotaUrl + '?visit_date=' + visitDate, {
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) throw new Error('Gagal mengambil data kuota');

        const data = await res.json();
        let isMuseumClosed = false;
        let closedNotes = '';

        Object.keys(data).forEach(function (ticketId) {
            const info = data[ticketId];
            remainingQuota[ticketId] = info.sisa;

            if (info.is_closed) {
                isMuseumClosed = true;
                if (info.notes) closedNotes = info.notes;
            }

            const quotaLabel = document.getElementById('ticket-' + ticketId + '-quota');
            const plusBtn = document.getElementById('ticket-' + ticketId + '-plus');
            const qtyInput = document.getElementById('ticket-' + ticketId + '-input');
            const qtyText = document.getElementById('ticket-' + ticketId + '-qty');

            if (info.sisa <= 0 || info.is_closed) {
                quotaLabel.innerText = info.is_closed ? 'Tutup pada tanggal ini' : 'Kuota habis untuk tanggal ini';
                quotaLabel.classList.add('text-[#8C3B2B]');
                quotaLabel.classList.remove('text-[#B8860B]');
                plusBtn.disabled = true;
                plusBtn.classList.add('opacity-40', 'cursor-not-allowed');

                qtyInput.value = 0;
                qtyText.innerText = 0;
            } else {
                quotaLabel.innerText = 'Sisa kuota: ' + info.sisa + ' / ' + info.slot + ' pax';
                quotaLabel.classList.remove('text-[#8C3B2B]');
                quotaLabel.classList.add('text-[#B8860B]');
                plusBtn.disabled = false;
                plusBtn.classList.remove('opacity-40', 'cursor-not-allowed');

                if (parseInt(qtyInput.value) > info.sisa) {
                    qtyInput.value = info.sisa;
                    qtyText.innerText = info.sisa;
                }
            }
        });

        if (isMuseumClosed) {
            blackoutNotice.innerHTML = 'Museum tutup pada tanggal yang dipilih.' + (closedNotes ? ' (' + closedNotes + ')' : '') + ' Silakan pilih tanggal kunjungan lain.';
            blackoutNotice.classList.remove('hidden');
            submitBtn.disabled = true;
        } else {
            blackoutNotice.classList.add('hidden');
            submitBtn.disabled = false;
        }

        updateTotal();
    } catch (err) {
        console.error(err);
    } finally {
        loadingEl.classList.add('hidden');
    }
}

document.getElementById('visit-date').addEventListener('change', function () {
    document.getElementById('summary-date').innerText =
        'Tanggal: ' + this.value;

    if (this.value) {
        fetchQuota(this.value);
    }
});

let manifestValues = @json(old('manifest', []));

function renderManifestInputs(count) {
    const section = document.getElementById('manifest-section');
    const container = document.getElementById('manifest-inputs');
    const countLabel = document.getElementById('manifest-count');

    if (count < 6) {
        section.classList.add('hidden');
        container.innerHTML = '';
        return;
    }

    section.classList.remove('hidden');
    countLabel.innerText = count + ' nama dibutuhkan';

    let html = '';
    for (let i = 0; i < count; i++) {
        const savedValue = manifestValues[i] || '';
        html += `
            <input type="text"
                   name="manifest[]"
                   value="${savedValue}"
                   required
                   maxlength="255"
                   placeholder="Nama anggota ke-${i + 1}"
                   oninput="manifestValues[${i}] = this.value"
                   class="w-full px-3.5 py-2.5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-xs sm:text-sm text-[#1A1A1A] focus:bg-white focus:outline-none focus:border-[#B8860B] transition">
        `;
    }

    container.innerHTML = html;
}

document.getElementById('jumlah_anggota').addEventListener('input', function () {
    const count = parseInt(this.value) || 0;
    renderManifestInputs(count);
});

const initialJumlah = parseInt(document.getElementById('jumlah_anggota').value) || 0;
if (initialJumlah >= 6) {
    renderManifestInputs(initialJumlah);
}

updateTotal();
</script>

@endsection
