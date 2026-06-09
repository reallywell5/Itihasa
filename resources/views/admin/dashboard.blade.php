@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 text-slate-800">

    <!-- Header -->
    <div class="rounded-3xl bg-slate-900 p-7 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-slate-400">Dashboard Admin</p>

                <h1 class="mt-2 text-2xl md:text-3xl font-bold tracking-tight">
                    Selamat Datang di Itihasa
                </h1>

                <p class="mt-2 max-w-2xl text-sm text-slate-300">
                    Pantau data museum, pengunjung, transaksi, dan pendapatan secara ringkas.
                </p>
            </div>

            <div class="w-fit rounded-xl bg-white/10 px-4 py-2 text-sm text-slate-200">
                {{ now()->format('d M Y') }}
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total Museum</p>
            <h3 class="mt-3 text-3xl font-bold text-slate-900">{{ $totalMuseums }}</h3>
            <p class="mt-2 text-xs text-slate-400">Museum aktif terdaftar</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total Pengunjung</p>
            <h3 class="mt-3 text-3xl font-bold text-slate-900">{{ $totalUsers ?? 0 }}</h3>
            <p class="mt-2 text-xs text-slate-400">Akun pengunjung terdaftar</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total Transaksi</p>
            <h3 class="mt-3 text-3xl font-bold text-slate-900">{{ $totalTransactions }}</h3>
            <p class="mt-2 text-xs text-slate-400">Transaksi berhasil masuk</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total Pendapatan</p>
            <h3 class="mt-3 text-2xl font-bold text-slate-900">
                Rp {{ number_format($totalRevenue) }}
            </h3>
            <p class="mt-2 text-xs text-slate-400">Dari pembayaran sukses</p>
        </div>

    </div>

    <!-- Recent Transactions -->
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 p-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Aktivitas pembelian tiket terbaru dari pengunjung.
                </p>
            </div>

            <a href="{{ route('transactions.index') }}"
               class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Pengunjung</th>
                        <th class="px-6 py-4 font-semibold">Nominal</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentTransactions as $transaction)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">
                                    {{ strtoupper(substr($transaction->user?->name ?? 'G', 0, 2)) }}
                                </div>

                                <div>
                                    <p class="font-semibold text-slate-900">
                                        {{ $transaction->user?->name ?? 'Guest' }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $transaction->user?->email ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 font-semibold text-slate-900">
                            Rp {{ number_format($transaction->total_price) }}
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-slate-700">
                                {{ $transaction->created_at?->format('d M Y') ?? '-' }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $transaction->created_at?->format('H:i') ?? '-' }} WIB
                            </p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                {{ $transaction->status == 'paid'
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : 'bg-amber-50 text-amber-700' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-400">
                            Belum ada transaksi masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
