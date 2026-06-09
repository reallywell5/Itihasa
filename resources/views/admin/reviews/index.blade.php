@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 pb-2">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold tracking-wider text-indigo-600 uppercase">
                <span>Dashboard</span>
                <span class="text-zinc-300">/</span>
                <span class="text-zinc-500">Reviews</span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900 mt-1">
                Manajemen Reviews
            </h1>

            <p class="text-sm text-zinc-500 mt-1.5">
                Kelola ulasan, rating, dan komentar pengunjung terhadap museum.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm shadow-sm">
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-sm">

        <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900">
                    Daftar Review
                </h2>

                <p class="text-xs text-zinc-500 mt-0.5">
                    Menampilkan total <span class="font-semibold text-zinc-700">{{ $reviews->count() }}</span> data review.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 text-zinc-500 text-xs font-bold uppercase tracking-wider border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-3.5">Pengunjung</th>
                        <th class="px-6 py-3.5">Museum</th>
                        <th class="px-6 py-3.5">Rating</th>
                        <th class="px-6 py-3.5">Komentar</th>
                        <th class="px-6 py-3.5 text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-zinc-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-zinc-100 text-zinc-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-semibold text-zinc-900">
                                            {{ $review->user->name ?? '-' }}
                                        </p>

                                        <p class="text-xs text-zinc-400 mt-0.5">
                                            RVW-{{ str_pad($review->id, 4, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-zinc-600">
                                {{ $review->museum->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100">
                                    ⭐ {{ $review->rating }}/5
                                </span>
                            </td>

                            <td class="px-6 py-4 text-zinc-600 max-w-md truncate">
                                {{ $review->comment ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('reviews.show', $review->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 transition"
                                   title="Detail">
                                    👁
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-zinc-50 border border-zinc-100 rounded-2xl flex items-center justify-center mx-auto text-zinc-400 shadow-sm mb-4">
                                    💬
                                </div>

                                <h3 class="text-base font-bold text-zinc-900">
                                    Belum Ada Review
                                </h3>

                                <p class="text-sm text-zinc-500 mt-1">
                                    Data ulasan pengunjung akan tampil di halaman ini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
