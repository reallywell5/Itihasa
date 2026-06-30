@extends('layouts.petugas')

@section('title', 'QR Codes')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                QR Code Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Data QR Code
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Kelola QR Code tiket pengunjung, status validasi, dan data pemindaian tiket.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">

            <a href="{{ route('petugas.qrcodes.scan') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold border border-blue-100 hover:bg-blue-100 transition">
                Scan QR
            </a>

            <a href="{{ route('petugas.qrcodes.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition">
                Generate QR
            </a>

        </div>

    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Total QR Code
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                {{ $qrCodes->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                QR Sudah Digunakan
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                {{ $qrCodes->where('scan_status', 'used')->count() }}
            </h2>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">
                QR Aktif
            </p>

            <h2 class="text-2xl font-bold">
                {{ $qrCodes->where('scan_status', 'pending')->count() }}
            </h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50">
            <h2 class="text-lg font-bold text-slate-800">
                Daftar QR Code
            </h2>

            <p class="text-sm text-slate-400">
                Menampilkan seluruh data QR Code tiket pengunjung.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pengunjung</th>
                        <th class="px-6 py-4">Museum</th>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Scanned At</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse ($qrCodes as $qr)
                    <tr class="hover:bg-blue-50/40 transition">

                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $qr?->transaction?->booking?->user?->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $qr?->transaction?->booking?->museum?->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-xl bg-blue-50 text-blue-600 font-mono text-xs">
                                {{ $qr->qr_code ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @if($qr->scan_status == 'used')
                                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-xl">
                                    Used
                                </span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-xl">
                                    Valid
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $qr->scanned_at ? $qr->scanned_at->format('d M Y H:i') : '-' }}
                        </td>

                    </tr>
                    @empty

                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                            Belum ada QR Code.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>
@endsection
