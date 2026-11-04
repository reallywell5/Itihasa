@extends('layouts.app')

@section('title', 'Manajemen Kode Promo & Kupon Diskon')

@section('content')
<div class="max-w-7xl mx-auto space-y-5" x-data="{ discountType: 'percent' }">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                Kode Promo & Kupon Diskon Tiket
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                Buat dan kelola voucher diskon khusus (promo hari besar, rombongan sekolah, dll.) untuk menarik minat pengunjung memesan tiket online.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold">
                Total: {{ $promos->total() }} Kupon
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

        {{-- FORM TAMBAH PROMO --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm h-fit">
            <h2 class="text-sm font-bold text-slate-800 mb-1">
                Buat Kupon Promo Baru
            </h2>
            <p class="text-xs text-slate-400 mb-4">
                Atur kode voucher dan parameter pemotongan harga.
            </p>

            <form action="{{ route('admin.promos.store') }}" method="POST" class="space-y-3.5">
                @csrf

                @if($isSuperAdmin)
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Museum (Opsional: Kosongkan untuk Global)
                        </label>
                        <select name="museum_id" class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                            <option value="">🌐 Kupon Berlaku untuk Semua Museum</option>
                            @foreach($museums as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Kode Kupon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" required placeholder="Contoh: MERDEKA20"
                           value="{{ old('code') }}"
                           class="w-full rounded-xl border border-slate-200 p-2.5 text-xs font-mono font-bold uppercase text-slate-800 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Judul Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required placeholder="Contoh: Promo HUT Kemerdekaan 20%"
                           value="{{ old('title') }}"
                           class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tipe Diskon <span class="text-red-500">*</span>
                        </label>
                        <select name="discount_type" x-model="discountType" required
                                class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                            <option value="percent">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Nilai Diskon <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="discount_value" required min="1"
                               :placeholder="discountType === 'percent' ? 'Contoh: 20' : 'Contoh: 10000'"
                               value="{{ old('discount_value') }}"
                               class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Min. Belanja (Rp)
                        </label>
                        <input type="number" name="min_total_price" min="0" placeholder="0"
                               value="{{ old('min_total_price', 0) }}"
                               class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div x-show="discountType === 'percent'">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Maks. Diskon (Rp)
                        </label>
                        <input type="number" name="max_discount" min="0" placeholder="Opsional"
                               value="{{ old('max_discount') }}"
                               class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Batas Kuota Pemakaian <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quota" required min="1" placeholder="Contoh: 100"
                           value="{{ old('quota', 100) }}"
                           class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tgl Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" required
                               value="{{ old('start_date', date('Y-m-d')) }}"
                               class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tgl Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" required
                               value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}"
                               class="w-full rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-sm">
                    Buat Kode Promo
                </button>
            </form>
        </div>

        {{-- DAFTAR KODE PROMO --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800">
                        Daftar Kupon Promo Aktif
                    </h2>
                    <p class="text-xs text-slate-400">
                        Kode promo yang dapat digunakan pengunjung saat reservasi tiket.
                    </p>
                </div>

                @if($promos->isEmpty())
                    <div class="text-center py-12 text-xs text-slate-400">
                        Belum ada kode promo yang dibuat.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3">Kode & Judul</th>
                                    <th class="px-4 py-3">Diskon</th>
                                    <th class="px-4 py-3 text-center">Pemakaian</th>
                                    <th class="px-4 py-3">Periode</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($promos as $promo)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 rounded-lg bg-amber-50 border border-amber-200 font-mono font-bold text-amber-800 text-xs">
                                                    {{ $promo->code }}
                                                </span>
                                            </div>
                                            <p class="font-semibold text-slate-800 mt-1">{{ $promo->title }}</p>
                                            <span class="text-[10px] text-slate-400">
                                                🏛 {{ $promo->museum->name ?? 'Semua Museum' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            @if($promo->discount_type === 'percent')
                                                <span class="font-bold text-blue-600 text-sm">
                                                    {{ $promo->discount_value }}% OFF
                                                </span>
                                                @if($promo->max_discount)
                                                    <span class="text-[10px] text-slate-400 block">
                                                        Maks Rp {{ number_format($promo->max_discount, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="font-bold text-emerald-600 text-sm">
                                                    - Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                            <span class="font-bold {{ $promo->used_count >= $promo->quota ? 'text-red-500' : 'text-slate-700' }}">
                                                {{ $promo->used_count }}
                                            </span>
                                            <span class="text-slate-400 text-[10px]">/ {{ $promo->quota }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-600 text-[11px] whitespace-nowrap">
                                            {{ $promo->start_date->format('d/m/y') }} - {{ $promo->end_date->format('d/m/y') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                            <form action="{{ route('admin.promos.toggle', $promo->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold transition {{ $promo->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                                    {{ $promo->is_active ? '● Aktif' : '○ Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                            <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus kode promo {{ $promo->code }}?')">
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

            @if($promos->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $promos->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
