@extends('layouts.petugas')

@section('title', 'Profil Petugas')

@section('content')
<div class="space-y-4 sm:space-y-5">

    {{-- HERO PROFILE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="relative h-36 sm:h-44 bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-500">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-6 left-10 w-28 h-28 rounded-full bg-white blur-2xl"></div>
                <div class="absolute bottom-4 right-12 w-32 h-32 rounded-full bg-white blur-3xl"></div>
            </div>
        </div>

        <div class="px-4 sm:px-6 pb-5">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 -mt-12 sm:-mt-14 relative z-10">
                <div class="flex flex-col sm:flex-row sm:items-end gap-3 sm:gap-4">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white p-1.5 shadow-md shrink-0">
                        <div class="w-full h-full rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl font-extrabold">
                            {{ strtoupper(substr($petugas->name, 0, 1)) }}
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-100">
                                ● Online
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-100">
                                🛡 Petugas Aktif
                            </span>
                        </div>

                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">
                            {{ $petugas->name }}
                        </h1>

                        <p class="text-xs text-slate-400">
                            {{ $petugas->museum?->name ?? 'Staff Lapangan Museum' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('petugas.profil.edit') }}"
                       class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow hover:bg-emerald-700 transition">
                        Edit Profil
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Scan QR</p>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">
                {{ $totalScan }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Semua aktivitas</p>
        </div>

        <div class="bg-white rounded-xl border border-emerald-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-emerald-600 uppercase">Tiket Valid</p>
            <h2 class="text-2xl font-extrabold text-emerald-700 mt-1">
                {{ $validTickets }}
            </h2>
            <p class="text-[10px] text-emerald-500 mt-0.5">Berhasil diverifikasi</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 p-4 shadow-sm">
            <p class="text-[11px] font-semibold text-red-600 uppercase">Tiket Ditolak</p>
            <h2 class="text-2xl font-extrabold text-red-700 mt-1">
                {{ $rejectedTickets }}
            </h2>
            <p class="text-[10px] text-slate-400 mt-0.5">QR sudah dipakai / expired</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-xl p-4 text-white shadow-sm flex flex-col justify-between">
            <p class="text-[11px] font-bold text-emerald-100 uppercase">Tamu Dilayani</p>
            <div>
                <h2 class="text-2xl font-extrabold mt-1">
                    {{ $totalVisitors }}
                </h2>
                <p class="text-[10px] text-emerald-100 mt-0.5">Total pengunjung</p>
            </div>
        </div>
    </div>

    {{-- PROFILE DETAIL & SHIFT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">
                        Informasi Akun Petugas
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Data pribadi dan detail penugasan loket.
                    </p>
                </div>

                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                    Terverifikasi
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Nama Lengkap</p>
                    <h3 class="font-bold text-slate-800 mt-0.5">{{ $petugas->name }}</h3>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Email Akun</p>
                    <h3 class="font-bold text-slate-800 mt-0.5">{{ $petugas->email }}</h3>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase">ID Petugas</p>
                    <h3 class="font-bold font-mono text-slate-800 mt-0.5">
                        PTG-{{ str_pad($petugas->id, 4, '0', STR_PAD_LEFT) }}
                    </h3>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Bergabung Sejak</p>
                    <h3 class="font-bold text-slate-800 mt-0.5">
                        {{ $petugas->created_at?->translatedFormat('d F Y') ?? '-' }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- SHIFT INFO --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 mb-0.5">
                Shift Operasional
            </h2>
            <p class="text-xs text-slate-400 mb-3">
                Jadwal jaga gate masuk.
            </p>

            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-xl p-4 text-white shadow-sm">
                <p class="text-[11px] font-semibold text-emerald-100">Shift Gate Masuk</p>
                <h3 class="text-xl font-extrabold mt-1">
                    08.00 - 16.00 WIB
                </h3>
                <p class="text-xs text-emerald-100 mt-1">
                    🏛 {{ $petugas->museum?->name ?? 'Museum Itihasa' }}
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
