@extends('layouts.app')

@section('title', 'Upload Foto Galeri')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('galleries.index') }}" class="text-xs text-blue-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Galeri
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Upload Foto Koleksi Baru
            </h1>
            <p class="text-xs text-slate-400">
                Museum: <span class="font-bold text-slate-700">{{ $museums->first()->name ?? auth()->user()->museum?->name }}</span>
            </p>
        </div>

        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
            Form Upload
        </span>
    </div>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl px-4 py-3 font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-xl">
            @csrf

            <input type="hidden" name="museum_id" value="{{ $museums->first()->id }}">

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">
                    Pilih Berkas Foto <span class="text-red-500">*</span>
                </label>
                <input type="file" name="image" accept="image/*" required
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold file:text-xs">
                <p class="text-[10px] text-slate-400 mt-1">Maksimal 2MB (format JPG, PNG, atau WEBP)</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">
                    Caption / Keterangan Foto (opsional)
                </label>
                <input type="text" name="caption" maxlength="255"
                       placeholder="Contoh: Koleksi Keris Majapahit Abad ke-14"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">
                    Urutan Tampil (opsional)
                </label>
                <input type="number" name="order" min="0" value="0"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="flex gap-2.5 pt-3">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-bold shadow hover:bg-blue-700 transition">
                    Upload & Simpan
                </button>
                <a href="{{ route('galleries.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
