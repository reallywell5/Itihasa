@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Transaction Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Riwayat Transaksi
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Kelola seluruh data transaksi tiket museum dari pengunjung.
            </p>
        </div>

        <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center">
            💳
        </div>

    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 mb-2">Total Transaksi</p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ method_exists($transactions, 'total') ? $transactions->total() : $transactions->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 mb-2">Total Pendapatan</p>
            <h2 class="text-3xl font-bold text-slate-800">
                Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">Transaksi Berhasil</p>
            <h2 class="text-2xl font-bold">
                {{ $transactions->where('payment_status', 'paid')->count() }}
            </h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Transaksi
                </h2>

                <p class="text-sm text-slate-400">
                    Menampilkan seluruh transaksi tiket museum.
                </p>
            </div>

            <form method="GET" class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari transaksi..."
                       class="bg-transparent outline-none text-sm w-full text-slate-600">
            </form>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Pengunjung</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse ($transactions as $transaction)

                    <tr class="hover:bg-blue-50/30 transition">

                        {{-- INVOICE --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">

                                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                    TR
                                </div>

                                <div>
                                    <p class="font-bold text-slate-800">
                                        {{ $transaction->invoice_code ?? 'TRX-'.$transaction->id }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        ID #{{ $transaction->id }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        {{-- USER --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->user->name ?? 'Guest' }}
                        </td>

                        {{-- TOTAL --}}
                        <td class="px-6 py-4 font-bold text-slate-800">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </td>

                        {{-- DATE --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->created_at->format('d M Y H:i') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">

                            @if($transaction->payment_status == 'paid')
                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-xs font-semibold">
                                    Paid
                                </span>

                            @elseif($transaction->payment_status == 'pending')
                                <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-600 text-xs font-semibold">
                                    Pending
                                </span>

                            @else
                                <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-semibold">
                                    Failed
                                </span>
                            @endif

                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('transactions.show', $transaction->id) }}"
                               class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold hover:bg-blue-600 hover:text-white transition">
                                Detail
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            Belum ada transaksi.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- PAGINATION --}}
    @if(method_exists($transactions, 'links'))
        <div>
            {{ $transactions->links() }}
        </div>
    @endif

</div>
@endsection
