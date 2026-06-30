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

                <h2 class="font-bold text-zinc-900 mb-4">
                    Hasil Scan
                </h2>

                <button type="submit"
                    id="submit-btn"
                    disabled
                    class="w-full px-6 py-3.5 rounded-xl bg-zinc-900 text-white text-sm font-semibold disabled:opacity-50">
                    Menunggu Scan...
                </button>

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
                            Invoice Code
                        </label>

                        <input type="text"
                               name="invoice_code"
                               id="qr_code"
                               readonly
                               required
                               class="w-full border border-zinc-200 rounded-xl px-4 py-3 bg-zinc-50 font-mono text-sm">
                    </div>

                    <button type="submit"
                            id="submit-btn"
                            class="w-full py-3 rounded-xl bg-zinc-900 text-white font-semibold hover:bg-zinc-800 transition">
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

function onScanSuccess(decodedText) {
    if (isProcessing) return;

    isProcessing = true;

    document.getElementById('qr_code').value = decodedText;

    document.getElementById('result').innerHTML = `
        <div class="w-full">
            <p class="font-bold text-green-600 mb-2">
                QR Berhasil Dibaca
            </p>
            <div class="bg-white border rounded-lg p-3 text-xs font-mono break-all">
                ${decodedText}
            </div>
        </div>
    `;

    document.getElementById('submit-btn').innerText = 'Memproses...';
    document.getElementById('submit-btn').disabled = true;

    setTimeout(() => {
        document.getElementById('qr-form').submit();
    }, 500);
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
