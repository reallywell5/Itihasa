@extends('layouts.petugas')

@section('title', 'Riwayat Scan')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
        <p class="text-sm font-semibold text-blue-600 mb-2">Activity Log</p>
        <h1 class="text-2xl font-bold text-slate-800">Riwayat Scan</h1>
        <p class="text-slate-500 mt-2">Log seluruh aktivitas scan tiket (berhasil maupun gagal), diurutkan dari yang paling baru.</p>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">Total Scan</p>
            <h2 class="text-3xl font-bold text-slate-800">{{ $totalScan }}</h2>
        </div>
        <div class="bg-blue-600 rounded-3xl p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">Scan Hari Ini</p>
            <h2 class="text-3xl font-bold">{{ $todayScan }}</h2>
        </div>
        <div class="bg-green-50 rounded-3xl border border-green-100 p-6">
            <p class="text-sm text-green-600 font-semibold mb-2">Berhasil</p>
            <h2 class="text-3xl font-bold text-green-700">{{ $successScan }}</h2>
        </div>
        <div class="bg-red-50 rounded-3xl border border-red-100 p-6">
            <p class="text-sm text-red-600 font-semibold mb-2">Gagal</p>
            <h2 class="text-3xl font-bold text-red-700">{{ $failedScan }}</h2>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-3xl border border-blue-100 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-semibold text-slate-500">Filter status:</span>

            <a href="{{ route('petugas.riwayat') }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-600' }}">
                Semua
            </a>
            <a href="{{ route('petugas.riwayat', ['status' => 'success']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('status') === 'success' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-600' }}">
                Berhasil
            </a>
            <a href="{{ route('petugas.riwayat', ['status' => 'failed']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold {{ request('status') === 'failed' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-600' }}">
                Gagal
            </a>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-blue-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-blue-50">
            <h2 class="text-lg font-bold text-slate-800">Log Aktivitas Scan</h2>
            <p class="text-sm text-slate-400">Menampilkan setiap percobaan scan, termasuk yang ditolak sistem.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full">
                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Pengunjung</th>
                        <th class="px-6 py-4">Museum</th>
                        <th class="px-6 py-4">Kode Discan</th>
                        <th class="px-6 py-4">Pesan</th>
                        <th class="px-6 py-4">Petugas</th>
                        <th class="px-6 py-4">Waktu Scan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @forelse($scans as $i => $scan)
                    <tr class="hover:bg-blue-50/40">
                        <td class="px-6 py-4">
                            {{ $scans->firstItem() + $i }}
                        </td>
                        <td class="px-6 py-4">
                            @if($scan->status === 'success')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">
                                    Berhasil
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">
                                    Gagal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $scan->transaction->booking->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $scan->transaction->booking->museum->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono">
                            {{ $scan->qr_code_input }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 max-w-xs">
                            {{ $scan->message }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $scan->petugas->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                            {{ $scan->scanned_at->format('d M Y, H:i') }} WIB
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-slate-400">
                            Belum ada aktivitas scan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($scans->hasPages())
        <div class="px-6 py-4 border-t border-blue-50">
            {{ $scans->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
