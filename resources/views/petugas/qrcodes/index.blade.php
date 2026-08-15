@extends('layouts.petugas')

@section('title', 'Data QR Code Tiket')

@section('content')
<div class="space-y-4 sm:space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold mb-1.5">
                🛡 Gate System • Tiket {{ Auth::user()?->museum?->name ?? 'Museum' }}
            </span>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800">
                Katalog Tiket & QR Code
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Daftar tiket pengunjung yang terdaftar untuk <strong>{{ Auth::user()?->museum?->name ?? 'museum ini' }}</strong>.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('petugas.validasi') }}"
               class="px-3.5 py-2 rounded-xl border border-emerald-200 text-emerald-700 text-xs font-bold hover:bg-emerald-50 transition shadow-sm">
                Validasi Manual
            </a>

            <a href="{{ route('petugas.qrcodes.scan') }}"
               class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>Buka Kamera Scan</span>
            </a>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Tiket Terbit</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">{{ number_format($totalQr) }}</h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Seluruh tiket museum</p>
        </div>

        <div class="bg-white rounded-xl border border-emerald-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-emerald-600 uppercase">Tiket Aktif / Belum Digunakan</p>
            <h2 class="text-2xl font-extrabold text-emerald-700 mt-1">{{ number_format($activeQr) }}</h2>
            <p class="text-[10px] text-emerald-500 mt-0.5">Siap discan di gate</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-500 uppercase">Sudah Digunakan</p>
            <h2 class="text-2xl font-extrabold text-slate-700 mt-1">{{ number_format($usedQr) }}</h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Telah diverifikasi petugas</p>
        </div>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-3.5 sm:p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form method="GET" action="{{ route('petugas.qrcodes.index') }}" class="flex items-center gap-2 flex-1 max-w-md">
            <div class="relative w-full">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode invoice atau nama pemesan..."
                       class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600 font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <button type="submit" class="px-3.5 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition shrink-0">
                Cari
            </button>
        </form>

        <div class="flex items-center gap-1.5 overflow-x-auto text-xs">
            <a href="{{ route('petugas.qrcodes.index', array_filter(['search' => request('search')])) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition whitespace-nowrap {{ !request('status') ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Status
            </a>
            <a href="{{ route('petugas.qrcodes.index', array_filter(['status' => 'active', 'search' => request('search')])) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition whitespace-nowrap {{ request('status') === 'active' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Aktif
            </a>
            <a href="{{ route('petugas.qrcodes.index', array_filter(['status' => 'used', 'search' => request('search')])) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition whitespace-nowrap {{ request('status') === 'used' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Sudah Dipakai
            </a>
        </div>
    </div>

    {{-- TABEL & DAFTAR TIKET --}}
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-100 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Pengunjung</th>
                        <th class="px-4 py-3">Invoice & Tgl Kunjungan</th>
                        <th class="px-4 py-3">Rincian Tiket</th>
                        <th class="px-4 py-3">Status QR</th>
                        <th class="px-4 py-3">Waktu Digunakan</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($transactions as $i => $transaction)
                    <tr class="hover:bg-emerald-50/30 transition">
                        <td class="px-4 py-3 font-semibold text-slate-400">
                            {{ $transactions->firstItem() ? $transactions->firstItem() + $i : $i + 1 }}
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-slate-800">{{ $transaction->booking->user->name ?? 'Pengunjung' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $transaction->booking->user->email ?? '-' }}</p>
                            @if($transaction->booking->is_rombongan)
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-semibold">
                                    Rombongan ({{ $transaction->booking->jumlah_anggota }} orang)
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-mono">
                            <span class="font-bold text-slate-800">{{ $transaction->invoice_code }}</span>
                            <p class="text-[11px] font-sans text-slate-400 mt-0.5">
                                Kunjungan: {{ $transaction->booking->visit_date ? \Carbon\Carbon::parse($transaction->booking->visit_date)->translatedFormat('d M Y') : '-' }}
                            </p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="space-y-0.5">
                                @foreach($transaction->booking->ticket_items ?? [] as $item)
                                    <p class="text-[11px]">
                                        <span class="font-medium text-slate-800">{{ $item['ticket_name'] ?? 'Tiket' }}</span>
                                        <span class="text-slate-400">x{{ $item['qty'] ?? 1 }}</span>
                                    </p>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($transaction->used_at)
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                                    ✓ Sudah Dipakai
                                </span>
                            @elseif($transaction->payment_status !== 'paid')
                                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                                    ● Belum Lunas
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-200">
                                    ● Siap Digunakan
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-500 font-mono text-[11px]">
                            {{ $transaction->used_at?->format('d M Y, H:i') ? $transaction->used_at->format('d M Y, H:i') . ' WIB' : '-' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($transaction->payment_status === 'paid')
                                <a href="{{ route('petugas.qrcodes.show', $transaction->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-xs transition">
                                    <span>Lihat QR</span>
                                </a>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                            Tidak ditemukan data tiket QR Code untuk museum ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
