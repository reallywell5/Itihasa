@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="space-y-4 sm:space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold mb-1.5">
                🛡 Petugas Lapangan • Loket & Verifikasi
            </span>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800">
                Dashboard Operasional Petugas
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                Pantau aktivitas scan QR tiket masuk, verifikasi pengunjung, dan riwayat validasi harian.
            </p>
        </div>

        <a href="{{ route('petugas.qrcodes.scan') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow hover:bg-emerald-700 transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            <span>Buka Kamera Scanner</span>
        </a>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Tamu Hari Ini</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ $todayVisitors }}
            </h2>
            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Tervalidasi hari ini</p>
        </div>

        <div class="bg-white rounded-xl border border-emerald-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-emerald-600 uppercase">QR Valid</p>
            <h2 class="text-2xl font-extrabold text-emerald-700 mt-1">
                {{ $validQr }}
            </h2>
            <p class="text-[10px] text-emerald-500 font-medium mt-0.5">Siap digunakan</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-amber-600 uppercase">Tiket Pending</p>
            <h2 class="text-2xl font-extrabold text-amber-700 mt-1">
                {{ $pendingTickets }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Menunggu pembayaran</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-xl p-4 text-white shadow-sm flex flex-col justify-between">
            <p class="text-[11px] font-bold text-emerald-100 uppercase">Status Scanner</p>
            <div>
                <h2 class="text-xl font-extrabold flex items-center gap-1.5 mt-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-300 animate-pulse inline-block"></span>
                    Online
                </h2>
                <p class="text-[10px] text-emerald-100 mt-0.5">Sistem gate aktif</p>
            </div>
        </div>
    </div>

    {{-- RECENT ACTIVITY TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-800">
                    Aktivitas Validasi Terbaru
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    Riwayat pemindaian tiket pada gate masuk museum.
                </p>
            </div>
            <a href="{{ route('petugas.riwayat') }}" class="text-xs text-emerald-600 hover:underline font-semibold">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Pengunjung</th>
                        <th class="px-4 py-3">Museum</th>
                        <th class="px-4 py-3">Kode Invoice</th>
                        <th class="px-4 py-3">Waktu Masuk</th>
                        <th class="px-4 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentTransactions as $transaction)
                        <tr class="hover:bg-slate-50/70 transition align-middle">
                            <td class="px-4 py-3 font-bold text-slate-800">
                                {{ $transaction->booking->user->name ?? 'Pengunjung' }}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $transaction->booking->museum->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 font-mono text-[11px] text-slate-600">
                                {{ $transaction->invoice_code }}
                            </td>
                            <td class="px-4 py-3 text-slate-500 whitespace-nowrap">
                                {{ $transaction->used_at ? $transaction->used_at->format('H:i') . ' WIB' : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                @if($transaction->used_at)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold">
                                        Sudah Digunakan
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-bold">
                                        Tiket Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-xs text-slate-400">
                                Belum ada aktivitas validasi tiket hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
