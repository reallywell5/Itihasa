@extends('layouts.user')

@section('title', 'Detail Pembayaran')

@section('content')

<section class="max-w-4xl mx-auto px-6 lg:px-8 py-14">

    <div class="bg-white rounded-[32px] border border-[#EADBC8] shadow-sm p-8">

        <div class="text-center mb-6">

            <h1 class="text-4xl font-bold text-[#102A43] mb-4">
                Selesaikan Pembayaran
            </h1>

            <p class="text-slate-500">
                Pilih dan selesaikan pembayaran sesuai metode yang dipilih.
            </p>

        </div>

        {{-- MIDTRANS BADGE --}}
        <div class="flex justify-center mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#F9F7F2] border border-[#EADBC8]">
                <span class="text-xs text-slate-400">Transaksi diproses melalui</span>
                <span class="text-sm font-bold text-[#102A43]">Midtrans</span>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="mb-6 text-center">

            @if($transaction->payment_status == 'pending')
                <span class="px-4 py-2 rounded-xl bg-yellow-100 text-yellow-700 text-sm font-semibold">
                    Menunggu Pembayaran
                </span>
            @elseif($transaction->payment_status == 'paid')
                <span class="px-4 py-2 rounded-xl bg-green-100 text-green-700 text-sm font-semibold">
                    Pembayaran Berhasil
                </span>
            @else
                <span class="px-4 py-2 rounded-xl bg-red-100 text-red-700 text-sm font-semibold">
                    {{ ucfirst($transaction->payment_status) }}
                </span>
            @endif

        </div>

        {{-- COUNTDOWN TIMER --}}
        @if($transaction->payment_status == 'pending')
            @php
                $expiresAt = $transaction->expired_at ? $transaction->expired_at->timestamp : $transaction->created_at->addMinutes(15)->timestamp;
            @endphp

            <div class="mb-8 p-5 rounded-2xl bg-amber-50 border border-amber-200 text-center max-w-md mx-auto shadow-sm">
                <p class="text-xs uppercase font-bold tracking-wider text-amber-700 mb-1">
                    ⏱️ Batas Waktu Pembayaran
                </p>
                <div id="countdown-timer" class="text-4xl font-extrabold font-mono text-[#102A43] my-1">
                    15:00
                </div>
                <p class="text-xs text-slate-500">
                    Harap selesaikan pembayaran sebelum waktu berakhir.
                </p>
            </div>
        @endif

        {{-- METODE PEMBAYARAN DETAIL --}}
        @if($transaction->payment_method == 'qris')
            <div class="text-center bg-[#F9F7F2] p-8 rounded-3xl border border-[#EADBC8] max-w-xl mx-auto mb-8">

                <div class="flex items-center justify-center gap-2 mb-4">
                    <h2 class="text-2xl font-bold text-[#102A43]">
                        Scan QRIS
                    </h2>
                    <span class="text-[10px] uppercase font-bold tracking-wider text-white bg-[#102A43] px-2 py-1 rounded-full">
                        Midtrans
                    </span>
                </div>

                <img src="{{ asset('images/qris-pembayaran.jpeg') }}"
                     alt="QRIS Pembayaran"
                     class="w-64 mx-auto mb-6 rounded-2xl shadow-md border border-white">

                <div class="text-left bg-white p-5 rounded-2xl border border-[#EADBC8] text-sm space-y-2">
                    <p class="font-bold text-[#102A43] mb-2">📋 Instruksi Pembayaran QRIS:</p>
                    <p class="text-slate-600">1. Buka aplikasi Mobile Banking (BCA, Mandiri, BRI, dll) atau E-Wallet (DANA, GoPay, OVO, ShopeePay).</p>
                    <p class="text-slate-600">2. Pilih fitur <strong>Scan QRIS</strong>.</p>
                    <p class="text-slate-600">3. Arahkan kamera ke kode QRIS di atas.</p>
                    <p class="text-slate-600">4. Periksa nominal pembayaran tepat <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong> dan tekan bayar.</p>
                </div>

            </div>
        @endif

        {{-- BANK TRANSFER --}}
        @if($transaction->payment_method == 'bank_transfer')
            @php
                $vaNumber = '8808' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT);
            @endphp
            <div class="text-center bg-[#F9F7F2] p-8 rounded-3xl border border-[#EADBC8] max-w-xl mx-auto mb-8">

                <div class="flex items-center justify-center gap-2 mb-4">
                    <h2 class="text-2xl font-bold text-[#102A43]">
                        Transfer Virtual Account
                    </h2>
                    <span class="text-[10px] uppercase font-bold tracking-wider text-white bg-[#102A43] px-2 py-1 rounded-full">
                        Midtrans
                    </span>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-[#EADBC8] shadow-sm mb-6 inline-block w-full">
                    <p class="text-slate-400 text-sm mb-2">
                        Midtrans Virtual Account
                    </p>

                    <div class="flex items-center justify-center gap-3">
                        <h3 id="va-text" class="text-3xl font-mono font-bold text-[#102A43] tracking-wider">
                            {{ $vaNumber }}
                        </h3>

                        <button type="button"
                                onclick="copyText('{{ $vaNumber }}', 'btn-copy-va')"
                                id="btn-copy-va"
                                class="px-3 py-1.5 rounded-lg bg-[#102A43] text-white text-xs font-semibold hover:bg-[#0c2238] transition flex items-center gap-1">
                            📋 Salin
                        </button>
                    </div>

                    <p class="text-xs text-slate-400 mt-3">
                        A/N ITIHASA HERITAGE MUSEUM
                    </p>
                </div>

                <div class="text-left bg-white p-5 rounded-2xl border border-[#EADBC8] text-sm space-y-2">
                    <p class="font-bold text-[#102A43] mb-2">📋 Instruksi Transfer Virtual Account:</p>
                    <p class="text-slate-600">1. Buka aplikasi Mobile Banking / Internet Banking Anda.</p>
                    <p class="text-slate-600">2. Pilih menu <strong>Transfer > Virtual Account</strong>.</p>
                    <p class="text-slate-600">3. Masukkan nomor VA: <strong class="font-mono text-[#102A43]">{{ $vaNumber }}</strong>.</p>
                    <p class="text-slate-600">4. Pastikan nama penerima <strong>ITIHASA</strong> dan jumlah total pembayaran sudah sesuai.</p>
                    <p class="text-slate-600">5. Konfirmasi pembayaran dan masukkan PIN transaksi Anda.</p>
                </div>

            </div>
        @endif

        {{-- E-WALLET --}}
        @if($transaction->payment_method == 'e_wallet')
            @php
                $ewalletNum = '085142258437';
            @endphp
            <div class="text-center bg-[#F9F7F2] p-8 rounded-3xl border border-[#EADBC8] max-w-xl mx-auto mb-8">

                <div class="flex items-center justify-center gap-2 mb-4">
                    <h2 class="text-2xl font-bold text-[#102A43]">
                        Transfer E-Wallet
                    </h2>
                    <span class="text-[10px] uppercase font-bold tracking-wider text-white bg-[#102A43] px-2 py-1 rounded-full">
                        Midtrans
                    </span>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-[#EADBC8] shadow-sm mb-6 inline-block w-full">

                    <p class="text-slate-400 text-sm mb-2">
                        DANA / GoPay
                    </p>

                    <div class="flex items-center justify-center gap-3">
                        <h3 class="text-3xl font-mono font-bold text-[#102A43] tracking-wider">
                            {{ $ewalletNum }}
                        </h3>

                        <button type="button"
                                onclick="copyText('{{ $ewalletNum }}', 'btn-copy-ewallet')"
                                id="btn-copy-ewallet"
                                class="px-3 py-1.5 rounded-lg bg-[#102A43] text-white text-xs font-semibold hover:bg-[#0c2238] transition flex items-center gap-1">
                            📋 Salin
                        </button>
                    </div>

                    <p class="text-xs text-slate-400 mt-3">
                        A/N ITIHASA HERITAGE MUSEUM
                    </p>

                </div>

                <div class="text-left bg-white p-5 rounded-2xl border border-[#EADBC8] text-sm space-y-2">
                    <p class="font-bold text-[#102A43] mb-2">📋 Instruksi Pembayaran E-Wallet:</p>
                    <p class="text-slate-600">1. Buka aplikasi DANA atau GoPay di smartphone Anda.</p>
                    <p class="text-slate-600">2. Pilih menu <strong>Kirim / Transfer</strong> ke Nomor Telepon.</p>
                    <p class="text-slate-600">3. Masukkan nomor: <strong class="font-mono text-[#102A43]">{{ $ewalletNum }}</strong>.</p>
                    <p class="text-slate-600">4. Masukkan jumlah persis <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>.</p>
                    <p class="text-slate-600">5. Konfirmasi pembayaran lalu masukkan PIN E-Wallet Anda.</p>
                </div>

            </div>
        @endif

        {{-- RINCIAN TIKET & INVOICE --}}
        <div class="bg-white rounded-2xl p-6 border border-[#EADBC8] max-w-xl mx-auto mb-8 space-y-4 shadow-sm">

            <div class="flex justify-between items-center border-b pb-3">
                <span class="text-slate-500 text-sm">Kode Invoice</span>
                <span class="font-mono font-bold text-[#102A43] text-base">{{ $transaction->invoice_code }}</span>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-slate-400 mb-2">Rincian Tiket</p>
                <div class="space-y-2">
                    @foreach($transaction->booking->ticket_items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-[#102A43] font-medium">{{ $item['ticket_name'] }} x {{ $item['qty'] }}</span>
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
            </div>

            <div class="flex justify-between items-center border-t pt-4">
                <span class="font-bold text-slate-700 text-lg">Total Pembayaran</span>
                <span class="text-3xl font-bold text-[#B88A44]">
                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- BUTTON --}}
        @if($transaction->payment_status == 'pending')
            <form action="{{ route('user.payment.confirm', $transaction->id) }}"
                  method="POST"
                  class="max-w-xl mx-auto"
                  onsubmit="document.getElementById('btn-confirm-payment').disabled = true; document.getElementById('btn-confirm-payment').innerText = 'Memproses...';">
                @csrf

                <button
                    id="btn-confirm-payment"
                    class="w-full py-4 rounded-2xl bg-[#102A43] text-white font-bold text-lg hover:bg-[#0c2238] transition shadow-lg disabled:opacity-60 disabled:cursor-not-allowed">

                    Saya Sudah Bayar

                </button>
            </form>

            <p class="text-center text-[11px] text-slate-400 mt-4">
                🔒 Transaksi diamankan oleh Midtrans Payment Gateway
            </p>
        @elseif($transaction->payment_status == 'paid')
            <div class="max-w-xl mx-auto">
                <a href="{{ route('user.ticket', $transaction->id) }}"
                   class="w-full flex justify-center py-4 rounded-2xl bg-[#102A43] text-white font-bold text-lg hover:bg-[#0c2238] transition shadow-lg">
                    Lihat Tiket QR
                </a>
            </div>
        @else
            {{-- payment_status == 'failed' (kadaluwarsa/gagal) --}}
            <div class="max-w-xl mx-auto text-center space-y-4">
                <div class="p-4 rounded-2xl bg-red-50 border border-red-100 text-red-600 text-sm font-semibold">
                    Pembayaran gagal atau sudah kedaluwarsa. Tiket tidak dapat digunakan.
                </div>

                <a href="{{ route('user.booking', $transaction->booking->museum->id) }}"
                   class="w-full flex justify-center py-4 rounded-2xl bg-[#102A43] text-white font-bold text-lg hover:bg-[#0c2238] transition shadow-lg">
                    Pesan Ulang Tiket
                </a>

                <a href="{{ route('user.home') }}"
                   class="w-full flex justify-center py-3 rounded-2xl border border-[#EADBC8] text-[#102A43] font-semibold hover:bg-[#F6F1E8] transition">
                    Kembali ke Beranda
                </a>
            </div>
        @endif

    </div>

</section>

@if($transaction->payment_status == 'pending')
<script>
    // COUNTDOWN TIMER
    let expireTimestamp = {{ $expiresAt ?? time() + 900 }};

    function updateTimer() {
        let now = Math.floor(Date.now() / 1000);
        let remaining = expireTimestamp - now;

        let timerEl = document.getElementById('countdown-timer');
        if (!timerEl) return;

        if (remaining <= 0) {
            timerEl.innerHTML = '<span class="text-red-500">00:00 (Kedaluwarsa)</span>';
            return;
        }

        let minutes = Math.floor(remaining / 60);
        let seconds = remaining % 60;

        let mStr = minutes < 10 ? '0' + minutes : minutes;
        let sStr = seconds < 10 ? '0' + seconds : seconds;

        timerEl.innerText = mStr + ':' + sStr;
    }

    setInterval(updateTimer, 1000);
    updateTimer();
</script>
@endif

<script>
    function copyText(text, btnId) {
        navigator.clipboard.writeText(text).then(function() {
            let btn = document.getElementById(btnId);
            let original = btn.innerHTML;
            btn.innerHTML = '✓ Tersalin!';
            btn.classList.remove('bg-[#102A43]');
            btn.classList.add('bg-green-600');

            setTimeout(function() {
                btn.innerHTML = original;
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-[#102A43]');
            }, 2000);
        });
    }
</script>

@endsection
