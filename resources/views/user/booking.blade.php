@extends('layouts.user')

@section('title', 'Booking Ticket')

@section('content')

<form action="{{ route('user.booking.store', $museum->id) }}" method="POST">
@csrf

<section class="max-w-7xl mx-auto px-6 lg:px-8 py-14">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-12">

        <a href="{{ route('user.home', $museum->id) }}"
           class="flex items-center gap-2 text-[#102A43] font-medium hover:text-[#B88A44] transition">
            ← Kembali
        </a>

        <h1 class="text-4xl font-bold text-[#102A43] tracking-wide">
            Itihasa
        </h1>

        <div></div>

    </div>

    {{-- STEP --}}
    <div class="flex justify-center mb-14">
        <div class="flex items-center gap-10">

            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold text-lg">
                    1
                </div>
                <span class="mt-3 text-sm font-semibold text-[#102A43]">
                    PILIH JADWAL
                </span>
            </div>

            <div class="w-32 h-[2px] bg-[#D9CBB8]"></div>

            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full border-2 border-[#B88A44] text-[#B88A44] flex items-center justify-center font-bold text-lg">
                    2
                </div>
                <span class="mt-3 text-sm font-semibold text-slate-500">
                    PEMBAYARAN
                </span>
            </div>

            <div class="w-32 h-[2px] bg-[#D9CBB8]"></div>

            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full border-2 border-[#B88A44] text-[#B88A44] flex items-center justify-center font-bold text-lg">
                    3
                </div>
                <span class="mt-3 text-sm font-semibold text-slate-500">
                    DETAIL TRANSAKSI
                </span>
            </div>

        </div>
    </div>

    <div class="grid lg:grid-cols-[1fr_420px] gap-12 items-start">

        {{-- LEFT --}}
        <div class="space-y-8">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="p-4 bg-red-100 text-red-600 rounded-2xl">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-600 rounded-2xl">
                    {{ session('error') }}
                </div>
            @endif

            {{-- DATE --}}
            <div class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-3xl font-bold text-[#102A43] mb-6">
                    Pilih Tanggal
                </h2>

                <input type="date"
                       name="visit_date"
                       id="visit-date"
                       required
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-6 py-5 rounded-2xl border border-[#D9CBB8] text-lg">

                <p id="quota-loading" class="text-sm text-slate-400 mt-3 hidden">
                    Mengecek sisa kuota...
                </p>

            </div>

            {{-- DATA WAJIB PENGUNJUNG --}}
            <div class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm space-y-6">

                <h2 class="text-3xl font-bold text-[#102A43]">
                    Data Kunjungan
                </h2>

                <div>
                    <label class="block font-semibold text-[#102A43] mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text"
                           name="nama_penanggung_jawab"
                           value="{{ old('nama_penanggung_jawab') }}"
                           required
                           placeholder="Contoh: Asep Supriatna"
                           class="w-full px-6 py-4 rounded-2xl border border-[#D9CBB8] text-lg">
                </div>

                <div>
                    <label class="block font-semibold text-[#102A43] mb-2">
                        Jumlah Anggota
                    </label>
                    <input type="number"
                           name="jumlah_anggota"
                           id="jumlah_anggota"
                           value="{{ old('jumlah_anggota') }}"
                           min="1"
                           required
                           placeholder="Total orang dalam rombongan"
                           class="w-full px-6 py-4 rounded-2xl border border-[#D9CBB8] text-lg">
                    <p class="text-sm text-slate-400 mt-2">
                        Wajib sama dengan total tiket yang dipilih di bawah.
                    </p>
                </div>

                <div>
                    <label class="block font-semibold text-[#102A43] mb-2">
                        Kota Asal
                    </label>
                    <input type="text"
                           name="kota_asal"
                           value="{{ old('kota_asal') }}"
                           required
                           placeholder="Contoh: Bandung"
                           class="w-full px-6 py-4 rounded-2xl border border-[#D9CBB8] text-lg">
                </div>

                <div>
                    <label class="block font-semibold text-[#102A43] mb-2">
                        Nomor HP
                    </label>
                    <input type="tel"
                           name="no_hp"
                           value="{{ old('no_hp') }}"
                           required
                           placeholder="08xxxxxxxxxx"
                           pattern="[0-9+]{9,15}"
                           class="w-full px-6 py-4 rounded-2xl border border-[#D9CBB8] text-lg">
                </div>

            </div>

            {{-- EMAIL (READ-ONLY, info saja) --}}
            <div class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-2xl font-bold text-[#102A43] mb-2">
                    Email Notifikasi
                </h2>

                <p class="text-slate-400 text-sm mb-5">
                    E-tiket dan notifikasi status pemesanan akan dikirim ke email akun kamu.
                </p>

                <div class="w-full px-6 py-5 rounded-2xl border border-[#D9CBB8] text-lg bg-[#F9F7F2] text-slate-600">
                    {{ auth()->user()->email }}
                </div>

            </div>

            {{-- TICKET --}}
            <div class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-3xl font-bold text-[#102A43] mb-8">
                    Pilih Tiket
                </h2>

                <p class="text-slate-400 text-sm -mt-4 mb-6">
                    Pilih tanggal kunjungan terlebih dahulu untuk melihat sisa kuota tiap kategori.
                </p>

                @foreach($museum->tickets as $ticket)

                <div class="flex justify-between items-center py-6 border-b" id="ticket-row-{{ $ticket->id }}">

                    <div>
                        <h3 class="font-bold text-xl">{{ $ticket->ticket_name }}</h3>
                        <p class="text-slate-500">
                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                        </p>
                        <p class="text-sm font-semibold text-[#B88A44] mt-1"
                           id="ticket-{{ $ticket->id }}-quota">
                            Kuota: {{ $ticket->slot }} pax/hari
                        </p>
                    </div>

                    <input type="hidden"
                        name="ticket_{{ $ticket->id }}"
                        id="ticket-{{ $ticket->id }}-input"
                        value="0"
                        data-slot="{{ $ticket->slot }}">

                    <div class="flex items-center gap-4">
                        <button type="button"
                            onclick="changeQty({{ $ticket->id }}, -1)"
                            class="w-12 h-12 rounded-xl bg-[#F9F7F2] text-xl">
                            -
                        </button>

                        <span id="ticket-{{ $ticket->id }}-qty"
                            class="text-xl font-bold w-8 text-center">
                            0
                        </span>

                        <button type="button"
                            id="ticket-{{ $ticket->id }}-plus"
                            onclick="changeQty({{ $ticket->id }}, 1)"
                            class="w-12 h-12 rounded-xl bg-[#F9F7F2] text-xl">
                            +
                        </button>
                    </div>

                </div>

                @endforeach

            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            <div class="sticky top-28 bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-xl">

                <h2 class="text-3xl font-bold text-[#102A43] mb-8">
                    Ringkasan Pesanan
                </h2>

                <div class="space-y-5">

                    <div>
                        <h3 class="font-bold text-2xl text-[#102A43]">
                            {{ $museum->name }}
                        </h3>

                        <p id="summary-date" class="text-slate-500 mt-2">
                            Tanggal: -
                        </p>
                    </div>

                    <div id="ticket-summary" class="border-t pt-5 space-y-3"></div>

                    <div class="border-t pt-6 flex justify-between items-center">
                        <span class="font-bold text-2xl text-[#102A43]">
                            Total
                        </span>

                        <span id="total-price" class="text-4xl font-bold text-[#B88A44]">
                            Rp 0
                        </span>
                    </div>

                </div>

                <button type="submit"
                        class="mt-8 w-full py-5 rounded-2xl bg-[#102A43] text-white font-semibold text-lg hover:bg-[#0d2238] transition">
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

updateTotal();
</script>

@endsection
