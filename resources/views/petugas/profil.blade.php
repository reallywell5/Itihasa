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
                            {{ strtoupper(substr($petugas->name, 0, 1)) }}
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
                            {{ $petugas->name }}
                        </h1>

                        <p class="text-sm text-slate-500 mt-1">
                            Staff validasi tiket dan QR Code
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

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-5 py-3 rounded-2xl bg-white border border-blue-100 text-blue-600 text-sm font-semibold hover:bg-blue-50 transition">
                            Logout
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>

    {{-- STATISTIC --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400 font-semibold">Total Scan QR</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $totalScan }}
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                Semua aktivitas
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400 font-semibold">Tiket Valid</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $validTickets }}
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                Berhasil diverifikasi
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">
            <p class="text-sm text-slate-400 font-semibold">Tiket Ditolak</p>
            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $rejectedTickets }}
            </h2>
            <p class="text-sm text-blue-600 font-semibold mt-2">
                QR sudah digunakan
            </p>
        </div>

        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-sm">
            <p class="text-sm text-blue-100">Pengunjung Dilayani</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $totalVisitors }}
            </h2>
            <p class="text-sm text-blue-100 mt-2">
                Total visitor
            </p>
        </div>

    </div>

    {{-- PROFILE DETAIL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

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
                    <p class="text-xs text-slate-400 font-semibold mb-1">Nama Lengkap</p>
                    <h3 class="font-bold text-slate-800">{{ $petugas->name }}</h3>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">Email</p>
                    <h3 class="font-bold text-slate-800">{{ $petugas->email }}</h3>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">ID Petugas</p>
                    <h3 class="font-bold text-slate-800">
                        PTG-{{ str_pad($petugas->id, 5, '0', STR_PAD_LEFT) }}
                    </h3>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <p class="text-xs text-slate-400 font-semibold mb-1">Bergabung Sejak</p>
                    <h3 class="font-bold text-slate-800">
                        {{ $petugas->created_at->format('d F Y') }}
                    </h3>
                </div>

            </div>
        </div>

        {{-- SHIFT --}}
        <div class="bg-white rounded-3xl border border-blue-100 p-6 shadow-sm">

            <h2 class="text-lg font-bold text-slate-800 mb-1">
                Shift Hari Ini
            </h2>

            <p class="text-sm text-slate-400 mb-6">
                Jadwal tugas operasional.
            </p>

            <div class="bg-blue-600 rounded-3xl p-5 text-white mb-5">
                <p class="text-sm text-blue-100">Shift Pagi</p>

                <h3 class="text-2xl font-bold mt-2">
                    08.00 - 16.00
                </h3>

                <p class="text-sm text-blue-100 mt-2">
                    Museum Nasional
                </p>
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
                Aktivitas validasi tiket terbaru.
            </p>
        </div>

        <div class="p-6 space-y-5">

            @forelse($recentActivities as $activity)

            <div class="flex items-start gap-4">

                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    ✓
                </div>

                <div class="flex-1">

                    <div class="flex justify-between gap-4">
                        <h3 class="text-sm font-bold text-slate-800">
                            QR Code {{ $activity->invoice_code }} berhasil divalidasi
                        </h3>

                        <span class="text-xs text-slate-400">
                            {{ $activity->used_at ? $activity->used_at->format('H:i') : '-' }} WIB
                        </span>
                    </div>

                    <p class="text-sm text-slate-400 mt-1">
                        Tiket milik {{ $activity->booking->user->name }} berhasil digunakan.
                    </p>

                </div>

            </div>

            @empty

            <p class="text-slate-400 text-sm">
                Belum ada aktivitas.
            </p>

            @endforelse

        </div>

    </div>

</div>
@endsection
