@extends('layouts.app')

@section('title', auth()->user()->isSuperAdmin() ? 'Pemantauan Pembayaran' : 'Riwayat Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border {{ auth()->user()->isSuperAdmin() ? 'border-purple-100' : 'border-blue-100' }} p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if(auth()->user()->isSuperAdmin())
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[11px] font-bold mb-2">
                    👑 Super Admin • Rekap Pembayaran Seluruh Museum
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold mb-2">
                    🏛 Admin • {{ auth()->user()->museum?->name ?? 'Museum' }}
                </span>
            @endif
            <h1 class="text-xl font-bold text-slate-800">
                Data Pembayaran Tiket
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                {{ auth()->user()->isSuperAdmin() ? 'Memonitor aliran kas masuk, gateway pembayaran, dan status penyelesaian transaksi se-Indonesia.' : 'Pantau pembayaran tiket masuk dan status penerimaan dana untuk museum Anda.' }}
            </p>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Pembayaran</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ $payments->count() }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Tercatat di sistem</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Pembayaran Sukses</p>
            <h2 class="text-2xl font-extrabold text-emerald-600 mt-1">
                {{ $payments->where('payment_status', 'paid')->count() }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Berhasil terverifikasi</p>
        </div>

        <div class="{{ auth()->user()->isSuperAdmin() ? 'bg-purple-600' : 'bg-blue-600' }} rounded-xl p-4 text-white shadow-sm flex flex-col justify-between">
            <p class="text-[11px] font-semibold {{ auth()->user()->isSuperAdmin() ? 'text-purple-200' : 'text-blue-100' }} uppercase">Total Dana Masuk</p>
            <h2 class="text-2xl font-extrabold mt-1">
                Rp {{ number_format($payments->where('payment_status','paid')->sum('amount'), 0, ',', '.') }}
            </h2>
            <p class="text-[10px] {{ auth()->user()->isSuperAdmin() ? 'text-purple-200' : 'text-blue-100' }} mt-0.5">Pemasukan riil dari transaksi paid</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Kode Invoice</th>
                        <th class="px-4 py-3">Museum & Pengunjung</th>
                        <th class="px-4 py-3 text-right">Nominal</th>
                        <th class="px-4 py-3 text-center">Metode</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3">Waktu Bayar</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/70 transition align-middle">
                            <td class="px-4 py-3 font-bold text-slate-800 whitespace-nowrap">
                                {{ $payment->transaction?->invoice_code ?? ('#TRX-' . $payment->transaction_id) }}
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-800">
                                    {{ $payment->transaction?->booking?->user?->name ?? 'Tamu' }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    🏛 {{ $payment->transaction?->booking?->museum?->name ?? '-' }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-right font-extrabold text-slate-800 whitespace-nowrap">
                                Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold uppercase">
                                    {{ $payment->payment_method ?: 'QRIS' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @php
                                    $badge = match($payment->payment_status) {
                                        'paid' => 'bg-emerald-50 text-emerald-700',
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        default => 'bg-red-50 text-red-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $badge }}">
                                    {{ $payment->payment_status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-400 text-[11px] whitespace-nowrap">
                                {{ $payment->paid_at ? $payment->paid_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('payments.show', $payment->id) }}"
                                   class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-xs text-slate-400">
                                Belum ada data pembayaran tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
