@extends('layouts.app')

@section('title', 'Detail Review')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">Detail Review</h1>
            <p class="text-sm text-zinc-500 mt-1">Informasi lengkap ulasan pengunjung.</p>
        </div>

        <a href="{{ route('reviews.index') }}"
           class="px-4 py-2 rounded-lg border border-zinc-200 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
            Kembali
        </a>
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Nama User
                </p>
                <p class="mt-2 text-lg font-bold text-zinc-900">
                    {{ $review->user->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Museum
                </p>
                <p class="mt-2 text-lg font-bold text-zinc-900">
                    {{ $review->museum->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Rating
                </p>
                <div class="mt-2">
                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                        ⭐ {{ $review->rating }}/5
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-widest font-bold text-zinc-400">
                    Tanggal Review
                </p>
                <p class="mt-2 text-sm text-zinc-700">
                    {{ $review->created_at ? $review->created_at->format('d M Y H:i') : '-' }}
                </p>
            </div>

        </div>

        <div class="mt-10 border-t border-zinc-100 pt-8">
            <p class="text-xs uppercase tracking-widest font-bold text-zinc-400 mb-3">
                Komentar
            </p>

            <div class="p-5 rounded-xl bg-zinc-50 border border-zinc-200 text-sm text-zinc-700 leading-relaxed">
                {{ $review->comment ?? 'Tidak ada komentar.' }}
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
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
