@extends('layouts.petugas')

@section('title', 'Riwayat Scan Tiket')

@section('content')
<div class="space-y-4 sm:space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold mb-1.5">
            🛡 Log Aktivitas • Riwayat Verifikasi
        </span>
        <h1 class="text-lg sm:text-xl font-bold text-slate-800">
            Riwayat Scan & Log Validasi
        </h1>
        <p class="text-xs text-slate-400 mt-0.5">
            Catatan seluruh aktivitas pemindaian tiket gate masuk (berhasil maupun ditolak sistem).
        </p>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Scan</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalScan }}</h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Semua percobaan</p>
        </div>

        <div class="bg-white rounded-xl border border-emerald-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-emerald-600 uppercase">Hari Ini</p>
            <h2 class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $todayScan }}</h2>
            <p class="text-[10px] text-emerald-500 mt-0.5">Scan gate hari ini</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-teal-600 uppercase">Berhasil</p>
            <h2 class="text-2xl font-extrabold text-teal-700 mt-1">{{ $successScan }}</h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Tiket valid & masuk</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-red-600 uppercase">Gagal / Ditolak</p>
            <h2 class="text-2xl font-extrabold text-red-700 mt-1">{{ $failedScan }}</h2>
            <p class="text-[10px] text-slate-400 mt-0.5">QR invalid/expired</p>
        </div>
    </div>

    {{-- FILTER BUTTONS --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-3 sm:p-4 shadow-sm flex flex-wrap items-center gap-2">
        <span class="text-xs font-semibold text-slate-500 mr-2">Filter status:</span>

        <a href="{{ route('petugas.riwayat') }}"
           class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ !request('status') ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            Semua Log
        </a>
        <a href="{{ route('petugas.riwayat', ['status' => 'success']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'success' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            ✓ Berhasil
        </a>
        <a href="{{ route('petugas.riwayat', ['status' => 'failed']) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'failed' ? 'bg-red-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            ✕ Gagal
        </a>
    </div>

    {{-- LOG TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-800">Daftar Aktivitas Scan</h2>
            <p class="text-xs text-slate-400 mt-0.5">Diurutkan berdasarkan waktu pemindaian terbaru.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Pengunjung</th>
                        <th class="px-4 py-3">Kode Input</th>
                        <th class="px-4 py-3">Keterangan / Pesan</th>
                        <th class="px-4 py-3">Petugas</th>
                        <th class="px-4 py-3 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($scans as $scan)
                        <tr class="hover:bg-slate-50/70 transition align-middle">
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($scan->status === 'success')
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        ✓ Berhasil
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-100 text-red-700">
                                        ✕ Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-800">
                                {{ $scan->transaction->booking->user->name ?? '-' }}
                                <span class="block text-[10px] text-slate-400 font-normal">
                                    {{ $scan->transaction->booking->museum->name ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-[11px] text-slate-600">
                                {{ $scan->qr_code_input }}
                            </td>
                            <td class="px-4 py-3 text-slate-500 max-w-xs truncate">
                                {{ $scan->message }}
                            </td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                {{ $scan->petugas->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right text-slate-400 text-[11px] whitespace-nowrap">
                                {{ $scan->scanned_at ? $scan->scanned_at->format('d M, H:i') . ' WIB' : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-xs text-slate-400">
                                Belum ada riwayat aktivitas scan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($scans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $scans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
