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
            <h2 class="text-3xl font-bold text-slate-800 mt-2">156</h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Scan Valid</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">140</h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">QR Used</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">16</h2>
        </div>

        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-sm">
            <p class="text-blue-100">Status Riwayat</p>
            <h2 class="text-2xl font-bold mt-2">Tersimpan</h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl border border-blue-100 overflow-hidden shadow-sm">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Riwayat Scan
                </h2>

                <p class="text-sm text-slate-400">
                    Aktivitas scan tiket terbaru.
                </p>
            </div>

            <div class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">
                <svg class="w-4 h-4 text-blue-600 mr-2"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                </svg>

                <input type="text"
                       placeholder="Search riwayat..."
                       class="bg-transparent outline-none text-sm w-full text-slate-600">
            </div>

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

                    <tr class="hover:bg-blue-50/40">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            Petugas 01
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            Budi Santoso
                        </td>

                        <td class="px-6 py-4 font-mono text-slate-500">
                            QRC-0001
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            Museum Nasional
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            09:45 WIB
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                Valid
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-blue-50/40">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            Petugas 01
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            Andi Wijaya
                        </td>

                        <td class="px-6 py-4 font-mono text-slate-500">
                            QRC-0002
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            Museum Nasional
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            10:10 WIB
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                Used
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-blue-50/40">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            Petugas 02
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            Siti Aulia
                        </td>

                        <td class="px-6 py-4 font-mono text-slate-500">
                            QRC-0003
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            Museum Sejarah
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            11:25 WIB
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                Valid
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>

</div>
@endsection
