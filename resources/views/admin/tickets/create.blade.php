@extends('layouts.app')

@section('title', 'Add Ticket')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Ticket Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800">
                Tambah Tiket
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Tambahkan kategori tiket baru untuk museum.
            </p>
        </div>

        <div class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold border border-blue-100">
            Ticket Setup
        </div>

    </div>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- LEFT --}}
            <div class="lg:col-span-2 bg-white border border-zinc-200 rounded-3xl shadow-sm p-8 space-y-6">

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- MUSEUM --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Nama Museum
                    </label>

                    <select name="museum_id"
                            class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                        <option value="">Pilih Museum</option>

                        @foreach ($museums as $museum)
                            <option value="{{ $museum->id }}">
                                {{ $museum->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Kategori Tiket
                    </label>

                    <input type="text"
                           name="ticket_name"
                           value="{{ old('ticket_name') }}"
                           placeholder="Contoh: Dewasa / Anak / Mahasiswa"
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                </div>

                {{-- PRICE --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Harga Tiket
                    </label>

                    <input type="number"
                           name="price"
                           value="{{ old('price') }}"
                           placeholder="Masukkan harga tiket"
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                </div>

                {{-- SLOT --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Total Slot Tiket
                    </label>

                    <input type="number"
                           name="slot"
                           value="{{ old('slot') }}"
                           placeholder="Jumlah tiket tersedia"
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">

                {{-- INFO --}}
                <div class="bg-white border border-zinc-200 rounded-3xl shadow-sm p-6 space-y-4">

                    <h3 class="font-bold text-zinc-900">
                        Informasi Tiket
                    </h3>

                    <div class="flex justify-between">
                        <span class="text-sm text-zinc-500">Status</span>
                        <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-xs font-semibold">
                            Active
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-zinc-500">Kategori</span>
                        <span class="text-sm font-semibold text-zinc-900">
                            Custom
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-zinc-500">Tipe</span>
                        <span class="text-sm font-semibold text-zinc-900">
                            Museum Ticket
                        </span>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="bg-white border border-zinc-200 rounded-3xl shadow-sm p-6 space-y-3">

                    <button type="submit"
                            class="w-full py-3 rounded-xl bg-zinc-900 text-white font-semibold hover:bg-zinc-800 transition">
                        Simpan Tiket
                    </button>

                    <a href="{{ route('tickets.index') }}"
                       class="w-full flex justify-center py-3 rounded-xl border border-zinc-200 text-zinc-700 font-semibold hover:bg-zinc-50 transition">
                        Batal
                    </a>

                </div>

            </div>

        </div>

    </form>

</div>
@endsection
