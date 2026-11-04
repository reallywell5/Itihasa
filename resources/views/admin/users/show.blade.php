@extends('layouts.app')

@section('title', 'Detail Akun Pengguna')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('users.index') }}" class="text-xs text-purple-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Manajemen Pengguna
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Detail Profil Pengguna
            </h1>
            <p class="text-xs text-slate-400">
                Informasi akun, peran sistem, penugasan museum, dan riwayat transaksi pengguna.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('users.edit', $user->id) }}"
               class="px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition">
                Edit Akun
            </a>
        </div>
    </div>

    {{-- USER INFO CARD --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center font-extrabold text-xl shadow-inner shrink-0">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $user->email }}
                    </p>
                    <span class="text-[10px] text-slate-400 font-mono mt-1 block">
                        ID: USR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }} • Terdaftar sejak {{ $user->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                    </span>
                </div>
            </div>

            <div>
                @if($user->role === 'super_admin')
                    <span class="px-3 py-1.5 rounded-xl bg-purple-100 text-purple-800 text-xs font-bold inline-flex items-center">
                        Super Admin (Otoritas Global)
                    </span>
                @elseif($user->role === 'admin')
                    <span class="px-3 py-1.5 rounded-xl bg-blue-100 text-blue-800 text-xs font-bold inline-flex items-center">
                        Admin Museum
                    </span>
                @elseif($user->role === 'staff')
                    <span class="px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-bold inline-flex items-center">
                        Petugas Loket & Gate
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold inline-flex items-center">
                        Pengunjung Wisata
                    </span>
                @endif
            </div>
        </div>

        {{-- METRICS & DETAIL INFO --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Museum Penugasan</span>
                @if($user->museum)
                    <p class="font-bold text-slate-800 text-sm">{{ $user->museum->name }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $user->museum->address }}</p>
                @else
                    <p class="text-slate-400 italic">Tidak terikat museum spesifik (Global)</p>
                @endif
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Transaksi Tiket</span>
                <p class="font-bold text-slate-800 text-sm">{{ $user->transactions->count() }} Transaksi</p>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    Rp {{ number_format($user->transactions->where('payment_status', 'paid')->sum('total_amount'), 0, ',', '.') }} total lunas
                </p>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Status Verifikasi</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold text-[11px]">
                    Akun Terverifikasi
                </span>
                <p class="text-[11px] text-slate-400 mt-1">Aktif dalam ekosistem</p>
            </div>
        </div>

        {{-- RIWAYAT TRANSAKSI TERAKHIR (JIKA PENGUNJUNG ATAU ADA TRANSAKSI) --}}
        @if($user->transactions->isNotEmpty())
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">
                    Riwayat Transaksi Pengguna
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                            <tr>
                                <th class="px-3 py-2">Invoice</th>
                                <th class="px-3 py-2">Museum</th>
                                <th class="px-3 py-2">Nominal</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2 text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($user->transactions->take(5) as $tx)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-3 py-2.5 font-mono font-bold text-purple-900">
                                        <a href="{{ route('transactions.show', $tx->id) }}" class="hover:underline">
                                            {{ $tx->invoice_code }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        {{ $tx->booking->museum->name ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2.5 font-semibold">
                                        Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-2.5">
                                        @if($tx->payment_status === 'paid')
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">Lunas</span>
                                        @elseif($tx->payment_status === 'pending')
                                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">Menunggu</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px]">{{ $tx->payment_status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5 text-right text-slate-400">
                                        {{ $tx->created_at?->translatedFormat('d M Y') ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection

