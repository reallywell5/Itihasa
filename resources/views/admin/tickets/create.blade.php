@extends('layouts.app')

@section('title', 'Tambah Tiket')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('tickets.index') }}" class="text-xs text-blue-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Daftar Tiket
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Tambah Kategori Tiket Baru
            </h1>
            <p class="text-xs text-slate-400">
                Museum: <span class="font-bold text-slate-700">{{ auth()->user()->museum?->name ?? 'Museum Anda' }}</span>
            </p>
        </div>

        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
            Form Tiket
        </span>
    </div>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <div class="grid lg:grid-cols-3 gap-5">

            {{-- LEFT: FORM INPUT --}}
            <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl shadow-sm p-6 space-y-4">

                {{-- ERROR ALERT --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- MUSEUM INFO --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        Museum
                    </label>
                    <input type="text"
                           disabled
                           value="{{ auth()->user()->museum?->name ?? 'Museum Anda' }}"
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-3.5 py-2.5 text-xs text-slate-600 font-semibold cursor-not-allowed">
                </div>

                {{-- CATEGORY NAME --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">
                        Nama Kategori Tiket <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="ticket_name"
                           required
                           value="{{ old('ticket_name') }}"
                           placeholder="Contoh: Tiket Reguler / Pelajar / Wisman"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                {{-- PRICE & SLOT IN 2 COLS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">
                            Harga Tiket (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="price"
                               required
                               min="0"
                               value="{{ old('price') }}"
                               placeholder="Contoh: 15000"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">
                            Kuota Slot Pengunjung <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               name="slot"
                               required
                               min="1"
                               value="{{ old('slot', 100) }}"
                               placeholder="Contoh: 100"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

            </div>

            {{-- RIGHT: SUMMARY & ACTIONS --}}
            <div class="space-y-4">
                <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5 space-y-3">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Panduan Kuota
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Kuota tiket akan digunakan sebagai batas maksimal pemesanan per sesi/hari oleh pengunjung online.
                    </p>
                </div>

                <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5 space-y-2.5">
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition shadow">
                        Simpan Kategori Tiket
                    </button>

                    <a href="{{ route('tickets.index') }}"
                       class="w-full flex justify-center py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
