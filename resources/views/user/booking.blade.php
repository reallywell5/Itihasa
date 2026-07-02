@extends('layouts.user')

@section('title', 'Booking Ticket')

@section('content')

<form action="{{ route('user.booking.store', $museum->id) }}" method="POST">
@csrf

<section class="max-w-7xl mx-auto px-6 lg:px-8 py-14">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-12">

        <a href="{{ route('museum.detail', $museum->id) }}"
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

            {{-- DATE --}}
            <div class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-3xl font-bold text-[#102A43] mb-6">
                    Pilih Tanggal
                </h2>

                <input type="date"
                       name="visit_date"
                       id="visit-date"
                       required
                       class="w-full px-6 py-5 rounded-2xl border border-[#D9CBB8] text-lg">

            </div>

            {{-- TICKET --}}
            <div class="bg-white rounded-[32px] border border-[#EADBC8] p-8 shadow-sm">

                <h2 class="text-3xl font-bold text-[#102A43] mb-8">
                    Pilih Tiket
                </h2>

                @foreach($museum->tickets as $ticket)

                <div class="flex justify-between items-center py-6 border-b">

                    <div>
                        <h3 class="font-bold text-xl">{{ $ticket->ticket_name }}</h3>
                        <p class="text-slate-500">
                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <input type="hidden"
                        name="ticket_{{ $ticket->id }}"
                        id="ticket-{{ $ticket->id }}-input"
                        value="0">

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
function changeQty(ticketId, change) {
    let input = document.getElementById('ticket-' + ticketId + '-input');
    let qtyText = document.getElementById('ticket-' + ticketId + '-qty');

    let current = parseInt(input.value);
    let updated = current + change;

    if (updated < 0) updated = 0;

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

document.getElementById('visit-date').addEventListener('change', function () {
    document.getElementById('summary-date').innerText =
        'Tanggal: ' + this.value;
});

updateTotal();
</script>

@endsection
