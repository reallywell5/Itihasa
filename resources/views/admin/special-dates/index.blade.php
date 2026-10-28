@extends('layouts.app')

@section('title', 'Kalender Hari Libur Khusus & Kuota Dinamis')

@section('content')
<div class="max-w-7xl mx-auto space-y-5" x-data="{ isClosed: true }">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                Kalender Hari Libur Khusus & Kuota Dinamis
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                Tandai tanggal libur mendadak (renovasi/acara internal) agar terkunci dari sistem booking online, atau atur kuota khusus untuk musim liburan.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold">
                Total: {{ $specialDates->total() }} Tanggal Khusus
            </span>
        </div>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-xl px-4 py-3">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- FORM TAMBAH JADWAL KHUSUS --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm h-fit">
            <h2 class="text-sm font-bold text-slate-800 mb-1">
                Tambah Jadwal Khusus
            </h2>
            <p class="text-xs text-slate-400 mb-4">
                Atur status operasional atau kuota untuk tanggal spesifik.
            </p>

            <form action="{{ route('admin.special-dates.store') }}" method="POST" class="space-y-4">
                @csrf

                @if($isSuperAdmin)
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Pilih Museum <span class="text-red-500">*</span>
                        </label>
                        <select name="museum_id" required class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                            @foreach($museums as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Pilih Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                           value="{{ old('date', date('Y-m-d')) }}"
                           class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_closed" value="1" x-model="isClosed"
                               class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                        <span class="text-xs font-bold text-slate-800">
                            ⛔ Libur Khusus (Museum Tutup Total)
                        </span>
                    </label>
                    <p class="text-[11px] text-slate-500 leading-tight">
                        Jika dicentang, pengunjung tidak akan bisa memesan tiket pada tanggal ini di web.
                    </p>
                </div>

                <div x-show="!isClosed" x-cloak>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Penyesuaian Kuota Tiket Harian (Opsional)
                    </label>
                    <input type="number" name="quota_override" min="0" placeholder="Kosongkan jika kuota normal"
                           value="{{ old('quota_override') }}"
                           class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                    <span class="text-[10px] text-slate-400 mt-1 block">
                        Isi jika ingin membatasi atau menambah kuota khusus pada tanggal ini.
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Alasan / Keterangan (Opsional)
                    </label>
                    <input type="text" name="notes" placeholder="Contoh: Renovasi Sayap Timur / Acara Kenegaraan"
                           value="{{ old('notes') }}"
                           class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-sm">
                    Simpan Jadwal Khusus
                </button>
            </form>
        </div>

        {{-- DAFTAR JADWAL KHUSUS --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800">
                        Daftar Tanggal Tutup Khusus & Kuota Dinamis
                    </h2>
                    <p class="text-xs text-slate-400">
                        Jadwal khusus yang aktif di sistem booking.
                    </p>
                </div>

                @if($specialDates->isEmpty())
                    <div class="text-center py-12 text-xs text-slate-400">
                        Belum ada tanggal libur khusus atau kuota dinamis yang didaftarkan.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    @if($isSuperAdmin)
                                        <th class="px-4 py-3">Museum</th>
                                    @endif
                                    <th class="px-4 py-3">Status Operasional</th>
                                    <th class="px-4 py-3">Keterangan / Alasan</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($specialDates as $item)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-4 py-3.5 font-bold text-slate-800 whitespace-nowrap">
                                            {{ $item->date->translatedFormat('l, d F Y') }}
                                        </td>
                                        @if($isSuperAdmin)
                                            <td class="px-4 py-3.5 text-slate-600 whitespace-nowrap">
                                                🏛 {{ $item->museum->name ?? '-' }}
                                            </td>
                                        @endif
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            @if($item->is_closed)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-[11px] font-bold">
                                                    ⛔ Tutup / Libur Khusus
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold">
                                                    ⚡ Kuota: {{ $item->quota_override ?? 'Normal' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-600 max-w-xs">
                                            {{ $item->notes ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                            <form action="{{ route('admin.special-dates.destroy', $item->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus jadwal khusus untuk tanggal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-[11px] font-bold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if($specialDates->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $specialDates->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
