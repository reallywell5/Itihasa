@extends('layouts.petugas')

@section('title', 'Scan QR Tiket')

@section('content')
<style>
    /* Hilangkan efek mirror pada tampilan video scanner kamera */
    #reader video,
    #reader__scan_region video,
    div[id^="reader"] video {
        transform: scaleX(1) !important;
        -webkit-transform: scaleX(1) !important;
        object-fit: cover !important;
    }
    #reader {
        border: none !important;
    }
    #reader__camera_selection {
        font-size: 11px !important;
        padding: 4px 8px !important;
        border-radius: 8px !important;
        margin-bottom: 8px !important;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
    }
    #reader__dashboard_section_csr button {
        font-size: 11px !important;
        padding: 5px 12px !important;
        border-radius: 8px !important;
        background: #059669 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        border: none !important;
        cursor: pointer;
    }
</style>

<div class="max-w-5xl mx-auto space-y-4 sm:space-y-5"
     x-data="offlineScannerApp()"
     @qr-scanned.window="handleQrScanned($event.detail)">

    {{-- TOAST NOTIFIKASI INTERAKTIF --}}
    <div x-show="toast.show"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="fixed top-5 right-5 z-50 max-w-sm rounded-2xl shadow-xl p-4 border text-xs"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-red-50 border-red-200 text-red-900'">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h4 class="font-bold text-xs" x-text="toast.title"></h4>
                <p class="mt-0.5 leading-relaxed" x-text="toast.message"></p>
            </div>
            <button type="button" @click="toast.show = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>
    </div>

    {{-- BROADCAST RESMI SUPER ADMIN --}}
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
                @endphp
                <div class="rounded-2xl p-4 shadow-md flex items-start justify-between gap-3 {{ $style }}" x-data="{ dismissed: false }" x-show="!dismissed">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-black/20 uppercase tracking-widest">
                                Instruksi Resmi
                            </span>
                            <h4 class="font-bold text-xs uppercase">{{ $bc->title }}</h4>
                        </div>
                        <p class="text-xs leading-relaxed opacity-95">{{ $bc->message }}</p>
                    </div>
                    <button type="button" @click="dismissed = true" class="opacity-75 hover:opacity-100 p-1 text-sm font-bold shrink-0">✕</button>
                </div>
            @endforeach
        </div>
    @endif

    {{-- WIDGET RINGKASAN SHIFT KERJA PETUGAS (FITUR D) --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-sm">
            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Shift Aktif</span>
            <div class="text-sm sm:text-base font-bold text-slate-800">
                Pukul {{ $shiftMetrics['shift_start'] ?? now()->format('H:i') }} WIB
            </div>
            <span class="text-[10px] text-emerald-600 font-semibold mt-0.5 block">Gerbang Masuk Siap</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-sm">
            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Tiket Dipindai</span>
            <div class="text-sm sm:text-base font-extrabold text-slate-800" x-text="todayScansCount + ' Tiket'">
                {{ number_format($shiftMetrics['today_scans'] ?? 0, 0, ',', '.') }} Tiket
            </div>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Shift hari ini</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-sm">
            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Pengunjung Masuk</span>
            <div class="text-sm sm:text-base font-extrabold text-slate-800" x-text="todayPaxCount + ' Orang'">
                {{ number_format($shiftMetrics['today_pax'] ?? 0, 0, ',', '.') }} Orang
            </div>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Total pax dilayani</span>
        </div>

        {{-- STATUS KONEKSI & OFFLINE QUEUE (FITUR C) --}}
        <div class="bg-white rounded-2xl border p-3.5 shadow-sm"
             :class="isOnline ? 'border-emerald-100 bg-emerald-50/20' : 'border-amber-200 bg-amber-50/50'">
            <div class="flex items-center justify-between">
                <span class="text-[10px] uppercase font-bold text-slate-400">Mode Jaringan</span>
                <span class="w-2 h-2 rounded-full" :class="isOnline ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
            </div>
            <div class="text-xs font-bold mt-0.5" :class="isOnline ? 'text-emerald-700' : 'text-amber-700'">
                <span x-text="isOnline ? 'Online (Terkoneksi)' : 'Mode Offline Siap'"></span>
            </div>
            <div class="mt-1 flex items-center justify-between text-[10px]">
                <span class="text-slate-500 font-semibold" x-text="offlineQueue.length + ' antrean sync'"></span>
                <button type="button" @click="syncOfflineQueue()" x-show="offlineQueue.length > 0 && isOnline"
                        class="text-blue-600 font-bold hover:underline">
                    Sinkronkan
                </button>
            </div>
        </div>
    </div>

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800">
                Pindai QR Tiket Pengunjung
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Kamera digunakan untuk membaca kode tiket secara otomatis. Tekan tombol "Validasi Tiket Sekarang" untuk memverifikasi.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button type="button" @click="refreshOfflineCache()"
                    title="Perbarui cache tiket offline hari ini"
                    class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition shadow-sm">
                Perbarui Cache
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
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-semibold">
            {{ session('error') }}
        </div>
    @endif

    {{-- HASIL MANIFES PROGRES ROMBONGAN SETELAH SUBMIT --}}
    @if(session('scan_manifest_progress'))
        @php $prog = session('scan_manifest_progress'); @endphp
        <div class="bg-white border border-emerald-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <span>Manifes Rombongan:</span>
                        <span class="text-emerald-700">{{ $prog['nama_penanggung_jawab'] }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Progres kehadiran: <strong>{{ $prog['scanned_pax'] }}</strong> dari <strong>{{ $prog['jumlah_anggota'] }}</strong> anggota rombongan.
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $prog['is_fully_used'] ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $prog['is_fully_used'] ? 'Lengkap Masuk' : 'Masuk Sebagian' }}
                </span>
            </div>

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
                                {{ $hasEntered ? 'Sudah Masuk' : 'Belum' }}
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
                            Arahkan kamera ke QR code tiket pengunjung untuk membaca kode.
                        </p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                <div class="p-3 bg-slate-900">
                    <div id="reader" class="w-full rounded-xl overflow-hidden text-white"></div>
                </div>
            </div>
        </div>

        {{-- SCAN RESULT & GATE NOTES --}}
        <div class="lg:col-span-5 space-y-4">

            {{-- LIVE SCAN RESULT --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between mb-2.5">
                    <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Hasil Pemindaian
                    </h2>
                    <button type="button"
                            x-show="scannedCode"
                            x-cloak
                            @click="resetScannedCode()"
                            class="text-[11px] font-bold text-slate-400 hover:text-red-600 transition">
                        Reset / Pindai Ulang
                    </button>
                </div>

                <div id="result" class="mb-3">
                    <div x-show="!scannedCode" class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-center">
                        <p class="text-xs text-slate-400">
                            Menunggu QR Code diarahkan ke kamera...
                        </p>
                    </div>

                    <div x-show="scannedCode" x-cloak class="w-full bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-emerald-800 text-xs">
                                QR Code Berhasil Terbaca
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-600 text-white">
                                Siap Validasi
                            </span>
                        </div>
                        <p class="font-mono text-xs font-bold text-slate-800 break-all bg-white px-2.5 py-1.5 rounded-lg border border-emerald-200"
                           x-text="scannedCode"></p>

                        <template x-if="matchedTicket">
                            <div class="text-[11px] text-slate-600 pt-1.5 border-t border-emerald-200/60 flex items-center justify-between">
                                <span>Pengunjung: <strong class="text-slate-800" x-text="matchedTicket.customer_name"></strong></span>
                                <span class="font-bold text-emerald-700" x-text="matchedTicket.is_rombongan ? ('Rombongan (' + matchedTicket.jumlah_anggota + ' Orang)') : 'Tiket Perorangan'"></span>
                            </div>
                        </template>

                        <p class="text-[10px] text-emerald-700 font-medium">
                            Kode tiket telah terbaca. Silakan klik tombol <strong>"Validasi Tiket Sekarang"</strong> di bawah untuk memproses izin masuk.
                        </p>
                    </div>
                </div>
            </div>

            {{-- FORM VALIDASI & GATE NOTES (FITUR E) --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5">
                <form id="qr-form"
                      action="{{ route('petugas.qrcodes.validate') }}"
                      method="POST"
                      @submit.prevent="submitValidationForm()"
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
                               placeholder="Menunggu pemindaian dari kamera..."
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-50 font-mono text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                    </div>

                    {{-- KONTROL PAX ROMBONGAN (REPRESENTATIF / 1 NAMA PERWAKILAN) --}}
                    <template x-if="selectedRombongan && (!selectedRombongan.manifest || selectedRombongan.manifest.length === 0)">
                        <div class="p-3.5 bg-blue-50/70 border border-blue-200 rounded-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-blue-950">
                                    Rombongan: <span x-text="selectedRombongan.customer_name"></span>
                                </span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800"
                                      x-text="'Total: ' + selectedRombongan.jumlah_anggota + ' Orang'"></span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-blue-900 pt-1">
                                <span>Jumlah Orang Masuk:</span>
                                <div class="flex items-center gap-2">
                                    <input type="number"
                                           name="pax_entered"
                                           x-model.number="paxEntering"
                                           min="1"
                                           :max="Math.max(1, selectedRombongan.jumlah_anggota - (selectedRombongan.scanned_pax || 0))"
                                           class="w-20 px-2.5 py-1 text-center font-bold text-xs rounded-lg border border-blue-300 bg-white">
                                    <button type="button"
                                            @click="paxEntering = Math.max(1, selectedRombongan.jumlah_anggota - (selectedRombongan.scanned_pax || 0))"
                                            class="text-[11px] font-bold text-blue-700 underline">
                                        Semua
                                    </button>
                                </div>
                            </div>
                            <p class="text-[10px] text-blue-700" x-show="(selectedRombongan.scanned_pax || 0) > 0">
                                Sudah masuk sebelumnya: <strong x-text="selectedRombongan.scanned_pax"></strong> orang.
                            </p>
                        </div>
                    </template>

                    {{-- CHECKLIST MANIFES ROMBONGAN JIKA TERDETEKSI --}}
                    <template x-if="selectedRombongan && selectedRombongan.manifest && selectedRombongan.manifest.length > 0">
                        <div class="p-3.5 bg-blue-50/70 border border-blue-200 rounded-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-blue-950">
                                    Manifes Rombongan: <span x-text="selectedRombongan.customer_name"></span>
                                </span>
                                <button type="button"
                                        @click="toggleSelectAllMembers()"
                                        class="text-blue-700 font-bold hover:underline text-[11px]">
                                    Pilih Semua
                                </button>
                            </div>
                            <p class="text-[11px] text-blue-800">
                                Centang anggota rombongan yang saat ini hadir di pintu masuk:
                            </p>
                            <div class="max-h-48 overflow-y-auto space-y-1 bg-white p-2 rounded-lg border border-blue-100">
                                <template x-for="(name, idx) in selectedRombongan.manifest" :key="idx">
                                    <label class="flex items-center justify-between p-1.5 rounded text-xs cursor-pointer hover:bg-slate-50">
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox"
                                                   :value="name"
                                                   x-model="selectedMembers"
                                                   :disabled="(selectedRombongan.scanned_manifest || []).includes(name)"
                                                   class="rounded text-emerald-600 focus:ring-emerald-500">
                                            <span x-text="(idx + 1) + '. ' + name"
                                                  :class="(selectedRombongan.scanned_manifest || []).includes(name) ? 'line-through text-slate-400' : 'text-slate-700 font-medium'"></span>
                                        </div>
                                        <span x-show="(selectedRombongan.scanned_manifest || []).includes(name)"
                                              class="text-[10px] font-bold text-emerald-600">
                                            Sudah Masuk
                                        </span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- FITUR E: TAG INSIDEN & CATATAN GATE --}}
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
                        <label class="block text-[11px] font-bold text-slate-700">
                            Catatan Gate & Label Khusus (Opsional)
                        </label>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="tag in ['VIP', 'Kursi Roda / Difabel', 'Barang Tertinggal', 'Kompensasi Tiket', 'Kendala Gate']" :key="tag">
                                <button type="button" @click="selectedTag = (selectedTag === tag ? '' : tag)"
                                        :class="selectedTag === tag ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100'"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-semibold transition"
                                        x-text="tag">
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="gate_incident_tag" :value="selectedTag">
                        <textarea name="gate_notes" rows="2"
                                  x-model="gateNotes"
                                  placeholder="Catatan singkat gate (cth: Pengunjung butuh pendamping kursi roda)"
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
        gateNotes: '',
        matchedTicket: null,
        offlineTickets: [],
        offlineQueue: JSON.parse(localStorage.getItem('itihasa_offline_queue') || '[]'),
        todayScansCount: {{ $shiftMetrics['today_scans'] ?? 0 }},
        todayPaxCount: {{ $shiftMetrics['today_pax'] ?? 0 }},

        // State Manifes Rombongan
        selectedRombongan: null,
        selectedMembers: [],
        paxEntering: 1,

        // Toast feedback
        toast: { show: false, type: 'success', title: '', message: '' },

        showToast(type, title, message) {
            this.toast = { show: true, type, title, message };
            setTimeout(() => { this.toast.show = false; }, 4000);
        },

        init() {
            window.addEventListener('online', () => {
                this.isOnline = true;
                this.syncOfflineQueue();
            });
            window.addEventListener('offline', () => {
                this.isOnline = false;
            });

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

        // Kamera HANYA membaca kode tiket dan memasukkannya ke form (TIDAK langsung memvalidasi)
        handleQrScanned(code) {
            this.scannedCode = code;

            const codeInput = document.getElementById('qr_code');
            if (codeInput) {
                codeInput.value = code;
                codeInput.dispatchEvent(new Event('input'));
            }

            // Cari info tiket di database cache jika ada
            const cached = this.offlineTickets.find(t => t.invoice_code === code);
            this.matchedTicket = cached || null;

            if (cached && cached.is_rombongan) {
                this.selectedRombongan = cached;
                const total = cached.jumlah_anggota || 1;
                const alreadyIn = cached.scanned_pax || 0;
                this.paxEntering = Math.max(1, total - alreadyIn);
                if (Array.isArray(cached.manifest) && cached.manifest.length > 0) {
                    const alreadyNames = cached.scanned_manifest || [];
                    this.selectedMembers = cached.manifest.filter(m => !alreadyNames.includes(m));
                } else {
                    this.selectedMembers = [];
                }
            } else {
                this.selectedRombongan = null;
                this.selectedMembers = [];
                this.paxEntering = 1;
            }

            // Beri jeda 1 detik agar scanner siap membaca kode baru jika diperlukan
            setTimeout(() => {
                isProcessing = false;
            }, 1000);
        },

        resetScannedCode() {
            this.scannedCode = '';
            this.matchedTicket = null;
            this.selectedRombongan = null;
            this.selectedMembers = [];
            const codeInput = document.getElementById('qr_code');
            if (codeInput) codeInput.value = '';
            isProcessing = false;
        },

        toggleSelectAllMembers() {
            if (!this.selectedRombongan || !this.selectedRombongan.manifest) return;
            const alreadyIn = this.selectedRombongan.scanned_manifest || [];
            const available = this.selectedRombongan.manifest.filter(m => !alreadyIn.includes(m));

            if (this.selectedMembers.length === available.length) {
                this.selectedMembers = [];
            } else {
                this.selectedMembers = [...available];
            }
        },

        // Eksekusi validasi HANYA saat petugas menekan tombol "Validasi Tiket Sekarang"
        submitValidationForm() {
            const code = trimString(this.scannedCode);
            if (!code) {
                alert('Silakan arahkan kamera ke QR code tiket atau masukkan kode invoice terlebih dahulu.');
                return;
            }

            // KASUS 1: OFFLINE
            if (!this.isOnline) {
                const cached = this.offlineTickets.find(t => t.invoice_code === code);
                if (!cached) {
                    this.showToast('error', 'Validasi Offline Ditolak', 'Tiket ' + code + ' tidak ditemukan di database cache offline hari ini.');
                    playErrorBeep();
                    return;
                }

                if (cached.is_fully_used) {
                    this.showToast('error', 'Tiket Sudah Dipakai', 'Tiket ' + code + ' sudah selesai digunakan sebelumnya (Pukul ' + (cached.used_at || 'hari ini') + ').');
                    playErrorBeep();
                    return;
                }

                // Cek manifes rombongan offline
                let checkedNames = [];
                if (cached.is_rombongan && Array.isArray(cached.manifest) && cached.manifest.length > 0) {
                    if (this.selectedMembers.length === 0) {
                        alert('Pilih minimal 1 anggota rombongan yang saat ini hadir.');
                        return;
                    }
                    checkedNames = this.selectedMembers;
                } else {
                    checkedNames = [cached.customer_name];
                }

                this.confirmOfflineScan(cached, checkedNames);
                return;
            }

            // KASUS 2: ONLINE
            if (this.selectedRombongan && this.selectedMembers.length === 0) {
                alert('Pilih minimal 1 anggota rombongan yang saat ini hadir.');
                return;
            }

            const form = document.getElementById('qr-form');
            form.querySelectorAll('input[name="manifest_checked[]"]').forEach(el => el.remove());

            if (this.selectedRombongan) {
                this.selectedMembers.forEach(name => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'manifest_checked[]';
                    hiddenInput.value = name;
                    form.appendChild(hiddenInput);
                });
            }

            form.submit();
        },

        confirmOfflineScan(ticket, checkedNames) {
            const nowTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
            const existingScanned = ticket.scanned_manifest || [];
            const mergedManifest = Array.from(new Set([...existingScanned, ...checkedNames]));
            const isFull = mergedManifest.length >= (ticket.jumlah_anggota || 1);

            ticket.scanned_manifest = mergedManifest;
            ticket.scanned_pax = mergedManifest.length;
            ticket.is_fully_used = isFull;
            ticket.used_at = isFull ? nowTime : null;

            localStorage.setItem('itihasa_offline_tickets', JSON.stringify(this.offlineTickets));

            this.offlineQueue.push({
                invoice_code: ticket.invoice_code,
                scanned_at: new Date().toISOString(),
                manifest_checked: checkedNames,
                gate_incident_tag: this.selectedTag || null,
                gate_notes: this.gateNotes || null,
            });
            localStorage.setItem('itihasa_offline_queue', JSON.stringify(this.offlineQueue));

            this.todayScansCount++;
            this.todayPaxCount += checkedNames.length;

            this.showToast('success', 'Validasi Offline Berhasil', 'Tiket ' + ticket.invoice_code + ' (' + checkedNames.length + ' orang) tersimpan di antrean sinkronisasi.');

            this.resetScannedCode();
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
                    this.showToast('success', 'Sinkronisasi Berhasil', data.message || 'Antrean offline berhasil disinkronkan ke server.');
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

function trimString(str) {
    return (str || '').trim();
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
        osc.stop(ctx.currentTime + 0.15);
    } catch (e) {}
}

function playErrorBeep() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sawtooth';
        osc.frequency.setValueAtTime(300, ctx.currentTime);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.35);
    } catch (e) {}
}

function onScanSuccess(decodedText) {
    if (isProcessing) return;
    isProcessing = true;
    playBeep();

    window.dispatchEvent(new CustomEvent('qr-scanned', { detail: decodedText }));
}

let scanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: {
            width: 250,
            height: 250
        },
        videoConstraints: {
            facingMode: { ideal: "environment" }
        },
        rememberLastUsedCamera: true
    }
);

// Pastikan elemen video tidak terkena efek cermin / mirror dari inline style library
const readerContainer = document.getElementById('reader');
if (readerContainer) {
    const mirrorGuardObserver = new MutationObserver(() => {
        readerContainer.querySelectorAll('video').forEach(video => {
            if (video.style.transform && video.style.transform.includes('-1')) {
                video.style.transform = 'scaleX(1)';
            }
        });
    });
    mirrorGuardObserver.observe(readerContainer, { childList: true, subtree: true, attributes: true, attributeFilter: ['style'] });
}

scanner.render(onScanSuccess);
</script>
@endsection
