@extends('layouts.petugas')

@section('title', 'Pengunjung Hari Ini')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">

        <p class="text-sm font-semibold text-blue-600 mb-2">
            Visitor Monitoring
        </p>

        <h1 class="text-2xl font-bold text-slate-800">
            Pengunjung Hari Ini
        </h1>

        <p class="text-slate-500 mt-2">
            Monitoring data pengunjung yang telah melakukan scan tiket.
        </p>

    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Total Pengunjung</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">128</h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Dewasa</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">85</h2>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400">Anak-anak</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">43</h2>
        </div>

        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-sm">
            <p class="text-blue-100">Status</p>
            <h2 class="text-2xl font-bold mt-2">Aktif</h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl border border-blue-100 overflow-hidden shadow-sm">

        <div class="px-6 py-5 border-b border-blue-50 flex justify-between items-center">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Pengunjung
                </h2>

                <p class="text-sm text-slate-400">
                    Pengunjung yang telah berhasil masuk museum.
                </p>
            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-blue-50 text-blue-600 uppercase text-xs font-bold">

                    <tr>
                        <th class="px-6 py-4 text-left">
                            Nama Pengunjung
                        </th>

                        <th class="px-6 py-4 text-left">
                            Kode Tiket
                        </th>

                        <th class="px-6 py-4 text-left">
                            Museum
                        </th>

                        <th class="px-6 py-4 text-left">
                            Jam Masuk
                        </th>

                        <th class="px-6 py-4 text-left">
                            Status
                        </th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-blue-50">

                    <tr class="hover:bg-blue-50/40">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            Budi Santoso
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            TKT-0001
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            Museum Nasional
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            09:45 WIB
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                Sudah Masuk
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-blue-50/40">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            Andi Wijaya
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            TKT-0002
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            Museum Nasional
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            10:10 WIB
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                                Sudah Masuk
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
