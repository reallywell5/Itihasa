@extends('layouts.app')

@section('title', 'Detail Pengumuman / Broadcast')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.broadcasts.index') }}" class="text-xs text-purple-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Daftar Broadcast
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Detail Pengumuman Resmi
            </h1>
            <p class="text-xs text-slate-400">
                Informasi detail pengumuman yang diterbitkan oleh Super Admin.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.broadcasts.edit', $broadcast->id) }}"
               class="px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition">
                Edit Pengumuman
            </a>
        </div>
    </div>

    {{-- DETAIL CARD --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden p-6 space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2">
                @php
                    $urgencyBadge = match($broadcast->urgency) {
                        'danger' => 'bg-red-50 text-red-700 border-red-200',
                        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
                        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                        default => 'bg-blue-50 text-blue-800 border-blue-200',
                    };
                @endphp
                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold uppercase border {{ $urgencyBadge }}">
                    {{ $broadcast->urgency }}
                </span>

                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $broadcast->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    {{ $broadcast->is_active ? '● Aktif Tayang' : '○ Nonaktif' }}
                </span>
            </div>

            <span class="text-xs text-slate-400">
                Dibuat oleh: <strong>{{ $broadcast->creator->name ?? 'Super Admin' }}</strong> • {{ $broadcast->created_at->translatedFormat('d M Y, H:i') }} WIB
            </span>
        </div>

        <div>
            <h2 class="text-lg font-bold text-slate-800 mb-2">
                {{ $broadcast->title }}
            </h2>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                {{ $broadcast->message }}
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100 text-xs">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span class="text-slate-400 font-bold block mb-1">Target Penerima:</span>
                <span class="font-semibold text-slate-800">
                    @if($broadcast->target_role === 'all')
                        Seluruh Tim (Admin + Petugas Gate)
                    @elseif($broadcast->target_role === 'admin')
                        Khusus Admin Museum
                    @else
                        Khusus Petugas Gate
                    @endif
                </span>
            </div>

            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span class="text-slate-400 font-bold block mb-1">Masa Berlaku:</span>
                <span class="font-semibold text-slate-800">
                    @if($broadcast->expires_at)
                        Sampai {{ $broadcast->expires_at->translatedFormat('d F Y, H:i') }} WIB
                    @else
                        Selamanya (Tanpa Batas Waktu)
                    @endif
                </span>
            </div>
        </div>
    </div>

</div>
@endsection

