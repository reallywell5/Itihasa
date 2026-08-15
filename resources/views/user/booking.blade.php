@extends('layouts.user')

@section('title', 'Booking Ticket')

@section('content')

<form action="{{ route('user.booking.store', $museum->id) }}" method="POST">
@csrf

<section class="max-w-7xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('museum.detail', $museum->id) }}"
           class="inline-flex items-center gap-1.5 text-xs text-[#102A43] font-semibold hover:text-[#B88A44] transition">
            ← Kembali ke Museum
        </a>

        <h1 class="text-xl sm:text-2xl font-bold text-[#102A43] tracking-wide">
            Pemesanan Tiket
        </h1>

        <div class="w-12"></div>
    </div>

    {{-- STEPPER --}}
    <div class="flex justify-center mb-8 sm:mb-12">
        <div class="flex items-center gap-2 sm:gap-4 md:gap-6 w-full max-w-xl px-2">

            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow">
                    1
                </div>
                <span class="mt-1.5 text-[10px] sm:text-xs font-bold text-[#102A43] text-center">
                    Jadwal
                </span>
            </div>

            <div class="flex-1 h-[2px] bg-[#D9CBB8]"></div>

            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-[#B88A44] text-[#B88A44] flex items-center justify-center font-bold text-xs sm:text-sm bg-white">
                    2
                </div>
                <span class="mt-1.5 text-[10px] sm:text-xs font-semibold text-slate-400 text-center">
                    Bayar
                </span>
            </div>

            <div class="flex-1 h-[2px] bg-[#D9CBB8]"></div>

            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-[#B88A44] text-[#B88A44] flex items-center justify-center font-bold text-xs sm:text-sm bg-white">
                    3
                </div>
                <span class="mt-1.5 text-[10px] sm:text-xs font-semibold text-slate-400 text-center">
                    Tiket QR
                </span>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-6 lg:gap-8 items-start">

        {{-- LEFT --}}
        <div class="space-y-6">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="p-3.5 bg-red-100 text-red-700 rounded-2xl text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="p-3.5 bg-red-100 text-red-700 rounded-2xl text-xs">
                    {{ session('error') }}
                </div>
            @endif

            {{-- DATE --}}
            <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-lg font-bold text-[#102A43] mb-3">
                    Pilih Tanggal Kunjungan
                </h2>

                <input type="date"
                       name="visit_date"
                       id="visit-date"
                       required
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#B88A44]">

                <p id="quota-loading" class="text-xs text-slate-400 mt-2 hidden">
                    Mengecek sisa kuota tiket...
                </p>
            </div>

            {{-- DATA WAJIB PENGUNJUNG --}}
            <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm space-y-4">
                <h2 class="text-base sm:text-lg font-bold text-[#102A43]">
                    Data Kunjungan
                </h2>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">
                        Nama Lengkap Penanggung Jawab <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama_penanggung_jawab"
                           value="{{ old('nama_penanggung_jawab', auth()->user()->name) }}"
                           required
                           placeholder="Contoh: Asep Supriatna"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CBB8] text-xs focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">
                            Jumlah Orang (Pax) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="jumlah_anggota"
                               id="jumlah_anggota"
                               value="{{ old('jumlah_anggota', 1) }}"
                               min="1"
                               required
                               placeholder="Total rombongan"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CBB8] text-xs focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
                        <p class="text-[10px] text-slate-400 mt-1">
                            Wajib sama dengan total tiket.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">
                            Kota Asal <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="kota_asal"
                               value="{{ old('kota_asal') }}"
                               required
                               placeholder="Contoh: Bandung"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CBB8] text-xs focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">
                        Nomor WhatsApp / HP <span class="text-red-500">*</span>
                    </label>
                    <input type="tel"
                           name="no_hp"
                           value="{{ old('no_hp') }}"
                           required
                           placeholder="08xxxxxxxxxx"
                           pattern="[0-9+]{9,15}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CBB8] text-xs focus:outline-none focus:ring-2 focus:ring-[#B88A44]">
                </div>
            </div>

            {{-- MANIFES ROMBONGAN (muncul otomatis kalau jumlah anggota >= 10) --}}
            <div id="manifest-section" class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm hidden">

                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-3xl font-bold text-[#102A43]">
            {{-- MANIFES ROMBONGAN (muncul otomatis kalau jumlah anggota >= 6) --}}
            <div id="manifest-section" class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm hidden">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-base sm:text-lg font-bold text-[#102A43]">
                        Manifes Rombongan
                    </h2>
                    <span id="manifest-count" class="text-xs font-semibold text-[#B88A44]"></span>
                </div>

                <p class="text-slate-400 text-xs mb-4">
                    Pemesanan untuk 6 orang atau lebih wajib mengisi nama seluruh anggota rombongan.
                </p>

                <div id="manifest-inputs" class="space-y-2.5"></div>
            </div>

            {{-- EMAIL NOTIFIKASI --}}
            <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-lg font-bold text-[#102A43] mb-1">
                    Email Notifikasi E-Tiket
                </h2>
                <p class="text-slate-400 text-xs mb-3">
                    E-tiket dan QR code akan otomatis dikirimkan ke email akun Anda.
                </p>
                <div class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9CBB8] text-xs bg-[#F9F7F2] text-slate-600 font-mono">
                    {{ auth()->user()->email }}
                </div>
            </div>

            {{-- TICKET LIST --}}
            <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-lg font-bold text-[#102A43] mb-1">
                    Pilihan Kategori Tiket
                </h2>
                <p class="text-slate-400 text-xs mb-4">
                    Sesuaikan jumlah tiket dengan kuota yang tersedia pada tanggal kunjungan.
                </p>

                <div class="divide-y divide-slate-100">
                    @foreach($museum->tickets as $ticket)
                        <div class="flex items-center justify-between py-3.5" id="ticket-row-{{ $ticket->id }}">
                            <div>
                                <h3 class="font-bold text-sm text-slate-800">{{ $ticket->ticket_name }}</h3>
                                <p class="text-xs font-semibold text-slate-500">
                                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </p>
                                <p class="text-[11px] font-semibold text-[#B88A44] mt-0.5"
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
                                    class="w-8 h-8 rounded-lg bg-[#F9F7F2] text-slate-700 font-bold text-base hover:bg-[#EADBC8] transition flex items-center justify-center">
                                    -
                                </button>

                                <span id="ticket-{{ $ticket->id }}-qty"
                                    class="text-sm font-bold w-6 text-center text-slate-800">
                                    0
                                </span>

                                <button type="button"
                                    id="ticket-{{ $ticket->id }}-plus"
                                    onclick="changeQty({{ $ticket->id }}, 1)"
                                    class="w-8 h-8 rounded-lg bg-[#F9F7F2] text-slate-700 font-bold text-base hover:bg-[#EADBC8] transition flex items-center justify-center">
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
            <div class="sticky top-28 bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-lg font-bold text-[#102A43] mb-4">
                    Ringkasan Pesanan
                </h2>

                <div class="space-y-3">
                    <div>
                        <h3 class="font-bold text-sm text-[#102A43]">
                            {{ $museum->name }}
                        </h3>
                        <p id="summary-date" class="text-xs text-slate-500 mt-0.5">
                            Tanggal: -
                        </p>
                    </div>

                    <div id="ticket-summary" class="border-t border-slate-100 pt-3 space-y-2 text-xs"></div>

                    <div class="border-t border-slate-100 pt-4 flex justify-between items-center">
                        <span class="font-bold text-sm text-[#102A43]">
                            Total Bayar
                        </span>
                        <span id="total-price" class="text-xl font-extrabold text-[#B88A44]">
                            Rp 0
                        </span>
                    </div>
                </div>

                <button type="submit"
                        class="mt-6 w-full py-3 rounded-xl bg-[#102A43] text-white font-bold text-xs hover:bg-[#0d2238] transition shadow">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </div>

    </div>

</section>

</form>

<script>
const quotaUrl = "{{ route('user.booking.quota', $museum->id) }}";
let remainingQuota = {}; // { ticketId: sisaKuota }

function changeQty(ticketId, change) {
    let input = document.getElementById('ticket-' + ticketId + '-input');
    let qtyText = document.getElementById('ticket-' + ticketId + '-qty');

    let current = parseInt(input.value);
    let updated = current + change;

    if (updated < 0) updated = 0;

    // Cegah menambah qty melebihi sisa kuota (kalau kuota sudah diketahui)
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
    let summaryHtml = '';

    @foreach($museum->tickets as $ticket)
        let qty{{ $ticket->id }} = parseInt(
            document.getElementById('ticket-{{ $ticket->id }}-input').value
        );

        if (qty{{ $ticket->id }} > 0) {
            let subtotal = qty{{ $ticket->id }} * {{ $ticket->price }};
            total += subtotal;

            summaryHtml += `
                <div class="flex justify-between items-center py-3 border-b border-[#EADBC8]">

                    <div>
                        <p class="font-semibold text-[#102A43] text-lg">
                            {{ $ticket->ticket_name }} x ${qty{{ $ticket->id }}}
                        </p>

                        <p class="text-sm text-slate-400">
                            Rp {{ number_format($ticket->price, 0, ',', '.') }} / tiket
                        </p>
                    </div>

                    <span class="font-bold text-[#102A43] text-lg">
                        Rp ${subtotal.toLocaleString('id-ID')}
                    </span>

                </div>
            `;
        }
    @endforeach

    document.getElementById('ticket-summary').innerHTML =
        summaryHtml || `
            <div class="py-6 text-center text-slate-400">
                Belum ada tiket dipilih
            </div>
        `;

    document.getElementById('total-price').innerText =
        'Rp ' + total.toLocaleString('id-ID');
}

async function fetchQuota(visitDate) {
    const loadingEl = document.getElementById('quota-loading');
    loadingEl.classList.remove('hidden');

    try {
        const res = await fetch(quotaUrl + '?visit_date=' + visitDate, {
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) throw new Error('Gagal mengambil data kuota');

        const data = await res.json();

        Object.keys(data).forEach(function (ticketId) {
            const info = data[ticketId];
            remainingQuota[ticketId] = info.sisa;

            const quotaLabel = document.getElementById('ticket-' + ticketId + '-quota');
            const plusBtn = document.getElementById('ticket-' + ticketId + '-plus');
            const qtyInput = document.getElementById('ticket-' + ticketId + '-input');
            const qtyText = document.getElementById('ticket-' + ticketId + '-qty');

            if (info.sisa <= 0) {
                quotaLabel.innerText = 'Kuota habis untuk tanggal ini';
                quotaLabel.classList.add('text-red-500');
                quotaLabel.classList.remove('text-[#B88A44]');
                plusBtn.disabled = true;
                plusBtn.classList.add('opacity-40', 'cursor-not-allowed');

                // Reset qty kalau ternyata sudah dipilih tapi kuota habis
                qtyInput.value = 0;
                qtyText.innerText = 0;
            } else {
                quotaLabel.innerText = 'Sisa kuota: ' + info.sisa + ' / ' + info.slot + ' pax';
                quotaLabel.classList.remove('text-red-500');
                quotaLabel.classList.add('text-[#B88A44]');
                plusBtn.disabled = false;
                plusBtn.classList.remove('opacity-40', 'cursor-not-allowed');

                // Kalau qty yang sudah dipilih ternyata melebihi sisa kuota terbaru, sesuaikan
                if (parseInt(qtyInput.value) > info.sisa) {
                    qtyInput.value = info.sisa;
                    qtyText.innerText = info.sisa;
                }
            }
        });

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
                   class="w-full px-5 py-3 rounded-xl border border-[#D9CBB8] text-base">
        `;
    }

    container.innerHTML = html;
}

document.getElementById('jumlah_anggota').addEventListener('input', function () {
    const count = parseInt(this.value) || 0;
    renderManifestInputs(count);
});

// Kalau halaman reload karena validasi gagal, tetap tampilkan manifes sesuai jumlah anggota lama
const initialJumlah = parseInt(document.getElementById('jumlah_anggota').value) || 0;
if (initialJumlah >= 10) {
    renderManifestInputs(initialJumlah);
}

updateTotal();
</script>

@endsection
