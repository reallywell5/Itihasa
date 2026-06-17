@extends('layouts.petugas')

@section('title', 'Profil Petugas')

@section('content')
<div class="space-y-6">

    {{-- HERO PROFILE --}}
    <div class="bg-white rounded-3xl border border-blue-100 shadow-sm overflow-hidden">

        <div class="relative h-52 bg-gradient-to-r from-blue-700 via-blue-600 to-blue-400">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-8 left-10 w-32 h-32 rounded-full bg-white blur-2xl"></div>
                <div class="absolute bottom-6 right-16 w-40 h-40 rounded-full bg-white blur-3xl"></div>
            </div>
        </div>

        <div class="px-6 pb-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 -mt-16 relative z-10">

                <div class="flex flex-col sm:flex-row sm:items-end gap-5">
                    <div class="w-32 h-32 rounded-3xl bg-white p-2 shadow-lg">
                        <div class="w-full h-full rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-5xl font-extrabold">
                            P
                        </div>
                    </div>

                    <div class="pb-2">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">
                                Online
                            </span>

                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">
                                Petugas Aktif
                            </span>
                        </div>

                        <h1 class="text-3xl font-bold text-slate-800">
                            Petugas Museum
                        </h1>

                        <p class="text-sm text-slate-500 mt-1">
                            Staff validasi tiket dan QR Code Museum Nasional
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="px-5 py-3 rounded-2xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                        Edit Profil
                    </button>

                    <button class="px-5 py-3 rounded-2xl bg-blue-50 text-blue-600 text-sm font-semibold hover:bg-blue-100 transition">
                        Ganti Password
                    </button>

                    <button type="submit" class="px-5 py-3 rounded-2xl bg-white border border-blue-100 text-blue-600 text-sm font-semibold hover:bg-blue-50 transition">
                        Logout
                    </button>
                </div>

            </div>
        </div>

    </div>

    {{-- STATISTIC --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 4h5v5H4V4zm11 0h5v5h-5V4zM4 15h5v5H4v-5zm11 0h2v2h-2v-2z"/>
                </svg>
            </div>

            <p class="text-sm text-slate-400 font-semibold">
                Total Scan QR
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                1.250
            </h2>

            <p class="text-sm text-blue-600 font-semibold mt-2">
                Semua aktivitas
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <p class="text-sm text-slate-400 font-semibold">
                Tiket Valid
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                1.100
            </h2>

            <p class="text-sm text-blue-600 font-semibold mt-2">
                Berhasil diverifikasi
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M18.364 18.364A9 9 0 115.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>

            <p class="text-sm text-slate-400 font-semibold">
                Tiket Ditolak
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                150
            </h2>

            <p class="text-sm text-blue-600 font-semibold mt-2">
                QR sudah digunakan
            </p>
        </div>

        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0"/>
                </svg>
            </div>

            <p class="text-sm text-blue-100">
                Pengunjung Dilayani
            </p>

            <h2 class="text-3xl font-bold mt-2">
                980
            </h2>

            <p class="text-sm text-blue-100 mt-2">
                Total visitor
            </p>
        </div>

    </div>

    {{-- PROFILE DETAIL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT DETAIL --}}
        <div class="lg:col-span-2 bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        Informasi Profil
                    </h2>

                    <p class="text-sm text-slate-400">
                        Data pribadi dan informasi akun petugas.
                    </p>
                </div>

                <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-xs font-semibold border border-blue-100">
                    Verified
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">
                        Nama Lengkap
                    </p>
                    <h3 class="font-bold text-slate-800">
                        Petugas Museum
                    </h3>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">
                        Email
                    </p>
                    <h3 class="font-bold text-slate-800">
                        petugas@gmail.com
                    </h3>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">
                        Nomor Telepon
                    </p>
                    <h3 class="font-bold text-slate-800">
                        0812-3456-7890
                    </h3>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">
                        ID Petugas
                    </p>
                    <h3 class="font-bold text-slate-800">
                        PTG-00001
                    </h3>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">
                        Museum Bertugas
                    </p>
                    <h3 class="font-bold text-slate-800">
                        Museum Nasional
                    </h3>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">
                        Bergabung Sejak
                    </p>
                    <h3 class="font-bold text-slate-800">
                        12 Juni 2026
                    </h3>
                </div>

            </div>
        </div>

        {{-- RIGHT SHIFT --}}
        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800 mb-1">
                Shift Hari Ini
            </h2>

            <p class="text-sm text-slate-400 mb-6">
                Jadwal tugas operasional.
            </p>

            <div class="bg-blue-600 rounded-3xl p-5 text-white mb-5">
                <p class="text-sm text-blue-100">
                    Shift Pagi
                </p>

                <h3 class="text-2xl font-bold mt-2">
                    08.00 - 16.00
                </h3>

                <p class="text-sm text-blue-100 mt-2">
                    Museum Nasional
                </p>
            </div>

            <div class="space-y-4">

                <div class="flex justify-between border-b border-blue-50 pb-3">
                    <span class="text-sm text-slate-400">Status</span>
                    <span class="text-sm font-bold text-blue-600">Sedang Bertugas</span>
                </div>

                <div class="flex justify-between border-b border-blue-50 pb-3">
                    <span class="text-sm text-slate-400">Lokasi</span>
                    <span class="text-sm font-bold text-slate-800">Lobby Utama</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-sm text-slate-400">Role</span>
                    <span class="text-sm font-bold text-slate-800">Petugas</span>
                </div>

            </div>
        </div>

    </div>

    {{-- RECENT ACTIVITY --}}
    <div class="bg-white rounded-3xl border border-blue-100 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50">
            <h2 class="text-lg font-bold text-slate-800">
                Aktivitas Terakhir
            </h2>

            <p class="text-sm text-slate-400">
                Aktivitas validasi tiket yang baru dilakukan.
            </p>
        </div>

        <div class="p-6 space-y-5">

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    ✓
                </div>

                <div class="flex-1">
                    <div class="flex justify-between gap-4">
                        <h3 class="text-sm font-bold text-slate-800">
                            QR Code QRC-0001 berhasil divalidasi
                        </h3>

                        <span class="text-xs text-slate-400">
                            09:10 WIB
                        </span>
                    </div>

                    <p class="text-sm text-slate-400 mt-1">
                        Tiket milik Budi Santoso berhasil digunakan untuk masuk museum.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    ✓
                </div>

                <div class="flex-1">
                    <div class="flex justify-between gap-4">
                        <h3 class="text-sm font-bold text-slate-800">
                            QR Code QRC-0002 berhasil divalidasi
                        </h3>

                        <span class="text-xs text-slate-400">
                            09:30 WIB
                        </span>
                    </div>

                    <p class="text-sm text-slate-400 mt-1">
                        Pengunjung Andi Wijaya telah berhasil masuk.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    !
                </div>

                <div class="flex-1">
                    <div class="flex justify-between gap-4">
                        <h3 class="text-sm font-bold text-slate-800">
                            QR Code QRC-0003 sudah digunakan
                        </h3>

                        <span class="text-xs text-slate-400">
                            10:15 WIB
                        </span>
                    </div>

                    <p class="text-sm text-slate-400 mt-1">
                        Sistem menolak QR Code karena status tiket sudah digunakan.
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
