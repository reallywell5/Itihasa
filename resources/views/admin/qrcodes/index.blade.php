@extends('layouts.app')

@section('title', 'QR Codes')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">Data QR Code</h1>
            <p class="text-sm text-zinc-500 mt-1">Kelola QR Code tiket pengunjung.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('qrcodes.scan') }}"
               class="px-4 py-2 rounded-lg border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
                Scan QR
            </a>

            <a href="{{ route('qrcodes.create') }}"
               class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-700">
                Generate QR
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-100 text-green-700 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-lg bg-red-100 text-red-700 text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 border-b border-zinc-200">
                <tr class="text-left text-zinc-500">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Transaction Detail</th>
                    <th class="px-6 py-4">QR Code</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Scanned At</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-zinc-100">
                @forelse ($qrCodes as $qr)
                    <tr class="hover:bg-zinc-50 transition">
                        <td class="px-6 py-4 text-zinc-600">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-zinc-900">
                            #{{ $qr->transaction_detail_id }}
                        </td>

                        <td class="px-6 py-4 font-mono text-zinc-700">
                            {{ $qr->qr_code }}
                        </td>

                        <td class="px-6 py-4">
                            @if($qr->scan_status == 'used')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                    Used
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    Valid
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-zinc-600">
                            {{ $qr->scanned_at ? $qr->scanned_at->format('d M Y H:i') : '-' }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('qrcodes.show', $qr->id) }}"
                               class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-semibold hover:bg-zinc-700">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-zinc-500">
                            Belum ada data QR Code.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
