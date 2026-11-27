@extends('layouts.app')

@section('title', auth()->user()->isSuperAdmin() ? 'Pemantauan Transaksi' : 'Riwayat Transaksi')

@section('content')
<div x-data="{ openPosModal: false, selectedTicketPrice: 0, qty: 1 }" class="max-w-7xl mx-auto space-y-5">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs">
            <p class="font-bold mb-1">Terjadi kesalahan validasi:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border {{ auth()->user()->isSuperAdmin() ? 'border-purple-100' : 'border-blue-100' }} p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                Riwayat Transaksi Pemesanan
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                {{ auth()->user()->isSuperAdmin() ? 'Memantau seluruh transaksi pembelian tiket online maupun walk-in di semua museum se-Indonesia.' : 'Pantau riwayat pemesanan tiket pengunjung, status pembayaran, dan invoice transaksi untuk museum Anda.' }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if(!auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.transactions.rekap') }}"
                   class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-sm transition">
                    Rekap Kas Harian
                </a>
                @if(isset($availableTickets) && $availableTickets->isNotEmpty())
                    <button type="button"
                            @click="openPosModal = true"
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Penjualan Tiket Loket (POS)</span>
                    </button>
                @endif
            @endif
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Pesanan Transaksi</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ method_exists($transactions, 'total') ? $transactions->total() : $transactions->count() }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Semua status pemesanan</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Pesanan Lunas (Paid)</p>
            <h2 class="text-2xl font-extrabold text-emerald-600 mt-1">
                {{ $totalPaidTransactions }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Tiket aktif / siap scan</p>
        </div>

        <div class="{{ auth()->user()->isSuperAdmin() ? 'bg-purple-600' : 'bg-blue-600' }} rounded-xl p-4 text-white shadow-sm flex flex-col justify-between">
            <p class="text-[11px] font-semibold {{ auth()->user()->isSuperAdmin() ? 'text-purple-200' : 'text-blue-100' }} uppercase">Total Nilai Pendapatan</p>
            <h2 class="text-2xl font-extrabold mt-1">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </h2>
            <p class="text-[10px] {{ auth()->user()->isSuperAdmin() ? 'text-purple-200' : 'text-blue-100' }} mt-0.5">Akumulasi transaksi berhasil</p>
        </div>
    </div>

    {{-- SEARCH & TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-4 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-xs text-slate-500 font-medium">
                Daftar Transaksi Tiket
            </div>

            <form method="GET" class="flex items-center gap-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode invoice atau nama..."
                       class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-slate-800">
                @if(request('search'))
                    <a href="{{ route('transactions.index') }}" class="px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs text-slate-500 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Kode Invoice</th>
                        <th class="px-4 py-3">Pengunjung & Museum</th>
                        <th class="px-4 py-3 text-right">Total Bayar</th>
                        <th class="px-4 py-3">Waktu Pemesanan</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-slate-50/70 transition align-middle">
                            <td class="px-4 py-3 font-bold text-slate-800 whitespace-nowrap">
                                {{ $transaction->invoice_code ?? ('#TRX-' . $transaction->id) }}
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-800">
                                    {{ $transaction->booking->nama_penanggung_jawab ?? ($transaction->booking->user->name ?? 'Tamu') }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    {{ $transaction->booking->museum->name ?? '-' }}
                                </p>
                                @if($transaction->gate_incident_tag || $transaction->gate_notes)
                                    <div class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-200">
                                        <span>{{ $transaction->gate_incident_tag ?? 'Catatan Gate' }}</span>
                                    </div>
                                @endif
                                @if(($transaction->booking->is_rombongan ?? false) && ($transaction->scanned_pax > 0))
                                    <div class="mt-1 text-[10px] font-semibold text-emerald-700">
                                        {{ $transaction->scanned_pax }} / {{ $transaction->booking->jumlah_anggota }} Orang Masuk
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-extrabold text-slate-800 whitespace-nowrap">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-slate-400 text-[11px] whitespace-nowrap">
                                {{ $transaction->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @php
                                    $badge = match($transaction->payment_status) {
                                        'paid' => 'bg-emerald-50 text-emerald-700',
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        default => 'bg-red-50 text-red-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $badge }}">
                                    {{ $transaction->payment_status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('transactions.show', $transaction->id) }}"
                                   class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-xs text-slate-400">
                                Belum ada data transaksi tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($transactions, 'links'))
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL POS WALK-IN CASHIER --}}
    @if(isset($availableTickets) && $availableTickets->isNotEmpty())
        <div x-show="openPosModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openPosModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="openPosModal = false"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="openPosModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">

                    <form action="{{ route('admin.transactions.pos') }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                <div>
                                    <h3 class="text-base font-bold text-slate-800" id="modal-title">
                                        Input Transaksi Loket (Walk-In POS)
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        Pencatatan langsung penjualan tiket di loket fisik museum
                                    </p>
                                </div>
                                <button type="button" @click="openPosModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="space-y-4 mt-4">
                                {{-- PILIH TIKET --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Kategori Tiket <span class="text-red-500">*</span>
                                    </label>
                                    <select name="ticket_id"
                                            required
                                            x-on:change="selectedTicketPrice = parseFloat($event.target.selectedOptions[0]?.dataset?.price || 0)"
                                            class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                                        <option value="">-- Pilih Tiket Tersedia --</option>
                                        @foreach($availableTickets as $ticket)
                                            <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                                {{ $ticket->name }} - Rp {{ number_format($ticket->price, 0, ',', '.') }} (Sisa Slot: {{ $ticket->slot }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- JUMLAH TIKET & METODE PEMBAYARAN --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Jumlah Tiket (Pax) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number"
                                               name="qty"
                                               min="1"
                                               max="500"
                                               x-model.number="qty"
                                               required
                                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Metode Bayar <span class="text-red-500">*</span>
                                        </label>
                                        <select name="payment_method"
                                                required
                                                class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                            <option value="tunai">Tunai / Uang Kas Loket</option>
                                            <option value="qris">QRIS Statis Loket</option>
                                            <option value="debit">Kartu Debit / EDC</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- KALKULASI TOTAL BAYAR --}}
                                <div class="rounded-xl bg-blue-50/70 border border-blue-100 p-3.5 flex items-center justify-between">
                                    <div>
                                        <span class="text-[11px] font-semibold text-blue-900 block">Total Tagihan Loket</span>
                                        <span class="text-[10px] text-blue-600 block">Kalkulasi otomatis sistem</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-base font-extrabold text-blue-700">
                                            Rp <span x-text="((selectedTicketPrice || 0) * (qty || 1)).toLocaleString('id-ID')">0</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- NAMA PENGUNJUNG --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Nama Pengunjung / Pembeli <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           name="visitor_name"
                                           placeholder="Misal: Bapak Hendra / Pengunjung Loket"
                                           required
                                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                {{-- KONTAK TELEPON --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Nomor WhatsApp / HP (Opsional)
                                    </label>
                                    <input type="text"
                                           name="visitor_phone"
                                           placeholder="08xxxxxxxxxx"
                                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                {{-- CATATAN KASIR --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Catatan Kasir (Opsional)
                                    </label>
                                    <textarea name="notes"
                                              rows="2"
                                              placeholder="Catatan tambahan kasir loket..."
                                              class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button"
                                    @click="openPosModal = false"
                                    class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                                Terbitkan Tiket & Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
