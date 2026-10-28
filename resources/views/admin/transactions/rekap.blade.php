@extends('layouts.app')

@section('title', 'Rekap Tutup Kas Harian & Finansial Loket')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER (HIDDEN ON PRINT) --}}
    <div class="print:hidden bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                Rekap Tutup Kas Harian Loket & Digital
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                Rekapitulasi transaksi sukses, audit uang tunai loket vs QRIS/transfer, serta ekspor pembukuan kas harian museum.
            </p>
        </div>

        {{-- DATE FILTER & ACTIONS --}}
        <div class="flex flex-wrap items-center gap-2.5">
            <form method="GET" action="{{ route('admin.transactions.rekap') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date }}" max="{{ date('Y-m-d') }}"
                       class="rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500 font-semibold">
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-sm">
                    Filter Tanggal
                </button>
            </form>

            <a href="{{ route('admin.transactions.export', ['date' => $date]) }}"
               class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <span>Unduh CSV</span>
            </a>

            <button type="button" onclick="window.print()"
                    class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <span>Cetak Rekap</span>
            </button>
        </div>
    </div>

    {{-- PRINT HEADER (ONLY VISIBLE ON PRINT) --}}
    <div class="hidden print:block text-center border-b-2 border-slate-800 pb-4 mb-6">
        <h2 class="text-2xl font-black uppercase tracking-wider">ITIHASA TICKET SYSTEM</h2>
        <h3 class="text-lg font-bold text-slate-700">LAPORAN REKAPITULASI TUTUP KAS HARIAN</h3>
        <p class="text-xs text-slate-500 mt-1">
            Tanggal Operasional: <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</strong> | Waktu Cetak: {{ now()->translatedFormat('d M Y, H:i') }}
        </p>
    </div>

    {{-- SUMMARY KPI CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- TOTAL PENDAPATAN --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                Total Omzet Bersih
            </span>
            <div class="text-lg md:text-xl font-black text-slate-800">
                Rp {{ number_format($totalAmount, 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-slate-400 mt-1 block">
                {{ $transactions->count() }} Transaksi Berhasil
            </span>
        </div>

        {{-- TOTAL KAS LOKET / TUNAI --}}
        <div class="bg-white rounded-2xl border border-emerald-100 bg-gradient-to-br from-white to-emerald-50/40 p-4 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block mb-1">
                Uang Tunai Kas Loket
            </span>
            <div class="text-lg md:text-xl font-black text-emerald-700">
                Rp {{ number_format($totalCash, 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-emerald-600 mt-1 block">
                Fisik Kas yang Harus Disetor
            </span>
        </div>

        {{-- TOTAL DIGITAL / QRIS --}}
        <div class="bg-white rounded-2xl border border-blue-100 bg-gradient-to-br from-white to-blue-50/40 p-4 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 block mb-1">
                Non-Tunai / QRIS / Online
            </span>
            <div class="text-lg md:text-xl font-black text-blue-700">
                Rp {{ number_format($totalDigital, 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-blue-600 mt-1 block">
                Rekening / Payment Gateway
            </span>
        </div>

        {{-- TOTAL PENGUNJUNG --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                👥 Total Pengunjung
            </span>
            <div class="text-lg md:text-xl font-black text-slate-800">
                {{ number_format($totalPax, 0, ',', '.') }} Orang
            </div>
            <span class="text-[10px] text-amber-600 mt-1 block font-semibold">
                Diskon Promo: Rp {{ number_format($totalDiscounts, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- TRANSACTIONS BREAKDOWN TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                Rincian Transaksi {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            </h2>
            <span class="text-xs text-slate-500">
                Total: <strong>{{ $transactions->count() }}</strong> data
            </span>
        </div>

        @if($transactions->isEmpty())
            <div class="text-center py-12 text-xs text-slate-400">
                Tidak ada transaksi lunas pada tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">No. Invoice</th>
                            @if($isSuperAdmin)
                                <th class="px-4 py-3">Museum</th>
                            @endif
                            <th class="px-4 py-3">Pengunjung</th>
                            <th class="px-4 py-3 text-center">Metode</th>
                            <th class="px-4 py-3 text-right">Subtotal</th>
                            <th class="px-4 py-3 text-right">Diskon</th>
                            <th class="px-4 py-3 text-right">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($transactions as $tx)
                            @php
                                $m = strtolower($tx->payment_method ?? '');
                                $isCash = in_array($m, ['cash', 'tunai', 'loket', 'manual']);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 py-3 text-slate-400 whitespace-nowrap">
                                    {{ $tx->created_at?->format('H:i:s') }}
                                </td>
                                <td class="px-4 py-3 font-mono font-bold text-slate-800 whitespace-nowrap">
                                    {{ $tx->invoice_code }}
                                </td>
                                @if($isSuperAdmin)
                                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                        {{ $tx->booking->museum->name ?? '-' }}
                                    </td>
                                @endif
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="font-bold text-slate-800">{{ $tx->booking->nama_penanggung_jawab ?? ($tx->booking->user->name ?? '-') }}</p>
                                    <span class="text-[10px] text-slate-400">{{ $tx->booking->no_hp ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full {{ $isCash ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }} text-[10px] font-bold">
                                        {{ $isCash ? '💵 TUNAI' : '💳 '.strtoupper($tx->payment_method ?? 'DIGITAL') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-slate-600 font-mono whitespace-nowrap">
                                    Rp {{ number_format($tx->subtotal ?? $tx->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right text-amber-600 font-mono whitespace-nowrap">
                                    @if(($tx->booking->discount_amount ?? 0) > 0)
                                        - Rp {{ number_format($tx->booking->discount_amount, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-bold font-mono text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 border-t-2 border-slate-200 font-bold text-slate-900">
                        <tr>
                            <td colspan="{{ $isSuperAdmin ? 5 : 4 }}" class="px-4 py-3 text-right uppercase tracking-wider text-xs">
                                Total Keseluruhan:
                            </td>
                            <td class="px-4 py-3 text-right font-mono">
                                Rp {{ number_format($transactions->sum(fn($t) => $t->subtotal ?? $t->total_amount), 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-amber-700">
                                - Rp {{ number_format($totalDiscounts, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-emerald-700 text-sm">
                                Rp {{ number_format($totalAmount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    {{-- PRINT SIGNATURE FOOTER --}}
    <div class="hidden print:grid grid-cols-2 gap-8 pt-12 mt-8 text-center text-xs">
        <div>
            <p class="text-slate-500 mb-16">Petugas Kasir Loket,</p>
            <p class="font-bold border-t border-slate-400 pt-1 inline-block min-w-[180px]">
                ( {{ auth()->user()->name }} )
            </p>
        </div>
        <div>
            <p class="text-slate-500 mb-16">Penanggung Jawab / Bendahara,</p>
            <p class="font-bold border-t border-slate-400 pt-1 inline-block min-w-[180px]">
                ( .................................................. )
            </p>
        </div>
    </div>

</div>
@endsection
