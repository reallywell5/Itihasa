@extends('layouts.petugas')

@section('title', 'Scan QR Ticket')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold mb-1.5">
                📷 Gate Masuk • Scanner Kamera
            </span>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800">
                Pindai QR Tiket Pengunjung
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Arahkan kamera ke layar smartphone atau cetakan tiket pengunjung untuk verifikasi otomatis.
            </p>
        </div>

        <a href="{{ route('petugas.validasi') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition shrink-0">
            Validasi Manual
        </a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- MANIFES ROMBONGAN --}}
    @if(session('scan_manifest'))
        @php $manifest = session('scan_manifest'); @endphp
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 sm:p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-emerald-900 text-sm">
                    Manifes Rombongan
                </h3>
                <span class="text-xs font-bold text-emerald-700 bg-white px-3 py-1 rounded-full border border-emerald-200 shadow-sm">
                    {{ $manifest['jumlah_anggota'] }} orang
                </span>
            </div>

            <p class="text-xs text-emerald-800">
                Penanggung jawab: <strong>{{ $manifest['nama_penanggung_jawab'] }}</strong> — periksa daftar anggota di bawah ini:
            </p>

            <div class="bg-white rounded-xl border border-emerald-100 p-3 max-h-56 overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach($manifest['daftar_nama'] as $index => $nama)
                        <div class="flex items-center gap-2 text-slate-700 bg-slate-50 p-2 rounded-lg">
                            <span class="text-[11px] font-bold text-emerald-600 w-5 shrink-0">{{ $index + 1 }}.</span>
                            <span class="font-medium">{{ $nama }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        {{-- CAMERA SCANNER BOX --}}
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-bold text-slate-800">
                            Kamera Pemindai QR
                        </h2>
                        <p class="text-[10px] text-slate-400">
                            Aktifkan izin kamera pada browser HP / laptop.
                        </p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                <div class="p-3 bg-slate-900">
                    <div id="reader" class="w-full rounded-xl overflow-hidden text-white"></div>
                </div>
            </div>
        </div>

        {{-- SCAN RESULT & AUTO FORM --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5">
                <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
                    Hasil Pemindaian
                </h2>

                <div id="result" class="mb-3">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-center">
                        <p class="text-xs text-slate-400">
                            Menunggu QR Code diarahkan ke kamera...
                        </p>
                    </div>
                </div>

                <div id="scan-status-badge" class="hidden p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold text-center">
                    ✓ QR Berhasil Dibaca! Mengirim verifikasi...
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5">
                <form id="qr-form"
                      action="{{ route('petugas.qrcodes.validate') }}"
                      method="POST"
                      class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">
                            Kode Tiket / Invoice
                        </label>
                        <input type="text"
                               name="invoice_code"
                               id="qr_code"
                               readonly
                               required
                               placeholder="Menunggu pemindaian..."
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-50 font-mono text-xs text-slate-800 focus:outline-none">
                    </div>

                    <button type="submit"
                            id="btn-validate-submit"
                            class="w-full py-3 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition shadow">
                        Validasi Tiket Sekarang
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
            <div class="w-full bg-emerald-50 border border-emerald-200 rounded-xl p-3.5">
                <p class="font-bold text-emerald-800 text-xs mb-0.5">
                    ✓ QR Code Terdeteksi!
                </p>
                <p class="font-mono text-xs text-slate-700 break-all">
                    ${decodedText}
                </p>
            </div>
        `;
    }

    let badge = document.getElementById('scan-status-badge');
    if (badge) badge.classList.remove('hidden');

    let btn = document.getElementById('btn-validate-submit');
    if (btn) {
        btn.innerText = 'Memverifikasi Tiket...';
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
            width: 240,
            height: 240
        }
    }
);

scanner.render(onScanSuccess);
</script>

@endsection
