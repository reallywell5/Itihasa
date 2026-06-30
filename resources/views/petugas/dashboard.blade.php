@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Staff Dashboard
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Dashboard Petugas
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Pantau aktivitas validasi tiket, scan QR Code, dan pengunjung museum hari ini.
            </p>
        </div>

        <a href="{{ route('petugas.qrcodes.index') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition">
            Buka QR Code
        </a>
    </div>

    {{-- STATISTICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Pengunjung Hari Ini
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ $todayVisitors }}
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                Data masuk hari ini
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                QR Valid
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ $validQr }}
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                Sudah diverifikasi
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Tiket Pending
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ $pendingTickets }}
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                Belum digunakan
            </p>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">
                Status Sistem
            </p>
            <h2 class="text-2xl font-bold">
                Online
            </h2>
            <p class="text-sm text-blue-100 mt-2">
                Scanner siap digunakan
            </p>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50">
            <h2 class="text-lg font-bold text-slate-800">
                Aktivitas Validasi Terbaru
            </h2>

            <p class="text-sm text-slate-400">
                Riwayat scan tiket terbaru.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">Pengunjung</th>
                        <th class="px-6 py-4">Museum</th>
                        <th class="px-6 py-4">Kode Tiket</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse($recentTransactions as $transaction)

                    <tr class="hover:bg-blue-50/40">

                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $transaction->booking->user->name }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->booking->museum->name }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->invoice_code }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->created_at->format('H:i') }} WIB
                        </td>

                        <td class="px-6 py-4">
                            @if($transaction->used_at)
                                <span class="px-3 py-1.5 rounded-xl bg-gray-100 text-gray-600 text-xs font-semibold border border-gray-200">
                                    Used
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-green-50 text-green-600 text-xs font-semibold border border-green-100">
                                    Valid
                                </span>
                            @endif
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-slate-400">
                            Belum ada aktivitas validasi tiket.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection
