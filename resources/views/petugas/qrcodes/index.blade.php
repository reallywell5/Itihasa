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

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-white border border-blue-100 rounded-2xl p-4 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                ✓
            </div>
            <p class="text-sm font-semibold text-slate-700">
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-white border border-blue-100 rounded-2xl p-4 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                !
            </div>
            <p class="text-sm font-semibold text-slate-700">
                {{ session('error') }}
            </p>
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Total QR Code
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                {{ $qrCodes->count() }}
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                Data QR tercatat
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Status Validasi
            </p>
            <h2 class="text-3xl font-bold text-slate-800">
                Aktif
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                QR Code siap dipindai
            </p>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">
                Quick Action
            </p>
            <h2 class="text-2xl font-bold">
                Generate QR
            </h2>
            <p class="text-sm text-blue-100 mt-2">
                Buat QR Code tiket pengunjung.
            </p>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar QR Code
                </h2>

                <p class="text-sm text-slate-400">
                    Menampilkan seluruh data QR Code tiket pengunjung.
                </p>
            </div>

            <div class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">
                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                </svg>

                <input type="text"
                       placeholder="Search QR Code..."
                       class="bg-transparent outline-none text-sm w-full text-slate-600">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Transaction Detail</th>
                        <th class="px-6 py-4">QR Code</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Scanned At</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">
                    @forelse ($qrCodes as $qr)
                        <tr class="hover:bg-blue-50/40 transition">

                            <td class="px-6 py-4 text-slate-500 font-semibold">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                        QR
                                    </div>

                                    <div>
                                        <p class="font-bold text-slate-800">
                                            Transaction #{{ $qr->transaction_detail_id }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-0.5">
                                            QRC-{{ str_pad($qr->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex max-w-xs truncate px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-mono font-semibold border border-blue-100">
                                    {{ $qr->qr_code }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if($qr->scan_status == 'used')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        Used
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        Valid
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $qr->scanned_at ? $qr->scanned_at->format('d M Y H:i') : '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('petugas.qrcodes.show', $qr->id) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                   title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/>
                                    </svg>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-center mx-auto text-blue-600 shadow-sm mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M4 4h5v5H4V4zm11 0h5v5h-5V4zM4 15h5v5H4v-5zm11 0h2v2h-2v-2zm4 4h1v1h-1v-1z"/>
                                    </svg>
                                </div>

                                <h3 class="text-base font-bold text-slate-800">
                                    Belum Ada Data QR Code
                                </h3>

                                <p class="text-sm text-slate-400 mt-1">
                                    QR Code tiket pengunjung akan tampil di halaman ini.
                                </p>

                                <a href="{{ route('petugas.qrcodes.create') }}"
                                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition mt-5">
                                    Generate QR
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
