@extends('layouts.petugas')

@section('title', 'QR Code')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">QR Code Management</p>
            <h1 class="text-2xl font-bold text-slate-800">Data QR Code</h1>
            <p class="text-slate-500 mt-2">Kelola QR Code tiket pengunjung, status validasi, dan data pemindaian tiket.</p>
        </div>

        <a href="{{ route('petugas.qrcodes.scan') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 text-white text-sm font-bold shadow-md hover:bg-blue-700 transition">
            Scan QR
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">Total QR Code</p>
            <h2 class="text-3xl font-bold text-slate-800">{{ $totalQr }}</h2>
        </div>
        <div class="bg-white rounded-3xl border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">QR Sudah Digunakan</p>
            <h2 class="text-3xl font-bold text-slate-800">{{ $usedQr }}</h2>
        </div>
        <div class="bg-blue-600 rounded-3xl p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">QR Aktif</p>
            <h2 class="text-3xl font-bold">{{ $activeQr }}</h2>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-blue-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-blue-50">
            <h2 class="text-lg font-bold text-slate-800">Daftar QR Code</h2>
            <p class="text-sm text-slate-400">Menampilkan seluruh data QR Code tiket pengunjung.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full">
                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pengunjung</th>
                        <th class="px-6 py-4">Museum</th>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Scanned At</th>
                        <th class="px-6 py-4">QR</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @forelse($transactions as $i => $transaction)
                    <tr class="hover:bg-blue-50/40">
                        <td class="px-6 py-4">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $transaction->booking->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->booking->museum->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono">
                            {{ $transaction->invoice_code }}
                        </td>
                        <td class="px-6 py-4">
                            @if($transaction->used_at)
                                <span class="px-3 py-1.5 rounded-xl bg-gray-100 text-gray-600 text-xs font-semibold border border-gray-200">
                                    Sudah Digunakan
                                </span>
                            @elseif($transaction->payment_status !== 'paid')
                                <span class="px-3 py-1.5 rounded-xl bg-yellow-50 text-yellow-600 text-xs font-semibold border border-yellow-100">
                                    Belum Dibayar
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-green-50 text-green-600 text-xs font-semibold border border-green-100">
                                    Belum Dipakai
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $transaction->used_at?->format('d M Y, H:i') ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('petugas.qrcodes.show', $transaction->id) }}"
                               class="text-blue-600 font-semibold hover:underline">
                                Lihat QR
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-slate-400">
                            Belum ada data tiket.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
