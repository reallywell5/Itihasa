@extends('layouts.app')

@section('title', auth()->user()->isSuperAdmin() ? 'Pemantauan Transaksi' : 'Riwayat Transaksi')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border {{ auth()->user()->isSuperAdmin() ? 'border-purple-100' : 'border-blue-100' }} p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if(auth()->user()->isSuperAdmin())
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[11px] font-bold mb-2">
                    👑 Super Admin • Pemantauan Transaksi Seluruh Museum
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold mb-2">
                    🏛 Admin • {{ auth()->user()->museum?->name ?? 'Museum' }}
                </span>
            @endif
            <h1 class="text-xl font-bold text-slate-800">
                Riwayat Transaksi Pemesanan
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                {{ auth()->user()->isSuperAdmin() ? 'Memantau seluruh transaksi pembelian tiket online maupun walk-in di semua museum se-Indonesia.' : 'Pantau riwayat pemesanan tiket pengunjung, status pembayaran, dan invoice transaksi untuk museum Anda.' }}
            </p>
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
                                    {{ $transaction->booking->user->name ?? 'Tamu' }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    🏛 {{ $transaction->booking->museum->name ?? '-' }}
                                </p>
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

</div>
@endsection
