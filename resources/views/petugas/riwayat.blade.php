@extends('layouts.petugas')

@section('title', 'Riwayat Scan')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
        <p class="text-sm font-semibold text-blue-600 mb-2">Activity Log</p>
        <h1 class="text-2xl font-bold text-slate-800">Riwayat Scan</h1>
        <p class="text-slate-500 mt-2">Log aktivitas tiket yang sudah discan, diurutkan dari yang paling baru.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">Total Scan</p>
            <h2 class="text-3xl font-bold text-slate-800">{{ $totalScan }}</h2>
        </div>
        <div class="bg-blue-600 rounded-3xl p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">Scan Hari Ini</p>
            <h2 class="text-3xl font-bold">{{ $todayScan }}</h2>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-blue-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-blue-50">
            <h2 class="text-lg font-bold text-slate-800">Log Aktivitas Scan</h2>
            <p class="text-sm text-slate-400">Hanya menampilkan tiket yang sudah divalidasi/discan.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[800px] w-full">
                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pengunjung</th>
                        <th class="px-6 py-4">Museum</th>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Waktu Scan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @forelse($scans as $i => $scan)
                    <tr class="hover:bg-blue-50/40">
                        <td class="px-6 py-4">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $scan->booking->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $scan->booking->museum->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono">
                            {{ $scan->invoice_code }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $scan->used_at->format('d M Y, H:i') }} WIB
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-slate-400">
                            Belum ada aktivitas scan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
