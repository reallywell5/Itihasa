@extends('layouts.user')

@section('title', 'Booking Tiket')

@section('content')

<section class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

<form method="POST" action="{{ route('user.booking.store', $museum->id) }}">
    @csrf

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-10">

        <a href="{{ route('museum.detail', $museum->id) }}"
           class="flex items-center gap-2 text-[#102A43] font-medium hover:text-[#B88A44] transition">
            ← Kembali
        </a>

        <h1 class="text-3xl font-bold text-[#102A43] tracking-wide">
            Itihasa
        </h1>

        <div></div>

    </div>

    {{-- STEP --}}
    <div class="flex items-center justify-center mb-12">

        <div class="flex items-center gap-8">

            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold">
                    1
                </div>
                <span class="mt-3 text-sm font-semibold text-[#102A43]">
                    PILIH JADWAL
                </span>
            </div>

            <div class="w-28 h-[2px] bg-[#D9CBB8]"></div>

            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full border-2 border-[#B88A44] text-[#B88A44] flex items-center justify-center font-bold">
                    2
                </div>
                <span class="mt-3 text-sm font-semibold text-slate-500">
                    PILIH TIKET
                </span>
            </div>

            <div class="w-28 h-[2px] bg-[#D9CBB8]"></div>

            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full border-2 border-[#B88A44] text-[#B88A44] flex items-center justify-center font-bold">
                    3
                </div>
                <span class="mt-3 text-sm font-semibold text-slate-500">
                    PEMBAYARAN
                </span>
            </div>

        </div>

    </div>

    <div class="grid lg:grid-cols-[1fr_380px] gap-10">

        {{-- LEFT --}}
        <div class="space-y-8">

            {{-- DATE --}}
            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                    Pilih Tanggal
                </h2>

                <div class="bg-[#F9F7F2] rounded-2xl p-8 border border-[#EADBC8]">
                    <input type="date"
                           name="visit_date"
                           id="visit-date"
                           required
                           class="w-full px-5 py-4 rounded-xl border border-[#D9CBB8] focus:ring-2 focus:ring-[#B88A44] outline-none">
                </div>

            </div>

            {{-- TICKET --}}
            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-2xl font-bold text-[#102A43] mb-8">
                    Pilih Tiket
                </h2>

                @php
                    $tickets = [
                        ['name' => 'adult', 'label' => 'Dewasa', 'price' => 25000],
                        ['name' => 'student', 'label' => 'Pelajar', 'price' => 15000],
                        ['name' => 'child', 'label' => 'Anak', 'price' => 10000],
                    ];
                @endphp

                <div class="space-y-6">

                    @foreach($tickets as $ticket)
                    <div class="flex justify-between items-center border-b border-[#EADBC8] pb-6">

                        <div>
                            <h3 class="font-bold text-lg text-[#102A43]">
                                {{ $ticket['label'] }}
                            </h3>

                            <p class="text-slate-500 text-sm">
                                Rp {{ number_format($ticket['price'],0,',','.') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-4">

                            <button type="button"
                                    onclick="changeQty('{{ $ticket['name'] }}', -1)"
                                    class="px-4 py-3 bg-[#F9F7F2] rounded-xl">
                                -
                            </button>

                            <span id="{{ $ticket['name'] }}-qty">0</span>

                            <input type="hidden"
                                   name="{{ $ticket['name'] }}_qty"
                                   id="{{ $ticket['name'] }}-input"
                                   value="0">

                            <button type="button"
                                    onclick="changeQty('{{ $ticket['name'] }}', 1)"
                                    class="px-4 py-3 bg-[#F9F7F2] rounded-xl">
                                +
                            </button>

                        </div>

                    </div>
                    @endforeach

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            <div class="sticky top-28 bg-white rounded-[28px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                    Ringkasan Pesanan
                </h2>

                <div class="space-y-5">

                    <div>
                        <h3 class="font-bold text-xl text-[#102A43]">
                            {{ $museum->name }}
                        </h3>

                        <p id="summary-date" class="text-slate-500 mt-2">
                            Tanggal: --
                        </p>
                    </div>

                    <div id="ticket-summary" class="space-y-2"></div>

                    <div class="border-t pt-5 flex justify-between items-center">

                        <span class="font-bold text-xl text-[#102A43]">
                            Total
                        </span>

                        <span id="total-price" class="text-3xl font-bold text-[#B88A44]">
                            Rp 0
                        </span>

                    </div>

                </div>

                <button type="submit"
                        class="mt-8 w-full flex justify-center py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0d2238] transition">
                    LANJUT PEMBAYARAN
                </button>

            </div>

        </div>

    </div>

</form>

</section>

<script>
const prices = {
    adult: 25000,
    student: 15000,
    child: 10000
};

function changeQty(type, change) {
    let qtyElement = document.getElementById(type + '-qty');
    let inputElement = document.getElementById(type + '-input');

    let qty = parseInt(qtyElement.innerText);
    qty = Math.max(0, qty + change);

    qtyElement.innerText = qty;
    inputElement.value = qty;

    updateSummary();
}

function updateSummary() {
    let total = 0;
    let summary = '';

    Object.keys(prices).forEach(type => {
        let qty = parseInt(document.getElementById(type + '-input').value);

        if (qty > 0) {
            let subtotal = qty * prices[type];
            total += subtotal;

            summary += `
                <div class="flex justify-between text-sm">
                    <span>${qty}x ${type}</span>
                    <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
                </div>
            `;
        }
    });

    document.getElementById('ticket-summary').innerHTML = summary;
    document.getElementById('total-price').innerText =
        'Rp ' + total.toLocaleString('id-ID');
}

document.getElementById('visit-date').addEventListener('change', function() {
    document.getElementById('summary-date').innerText =
        'Tanggal: ' + this.value;
});
</script>

@endsection
