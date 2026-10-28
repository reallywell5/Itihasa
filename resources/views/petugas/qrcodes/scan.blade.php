@extends('layouts.petugas')

@section('title', 'Scan QR Ticket')

@section('content')
<div class="max-w-5xl mx-auto space-y-4 sm:space-y-5" x-data="offlineScannerApp()">

    {{-- FITUR E SUPER ADMIN: BROADCAST RESMI PADA SCANNER PETUGAS --}}
    @if(isset($activeBroadcasts) && $activeBroadcasts->isNotEmpty())
        <div class="space-y-2.5">
            @foreach($activeBroadcasts as $bc)
                @php
                    $style = match($bc->urgency) {
                        'danger' => 'bg-red-600 text-white',
                        'warning' => 'bg-amber-500 text-slate-950',
                        'success' => 'bg-emerald-600 text-white',
                        default => 'bg-blue-600 text-white',
                    };
                    $icon = match($bc->urgency) {
                        'danger' => '🚨',
                        'warning' => '⚠️',
                        'success' => '🎉',
                        default => '📢',
                    };
                @endphp
                <div class="rounded-2xl p-4 shadow-md flex items-start justify-between gap-3 {{ $style }}" x-data="{ dismissed: false }" x-show="!dismissed">
                    <div class="flex items-start gap-3">
                        <span class="text-xl shrink-0 mt-0.5">{{ $icon }}</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-xs uppercase tracking-wide">{{ $bc->title }}</h4>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-black/20 uppercase tracking-widest">
                                    Instruksi Super Admin
                                </span>
                            </div>
                            <p class="text-xs mt-1 leading-relaxed opacity-95">{{ $bc->message }}</p>
                        </div>
                    </div>
                    <button type="button" @click="dismissed = true" class="opacity-75 hover:opacity-100 p-1 text-sm font-bold shrink-0">✕</button>
                </div>
            @endforeach
        </div>
    @endif

    {{-- FITUR D: WIDGET RINGKASAN SHIFT KERJA PETUGAS --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-sm">
            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Shift Aktif</span>
            <div class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-1.5">
                <span>⏱️ {{ $shiftMetrics['shift_start'] ?? now()->format('H:i') }} WIB</span>
            </div>
            <span class="text-[10px] text-emerald-600 font-semibold mt-0.5 block">● Gerbang Aktif</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-sm">
            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Tiket Dipindai</span>
            <div class="text-sm sm:text-base font-extrabold text-slate-800">
                🎟️ {{ number_format($shiftMetrics['today_scans'] ?? 0, 0, ',', '.') }} Tiket
            </div>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Shift hari ini</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-sm">
            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Pengunjung Masuk</span>
            <div class="text-sm sm:text-base font-extrabold text-slate-800">
                👥 {{ number_format($shiftMetrics['today_pax'] ?? 0, 0, ',', '.') }} Orang
            </div>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Total pax dilayani</span>
        </div>

        {{-- FITUR C: KONEKSI & OFFLINE SYNC STATUS --}}
        <div class="bg-white rounded-2xl border p-3.5 shadow-sm"
             :class="isOnline ? 'border-emerald-100 bg-emerald-50/20' : 'border-amber-200 bg-amber-50/50'">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold text-slate-400">Mode Jaringan</span>
                <span class="w-2 h-2 rounded-full" :class="isOnline ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
            </div>
            <div class="text-xs font-bold mt-0.5" :class="isOnline ? 'text-emerald-700' : 'text-amber-700'">
                <span x-text="isOnline ? '🌐 Online' : '⚡ Mode Offline'"></span>
            </div>
            <div class="mt-1 flex items-center justify-between text-[10px]">
                <span class="text-slate-500 font-semibold" x-text="offlineQueue.length + ' antrean sync'"></span>
                <button type="button" @click="syncOfflineQueue()" x-show="offlineQueue.length > 0 && isOnline"
                        class="text-blue-600 font-bold hover:underline">
                    Sinkron 🔄
                </button>
            </div>
        </div>
    </div>

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold mb-1.5">
                📷 Gate Masuk • Pemindai QR Cerdas
            </span>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800">
                Pindai QR Tiket Pengunjung
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Arahkan kamera ke layar ponsel atau tiket fisik pengunjung. Mendukung validasi offline otomatis dan rombongan bertahap.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button type="button" @click="refreshOfflineCache()"
                    title="Perbarui cache tiket offline hari ini"
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition flex items-center gap-1.5 shadow-2xs">
                <span>🔄</span>
                <span>Cache Offline</span>
            </button>
            <a href="{{ route('petugas.validasi') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-slate-900 text-xs font-bold text-white hover:bg-slate-800 transition shadow-sm">
                Validasi Manual
            </a>
        </div>
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

    {{-- FITUR A: MANIFES PROGRES ROMBONGAN (JIKA ADA DARI SERVER) --}}
    @if(session('scan_manifest_progress'))
        @php $prog = session('scan_manifest_progress'); @endphp
        <div class="bg-white border border-emerald-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <span>👥 Manifes Rombongan:</span>
                        <span class="text-emerald-700">{{ $prog['nama_penanggung_jawab'] }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Progres kehadiran: <strong>{{ $prog['scanned_pax'] }}</strong> dari <strong>{{ $prog['jumlah_anggota'] }}</strong> anggota rombongan.
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $prog['is_fully_used'] ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $prog['is_fully_used'] ? '✓ Lengkap Masuk' : '⏳ Masuk Sebagian' }}
                </span>
            </div>

            {{-- PROGRESS BAR --}}
            @php $pct = round(($prog['scanned_pax'] / max(1, $prog['jumlah_anggota'])) * 100); @endphp
            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                <div class="bg-emerald-600 h-full transition-all duration-500 rounded-full" style="width: {{ $pct }}%"></div>
            </div>

            <div class="bg-slate-50 rounded-xl border border-slate-100 p-3 max-h-48 overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach($prog['daftar_nama'] as $index => $nama)
                        @php $hasEntered = is_array($prog['scanned_manifest']) && in_array($nama, $prog['scanned_manifest']); @endphp
                        <div class="flex items-center justify-between p-2 rounded-lg {{ $hasEntered ? 'bg-emerald-50/70 border border-emerald-200 text-emerald-900 font-semibold' : 'bg-white border border-slate-200 text-slate-600' }}">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold {{ $hasEntered ? 'text-emerald-600' : 'text-slate-400' }}">{{ $index + 1 }}.</span>
                                <span>{{ $nama }}</span>
                            </div>
                            <span class="text-[10px] font-bold {{ $hasEntered ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $hasEntered ? '✓ Masuk' : 'Belum' }}
                            </span>
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
                        <h2 class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            <span>Kamera Pemindai QR</span>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold"
                                  :class="isOnline ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                                  x-text="isOnline ? 'Online' : 'Offline Ready'"></span>
                        </h2>
                        <p class="text-[10px] text-slate-400">
                            Arahkan kamera ke QR code tiket pengunjung.
                        </p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                <div class="p-3 bg-slate-900">
                    <div id="reader" class="w-full rounded-xl overflow-hidden text-white"></div>
                </div>
            </div>
        </div>

        {{-- SCAN RESULT, MANIFEST CHECKLIST & GATE NOTES --}}
        <div class="lg:col-span-5 space-y-4">

            {{-- LIVE SCAN RESULT --}}
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
                    ✓ QR Berhasil Dibaca!
                </div>
            </div>

            {{-- FORM VALIDASI & GATE NOTES (FITUR E) --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5">
                <form id="qr-form"
                      action="{{ route('petugas.qrcodes.validate') }}"
                      method="POST"
                      class="space-y-3.5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">
                            Kode Tiket / Invoice
                        </label>
                        <input type="text"
                               name="invoice_code"
                               id="qr_code"
                               x-model="scannedCode"
                               required
                               placeholder="Menunggu pemindaian..."
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-50 font-mono text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                    </div>

                    {{-- FITUR E: TAG INSIDEN & CATATAN GATE --}}
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
                        <label class="block text-[11px] font-bold text-slate-700">
                            📝 Catatan Gate & Tag Insiden (Opsional)
                        </label>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="tag in ['⭐ VIP', '♿ Kursi Roda / Difabel', '🎒 Barang Tertinggal', '🔄 Kompensasi', '⚠️ Kendala Gate']" :key="tag">
                                <button type="button" @click="selectedTag = (selectedTag === tag ? '' : tag)"
                                        :class="selectedTag === tag ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100'"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-semibold transition"
                                        x-text="tag">
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="gate_incident_tag" :value="selectedTag">
                        <textarea name="gate_notes" rows="2" placeholder="Catatan singkat gate (cth: Pengunjung butuh pendamping kursi roda)"
                                  class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-emerald-600"></textarea>
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
function offlineScannerApp() {
    return {
        isOnline: navigator.onLine,
        scannedCode: '',
        selectedTag: '',
        offlineTickets: [],
        offlineQueue: JSON.parse(localStorage.getItem('itihasa_offline_queue') || '[]'),

        init() {
            window.addEventListener('online', () => {
                this.isOnline = true;
                this.syncOfflineQueue();
            });
            window.addEventListener('offline', () => {
                this.isOnline = false;
            });

            // Load initial offline tickets cache
            this.refreshOfflineCache();
        },

        async refreshOfflineCache() {
            if (!this.isOnline) {
                this.offlineTickets = JSON.parse(localStorage.getItem('itihasa_offline_tickets') || '[]');
                return;
            }

            try {
                const res = await fetch("{{ route('petugas.scan.offline-cache') }}");
                if (res.ok) {
                    const data = await res.json();
                    this.offlineTickets = data.tickets || [];
                    localStorage.setItem('itihasa_offline_tickets', JSON.stringify(this.offlineTickets));
                }
            } catch (e) {
                this.offlineTickets = JSON.parse(localStorage.getItem('itihasa_offline_tickets') || '[]');
            }
        },

        async syncOfflineQueue() {
            if (this.offlineQueue.length === 0 || !this.isOnline) return;

            try {
                const res = await fetch("{{ route('petugas.scan.sync-offline') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ items: this.offlineQueue }),
                });

                if (res.ok) {
                    const data = await res.json();
                    alert(data.message || 'Antrean offline berhasil disinkronkan!');
                    this.offlineQueue = [];
                    localStorage.removeItem('itihasa_offline_queue');
                    this.refreshOfflineCache();
                }
            } catch (e) {
                console.error('Gagal sinkron offline:', e);
            }
        }
    }
}

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

    const codeInput = document.getElementById('qr_code');
    if (codeInput) {
        codeInput.value = decodedText;
        codeInput.dispatchEvent(new Event('input'));
    }

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

    // Jika offline: simpan ke antrean lokal dan beri feedback
    if (!navigator.onLine) {
        let queue = JSON.parse(localStorage.getItem('itihasa_offline_queue') || '[]');
        queue.push({
            invoice_code: decodedText,
            scanned_at: new Date().toISOString(),
        });
        localStorage.setItem('itihasa_offline_queue', JSON.stringify(queue));
        alert('⚡ Mode Offline: Tiket ' + decodedText + ' berhasil divalidasi lokal dan disimpan ke antrean sinkronisasi.');
        window.location.reload();
        return;
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
