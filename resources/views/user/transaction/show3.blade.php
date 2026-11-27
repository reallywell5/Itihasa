@extends('layouts.user')

@section('title', 'Detail Pembayaran')

@section('content')

<section class="max-w-3xl mx-auto py-4 sm:py-8">

    <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-10">

        <div class="text-center pb-6 mb-6 border-b border-neutral-200">
            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] mb-2">
                Proses Pembayaran Midtrans
            </p>
            <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[#1A1A1A] tracking-tight mb-2">
                Selesaikan Pembayaran
            </h1>
            <p class="text-xs sm:text-sm text-[#5A5A5A]">
                Pilih dan selesaikan pembayaran sesuai metode yang telah Anda tentukan.
            </p>

            {{-- STATUS --}}
            <div class="mt-5">
                @if($transaction->payment_status == 'pending')
                    <span class="inline-block px-3.5 py-1 rounded-sm bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold uppercase tracking-wider">
                        Menunggu Pembayaran
                    </span>
                @elseif($transaction->payment_status == 'paid')
                    <span class="inline-block px-3.5 py-1 rounded-sm bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold uppercase tracking-wider">
                        Pembayaran Berhasil
                    </span>
                @else
                    <span class="inline-block px-3.5 py-1 rounded-sm bg-rose-50 border border-rose-200 text-[#8C3B2B] text-xs font-semibold uppercase tracking-wider">
                        {{ ucfirst($transaction->payment_status) }}
                    </span>
                @endif
            </div>
        </div>

        {{-- COUNTDOWN TIMER --}}
        @if($transaction->payment_status == 'pending')
            @php
                $expiresAt = $transaction->expired_at ? $transaction->expired_at->timestamp : $transaction->created_at->addMinutes(15)->timestamp;
            @endphp

            <div class="mb-8 p-5 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-center max-w-md mx-auto">
                <p class="text-[10px] uppercase font-semibold tracking-[0.2em] text-[#8C3B2B] mb-1">
                    Batas Waktu Pembayaran
                </p>
                <div id="countdown-timer" class="font-serif text-4xl font-semibold text-[#1A1A1A] my-1">
                    15:00
                </div>
                <p class="text-xs text-[#5A5A5A]">
                    Harap selesaikan pembayaran sebelum waktu berakhir.
                </p>
            </div>
        @endif

        {{-- METODE PEMBAYARAN: QRIS --}}
        @if($transaction->payment_method == 'qris')
            <div class="text-center bg-[#F8F7F4] p-6 sm:p-8 rounded-sm border border-neutral-200 max-w-xl mx-auto mb-8">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <h2 class="font-serif text-xl font-semibold text-[#1A1A1A]">
                        Scan QRIS
                    </h2>
                    <span class="text-[10px] uppercase font-semibold tracking-wider text-white bg-[#1A1A1A] px-2 py-0.5 rounded-sm">
                        Midtrans
                    </span>
                </div>

                <img src="{{ asset('images/qris-pembayaran.jpeg') }}"
                     alt="QRIS Pembayaran"
                     class="w-60 mx-auto mb-6 rounded-sm border border-neutral-200 bg-white p-2">

                <div class="text-left bg-white p-5 rounded-sm border border-neutral-200 text-xs sm:text-sm space-y-1.5 text-[#5A5A5A]">
                    <p class="font-semibold text-[#1A1A1A] mb-2">Instruksi Pembayaran QRIS:</p>
                    <p>1. Buka aplikasi Mobile Banking atau E-Wallet (GoPay, OVO, DANA, ShopeePay).</p>
                    <p>2. Pilih fitur <strong class="text-[#1A1A1A]">Scan QRIS</strong>.</p>
                    <p>3. Arahkan kamera ke kode QRIS di atas.</p>
                    <p>4. Periksa nominal <strong class="text-[#1A1A1A]">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong> dan konfirmasi bayar.</p>
                </div>
            </div>
        @endif

        {{-- BANK TRANSFER --}}
        @if($transaction->payment_method == 'bank_transfer')
            @php
                $vaNumber = '8808' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT);
            @endphp
            <div class="text-center bg-[#F8F7F4] p-6 sm:p-8 rounded-sm border border-neutral-200 max-w-xl mx-auto mb-8">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <h2 class="font-serif text-xl font-semibold text-[#1A1A1A]">
                        Transfer Virtual Account
                    </h2>
                    <span class="text-[10px] uppercase font-semibold tracking-wider text-white bg-[#1A1A1A] px-2 py-0.5 rounded-sm">
                        Midtrans
                    </span>
                </div>

                <div class="bg-white rounded-sm p-5 border border-neutral-200 mb-6">
                    <p class="text-xs text-[#5A5A5A] mb-2">Nomor Virtual Account</p>
                    <div class="flex items-center justify-center gap-3 flex-wrap">
                        <h3 id="va-text" class="font-serif text-2xl sm:text-3xl font-semibold text-[#1A1A1A] tracking-wider">
                            {{ $vaNumber }}
                        </h3>
                        <button type="button"
                                onclick="copyText('{{ $vaNumber }}', 'btn-copy-va')"
                                id="btn-copy-va"
                                class="px-3 py-1.5 rounded-sm bg-[#1A1A1A] text-white text-xs font-semibold hover:bg-[#B8860B] transition duration-200">
                            Salin
                        </button>
                    </div>
                    <p class="text-[11px] uppercase tracking-wider text-[#5A5A5A] mt-3">
                        A/N ITIHASA HERITAGE MUSEUM
                    </p>
                </div>

                <div class="text-left bg-white p-5 rounded-sm border border-neutral-200 text-xs sm:text-sm space-y-1.5 text-[#5A5A5A]">
                    <p class="font-semibold text-[#1A1A1A] mb-2">Instruksi Transfer Virtual Account:</p>
                    <p>1. Buka aplikasi Mobile Banking atau Internet Banking Anda.</p>
                    <p>2. Pilih menu <strong class="text-[#1A1A1A]">Transfer &gt; Virtual Account</strong>.</p>
                    <p>3. Masukkan nomor VA: <strong class="text-[#1A1A1A]">{{ $vaNumber }}</strong>.</p>
                    <p>4. Pastikan nominal &amp; penerima <strong class="text-[#1A1A1A]">ITIHASA</strong> telah sesuai, lalu konfirmasi.</p>
                </div>
            </div>
        @endif

        {{-- E-WALLET --}}
        @if($transaction->payment_method == 'e_wallet')
            @php
                $ewalletNum = '085142258437';
            @endphp
            <div class="text-center bg-[#F8F7F4] p-6 sm:p-8 rounded-sm border border-neutral-200 max-w-xl mx-auto mb-8">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <h2 class="font-serif text-xl font-semibold text-[#1A1A1A]">
                        Transfer E-Wallet
                    </h2>
                    <span class="text-[10px] uppercase font-semibold tracking-wider text-white bg-[#1A1A1A] px-2 py-0.5 rounded-sm">
                        Midtrans
                    </span>
                </div>

                <div class="bg-white rounded-sm p-5 border border-neutral-200 mb-6">
                    <p class="text-xs text-[#5A5A5A] mb-2">DANA / GoPay</p>
                    <div class="flex items-center justify-center gap-3 flex-wrap">
                        <h3 class="font-serif text-2xl sm:text-3xl font-semibold text-[#1A1A1A] tracking-wider">
                            {{ $ewalletNum }}
                        </h3>
                        <button type="button"
                                onclick="copyText('{{ $ewalletNum }}', 'btn-copy-ewallet')"
                                id="btn-copy-ewallet"
                                class="px-3 py-1.5 rounded-sm bg-[#1A1A1A] text-white text-xs font-semibold hover:bg-[#B8860B] transition duration-200">
                            Salin
                        </button>
                    </div>
                    <p class="text-[11px] uppercase tracking-wider text-[#5A5A5A] mt-3">
                        A/N ITIHASA HERITAGE MUSEUM
                    </p>
                </div>

                <div class="text-left bg-white p-5 rounded-sm border border-neutral-200 text-xs sm:text-sm space-y-1.5 text-[#5A5A5A]">
                    <p class="font-semibold text-[#1A1A1A] mb-2">Instruksi Pembayaran E-Wallet:</p>
                    <p>1. Buka aplikasi DANA atau GoPay pada perangkat Anda.</p>
                    <p>2. Pilih menu <strong class="text-[#1A1A1A]">Kirim / Transfer</strong> ke Nomor Telepon.</p>
                    <p>3. Masukkan nomor tujuan <strong class="text-[#1A1A1A]">{{ $ewalletNum }}</strong>.</p>
                    <p>4. Masukkan nominal tepat <strong class="text-[#1A1A1A]">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong> dan selesaikan transaksi.</p>
                </div>
            </div>
        @endif

        {{-- RINCIAN INVOICE --}}
        <div class="bg-[#F8F7F4] rounded-sm p-6 border border-neutral-200 max-w-xl mx-auto mb-8 space-y-4">
            <div class="flex justify-between items-center border-b border-neutral-200 pb-3 text-xs sm:text-sm">
                <span class="text-[#5A5A5A]">Kode Invoice</span>
                <span class="font-mono font-semibold text-[#1A1A1A]">{{ $transaction->invoice_code }}</span>
            </div>

            <div>
                <p class="text-[10px] uppercase tracking-wider font-semibold text-[#B8860B] mb-2">Rincian Tiket</p>
                <div class="space-y-1.5 text-xs sm:text-sm">
                    @foreach($transaction->booking->ticket_items as $item)
                        <div class="flex justify-between items-center">
                            <span class="text-[#1A1A1A]">{{ $item['ticket_name'] }} x {{ $item['qty'] }}</span>
                            <span class="font-semibold text-[#1A1A1A]">
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

            <div class="flex justify-between items-baseline border-t border-neutral-200 pt-4">
                <span class="font-semibold text-sm text-[#1A1A1A]">Total Pembayaran</span>
                <span class="font-serif text-2xl font-semibold text-[#B8860B]">
                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- ACTION BUTTON --}}
        @if($transaction->payment_status == 'pending')
            <form action="{{ route('user.payment.confirm', $transaction->id) }}"
                  method="POST"
                  class="max-w-xl mx-auto"
                  onsubmit="document.getElementById('btn-confirm-payment').disabled = true; document.getElementById('btn-confirm-payment').innerText = 'Memproses...';">
                @csrf

                <button id="btn-confirm-payment"
                        class="w-full py-3.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300 disabled:opacity-60 disabled:cursor-not-allowed">
                    Saya Sudah Bayar
                </button>
            </form>

            <p class="text-center text-[11px] text-[#5A5A5A] mt-4">
                Transaksi diamankan oleh Midtrans Payment Gateway
            </p>
        @elseif($transaction->payment_status == 'paid')
            <div class="max-w-xl mx-auto">
                <a href="{{ route('user.ticket', $transaction->id) }}"
                   class="w-full flex justify-center py-3.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300">
                    Lihat Tiket QR
                </a>
            </div>
        @else
            <div class="max-w-xl mx-auto text-center space-y-3">
                <div class="p-4 rounded-sm bg-rose-50 border border-rose-200 text-[#8C3B2B] text-xs font-medium">
                    Pembayaran gagal atau sudah kedaluwarsa. Tiket tidak dapat digunakan.
                </div>

                <a href="{{ route('user.booking', $transaction->booking->museum->id) }}"
                   class="w-full flex justify-center py-3.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white font-semibold text-xs uppercase tracking-wider transition duration-300">
                    Pesan Ulang Tiket
                </a>

                <a href="{{ route('user.home') }}"
                   class="w-full flex justify-center py-3 rounded-sm border border-neutral-300 text-[#1A1A1A] text-xs font-semibold uppercase tracking-wider hover:bg-[#F8F7F4] transition duration-300">
                    Kembali ke Beranda
                </a>
            </div>
        @endif

    </div>

</section>

@if($transaction->payment_status == 'pending')
<script>
    let expireTimestamp = {{ $expiresAt ?? time() + 900 }};

    function updateTimer() {
        let now = Math.floor(Date.now() / 1000);
        let remaining = expireTimestamp - now;

        let timerEl = document.getElementById('countdown-timer');
        if (!timerEl) return;

        if (remaining <= 0) {
            timerEl.innerHTML = '<span class="text-[#8C3B2B]">00:00 (Kedaluwarsa)</span>';
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
            btn.innerHTML = '✓ Tersalin';
            btn.classList.remove('bg-[#1A1A1A]');
            btn.classList.add('bg-emerald-700');

            setTimeout(function() {
                btn.innerHTML = original;
                btn.classList.remove('bg-emerald-700');
                btn.classList.add('bg-[#1A1A1A]');
            }, 2000);
        });
    }
</script>

@endsection
