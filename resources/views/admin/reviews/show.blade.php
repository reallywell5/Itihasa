@extends('layouts.app')

@section('title', 'Detail Review')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Review Detail
            </p>

            <h1 class="text-2xl font-bold text-slate-800">
                Detail Review
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Informasi lengkap ulasan pengunjung museum.
            </p>
        </div>

        <a href="{{ route('reviews.index') }}"
           class="px-4 py-2 rounded-xl border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100 transition">
            Kembali
        </a>

    </div>

    {{-- HERO --}}
    <div class="bg-white rounded-3xl border border-blue-100 shadow-sm p-6 flex items-center justify-between">

        <div>
            <p class="text-sm text-slate-400 mb-2">
                Rating Pengunjung
            </p>

            <h2 class="text-4xl font-bold text-slate-800">
                ⭐ {{ $review->rating }}/5
            </h2>
        </div>

        <div class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold">
            RVW-{{ str_pad($review->id, 4, '0', STR_PAD_LEFT) }}
        </div>

    </div>

    {{-- DETAIL --}}
    <div class="bg-white border border-zinc-200 rounded-3xl shadow-sm p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- USER --}}
            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">
                    Nama Pengunjung
                </p>

                <div class="flex items-center gap-4 mt-3">

                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold uppercase">
                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 2)) }}
                    </div>

                    <p class="text-lg font-bold text-zinc-900">
                        {{ $review->user->name ?? '-' }}
                    </p>

                </div>
            </div>

            {{-- MUSEUM --}}
            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">
                    Museum
                </p>

                <p class="mt-3 text-lg font-bold text-zinc-900">
                    {{ $review->museum->name ?? '-' }}
                </p>
            </div>

            {{-- RATING --}}
            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">
                    Rating
                </p>

                <div class="mt-3">
                    <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-600 text-xs font-semibold">
                        ⭐ {{ $review->rating }}/5
                    </span>
                </div>
            </div>

            {{-- DATE --}}
            <div>
                <p class="text-xs uppercase font-bold text-zinc-400">
                    Tanggal Review
                </p>

                <p class="mt-3 text-sm text-zinc-700">
                    {{ $review->created_at ? $review->created_at->format('d M Y • H:i') : '-' }}
                </p>
            </div>

        </div>

        {{-- COMMENT --}}
        <div class="mt-10 border-t border-zinc-100 pt-8">

            <p class="text-xs uppercase font-bold text-zinc-400 mb-4">
                Komentar Pengunjung
            </p>

            <div class="p-6 rounded-2xl bg-zinc-50 border border-zinc-200 text-sm text-zinc-700 leading-relaxed">
                {{ $review->comment ?? 'Tidak ada komentar.' }}
            </div>

        </div>

        {{-- ACTION --}}
        <div class="mt-8 flex justify-end">

            <form action="{{ route('reviews.destroy', $review->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                    Hapus Review
                </button>
            </form>

        </div>

    </div>

</div>
@endsection
