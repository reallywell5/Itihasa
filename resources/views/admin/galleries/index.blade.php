@extends('layouts.app')

@section('title', auth()->user()->isSuperAdmin() ? 'Pemantauan Galeri' : 'Galeri Museum')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border {{ auth()->user()->isSuperAdmin() ? 'border-purple-100' : 'border-blue-100' }} p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if(auth()->user()->isSuperAdmin())
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[11px] font-bold mb-2">
                    👑 Super Admin • Pemantauan Koleksi Galeri Seluruh Museum
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold mb-2">
                    🏛 Admin • {{ auth()->user()->museum?->name ?? 'Museum' }}
                </span>
            @endif
            <h1 class="text-xl font-bold text-slate-800">
                Galeri & Foto Koleksi
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                {{ auth()->user()->isSuperAdmin() ? 'Memantau seluruh dokumentasi artefak, suasana, dan fasilitas museum di sistem Itihasa.' : 'Kelola foto-foto koleksi, fasilitas, dan suasana untuk museum Anda.' }}
            </p>
        </div>

        @if(! auth()->user()->isSuperAdmin())
            <a href="{{ route('galleries.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-bold shadow hover:bg-blue-700 transition shrink-0">
                <span>+ Upload Foto Baru</span>
            </a>
        @endif
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-xl px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- GRID GALERI --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Koleksi Foto Terunggah ({{ $galleries->count() }})
            </h2>
        </div>

        @if ($galleries->isEmpty())
            <div class="text-center py-12 text-xs text-slate-400">
                Belum ada foto galeri terunggah.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($galleries as $gallery)
                    <div class="group border border-slate-100 rounded-xl overflow-hidden hover:shadow-md hover:border-slate-200 transition flex flex-col justify-between bg-slate-50/50">

                        <div class="relative overflow-hidden aspect-[4/3] bg-slate-100">
                            <img src="{{ asset('storage/' . $gallery->image_path) }}"
                                 alt="{{ $gallery->caption ?? $gallery->museum->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-0.5 rounded-md bg-slate-900/80 text-white text-[10px] font-bold backdrop-blur-sm">
                                    {{ $gallery->museum->name }}
                                </span>
                            </div>
                        </div>

                        <div class="p-3.5 flex flex-col justify-between flex-1">
                            <p class="text-xs text-slate-600 font-medium line-clamp-2 min-h-[32px]">
                                {{ $gallery->caption ?: 'Tanpa keterangan foto' }}
                            </p>

                            @if(! auth()->user()->isSuperAdmin())
                                <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus foto ini?')"
                                      class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full py-1.5 rounded-lg border border-red-200 text-red-600 text-[11px] font-bold hover:bg-red-50 transition">
                                        Hapus Foto
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
