@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 py-6 px-4">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-zinc-100 pb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-zinc-900 tracking-tight">Scan QR Code</h1>
            <p class="text-sm text-zinc-500 mt-1">Arahkan kamera ke QR Code tiket untuk validasi masuk secara instan.</p>
        </div>

        <a href="{{ route('qrcodes.index') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-zinc-200 bg-white text-sm font-semibold text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900 shadow-sm transition-all duration-200">
            Kembali ke Daftar
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50">
                    <div>
                        <h2 class="text-base font-bold text-zinc-900">Kamera Pemindai</h2>
                        <p class="text-xs text-zinc-500 mt-0.5">Izinkan akses kamera untuk mulai mendeteksi tiket.</p>
                    </div>

                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                </div>

                <div class="p-4 bg-zinc-950 flex items-center justify-center">
                    <div class="w-full max-w-lg rounded-xl overflow-hidden bg-zinc-900 border border-zinc-800">
                        <div id="reader" class="w-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 space-y-6">

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 space-y-4">
                <div>
                    <h2 class="text-base font-bold text-zinc-900">Hasil Pindai</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Konten string dari kode QR yang terbaca.</p>
                </div>

                <div id="result"
                     class="min-h-[80px] flex items-center justify-center p-4 rounded-xl bg-zinc-50 border border-zinc-200 border-dashed text-sm text-zinc-500 text-center">
                    Menunggu kode QR terdeteksi...
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
                <form id="qr-form" action="{{ route('qrcodes.validate') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="qr_code" class="block text-sm font-semibold text-zinc-700 mb-2">
                            Kode Data QR
                        </label>

                        <input type="text"
                               name="qr_code"
                               id="qr_code"
                               class="w-full border border-zinc-200 bg-zinc-50/50 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:bg-white transition-all shadow-inner"
                               placeholder="Hasil pindai otomatis masuk ke sini"
                               readonly
                               required>
                    </div>

                    <button type="submit"
                            id="submit-btn"
                            class="w-full px-6 py-3.5 rounded-xl bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 active:scale-[0.99] transition-all shadow-sm">
                        Validasi QR Code
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    #reader {
        border: none !important;
        background: transparent !important;
    }

    #reader * {
        font-family: ui-sans-serif, system-ui, sans-serif !important;
    }

    #reader video {
        width: 100% !important;
        height: 320px !important;
        object-fit: cover !important;
        border-radius: 12px !important;
    }

    #reader__scan_region {
        background: transparent !important;
        padding: 0 !important;
        display: flex !important;
        justify-content: center !important;
    }

    #reader__scan_region img {
        display: none !important;
    }

    #reader__dashboard {
        background: #18181b !important;
        border-top: 1px solid #27272a !important;
        padding: 14px !important;
    }

    #reader__dashboard_section_csr button,
    #reader__camera_permission_button {
        padding: 10px 18px !important;
        border-radius: 10px !important;
        background-color: #ffffff !important;
        color: #18181b !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        border: 1px solid #e4e4e7 !important;
        cursor: pointer !important;
    }

    #reader__dashboard_section_swaplink {
        color: #a1a1aa !important;
        font-size: 12px !important;
        margin-top: 8px !important;
        display: inline-block !important;
    }
</style>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let isProcessing = false;

    const beep = new Audio(
        "data:audio/wav;base64,UklGRigAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQQAAAAAAAAG"
    );

    function onScanSuccess(decodedText) {
        if (isProcessing) return;

        isProcessing = true;

        try {
            beep.play();
        } catch (e) {}

        const inputField = document.getElementById('qr_code');
        const resultContainer = document.getElementById('result');
        const submitBtn = document.getElementById('submit-btn');

        inputField.value = decodedText;

        resultContainer.className =
            "min-h-[80px] flex items-center p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-zinc-800";

        resultContainer.innerHTML = `
            <div class="w-full">
                <p class="font-bold text-emerald-800 mb-1">Berhasil Terbaca!</p>
                <p class="font-mono text-xs text-zinc-600 bg-white/80 p-2 rounded border border-emerald-100 break-all select-all">
                    ${decodedText}
                </p>
            </div>
        `;

        submitBtn.innerHTML = 'Memproses...';
        submitBtn.disabled = true;

        setTimeout(() => {
            document.getElementById('qr-form').submit();
        }, 500);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 15,
            qrbox: {
                width: 240,
                height: 240
            },
            rememberLastUsedCamera: true
        },
        false
    );

    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection
