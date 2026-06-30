@extends('layouts.petugas')

@section('title', 'Riwayat Scan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
        <p class="text-sm font-semibold text-blue-600 mb-2">
            Scan History
        </p>

        <h1 class="text-2xl font-bold text-slate-800">
            Riwayat Scan
        </h1>

        <p class="text-slate-500 mt-2">
            Menampilkan riwayat validasi QR Code tiket yang dilakukan oleh petugas.
        </p>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Total Scan</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $totalScan }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Scan Valid</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $validScan }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">QR Used</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $usedScan }}
            </h2>
        </div>

        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-sm">
            <p class="text-blue-100">Status Riwayat</p>
            <h2 class="text-2xl font-bold mt-2">Tersimpan</h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl border border-blue-100 overflow-hidden shadow-sm">

        <div class="px-6 py-5 border-b border-blue-50">
            <h2 class="text-lg font-bold text-slate-800">
                Daftar Riwayat Scan
            </h2>

            <p class="text-sm text-slate-400">
                Aktivitas scan tiket terbaru.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-blue-50 text-blue-600 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4 text-left">Petugas</th>
                        <th class="px-6 py-4 text-left">Pengunjung</th>
                        <th class="px-6 py-4 text-left">Kode QR</th>
                        <th class="px-6 py-4 text-left">Museum</th>
                        <th class="px-6 py-4 text-left">Waktu Scan</th>
                        <th class="px-6 py-4 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-blue-50/40">

                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ auth()->user()->name }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->booking->user->name }}
                        </td>

                        <td class="px-6 py-4 font-mono text-slate-500">
                            {{ $transaction->invoice_code }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->booking->museum->name }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->used_at ? $transaction->used_at->format('H:i') : '-' }} WIB
                        </td>

                        <td class="px-6 py-4">
                            @if($transaction->used_at)
                                <span class="px-3 py-1.5 rounded-xl bg-green-50 text-green-600 text-xs font-semibold border border-green-100">
                                    Valid
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-yellow-50 text-yellow-600 text-xs font-semibold border border-yellow-100">
                                    Pending
                                </span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                            Belum ada riwayat scan.
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>
@endsection
