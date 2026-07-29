@extends('layouts.petugas')

@section('title', 'Scan QR Code')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 py-6 px-4">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-zinc-100 pb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-zinc-900 tracking-tight">
                Scan QR Ticket
            </h1>
            <p class="text-sm text-zinc-500 mt-1">
                Arahkan kamera ke QR Ticket pengunjung untuk validasi masuk.
            </p>
        </div>

        <a href="{{ route('petugas.qrcodes.index') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-zinc-200 bg-white text-sm font-semibold text-zinc-700 hover:bg-zinc-50">
            Kembali
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-12 gap-8">

        {{-- CAMERA --}}
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">

                <div class="p-5 border-b border-zinc-100 bg-zinc-50">
                    <h2 class="font-bold text-zinc-900">
                        Kamera Scanner
                    </h2>
                    <p class="text-xs text-zinc-500 mt-1">
                        Scan QR ticket untuk validasi.
                    </p>
                </div>

                <div class="p-4 bg-zinc-950">
                    <div id="reader" class="w-full rounded-xl overflow-hidden"></div>
                </div>

            </div>
        </div>

        {{-- RESULT --}}
        <div class="lg:col-span-5 space-y-6">

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <h2 class="font-bold text-zinc-900 mb-3">
                    Hasil Scan
                </h2>

                <div id="result" class="mb-4">
                    <p class="text-sm text-zinc-400">
                        Belum ada QR Code yang dipindai.
                    </p>
                </div>

                <div id="scan-status-badge" class="hidden p-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-xs font-semibold text-center">
                    ✓ QR Berhasil Dibaca
                </div>

            </div>

            {{-- FORM --}}
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

                <form id="qr-form"
                      action="{{ route('petugas.qrcodes.validate') }}"
                      method="POST"
                      class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">
                            Invoice Code / QR Tiket
                        </label>

                        <input type="text"
                               name="invoice_code"
                               id="qr_code"
                               readonly
                               required
                               placeholder="Menunggu scan kamera..."
                               class="w-full border border-zinc-200 rounded-xl px-4 py-3 bg-zinc-50 font-mono text-sm text-zinc-800">
                    </div>

                    <button type="submit"
                            id="btn-validate-submit"
                            class="w-full py-3.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-md">
                        Validasi Tiket
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let isProcessing = false;

function playBeep() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, ctx.currentTime);
        gain.gain.setValueAtTime(0.2, ctx.currentTime);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.2);
    } catch (e) {}
}

function onScanSuccess(decodedText) {
    if (isProcessing) return;

    isProcessing = true;
    playBeep();

    document.getElementById('qr_code').value = decodedText;

    let resultEl = document.getElementById('result');
    if (resultEl) {
        resultEl.innerHTML = `
            <div class="w-full bg-green-50 border border-green-200 rounded-xl p-4">
                <p class="font-bold text-green-700 text-sm mb-1">
                    ✓ QR Code Berhasil Dibaca!
                </p>
                <p class="font-mono text-xs text-zinc-700 break-all">
                    ${decodedText}
                </p>
            </div>
        `;
    }

    let badge = document.getElementById('scan-status-badge');
    if (badge) badge.classList.remove('hidden');

    let btn = document.getElementById('btn-validate-submit');
    if (btn) {
        btn.innerText = 'Memproses Validasi...';
        btn.disabled = true;
    }

    setTimeout(() => {
        document.getElementById('qr-form').submit();
    }, 600);
}

let scanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: {
            width: 250,
            height: 250
        }
    }
);

scanner.render(onScanSuccess);
</script>

@endsection
