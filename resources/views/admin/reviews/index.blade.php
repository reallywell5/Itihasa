@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Review Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">
                Manajemen Reviews
            </h1>

            <p class="text-sm text-slate-500 max-w-xl">
                Kelola ulasan, rating, dan feedback pengunjung terhadap museum.
            </p>
        </div>

        <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center">
            ⭐
        </div>

    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 text-green-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Total Review
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                {{ $reviews->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6">
            <p class="text-sm text-slate-400 font-semibold mb-2">
                Average Rating
            </p>

            <h2 class="text-3xl font-bold text-slate-800">
                {{ number_format($reviews->avg('rating'), 1) ?? 0 }}
            </h2>
        </div>

        <div class="bg-blue-600 rounded-3xl shadow-sm p-6 text-white">
            <p class="text-sm text-blue-100 mb-2">
                Rating Tertinggi
            </p>

            <h2 class="text-2xl font-bold">
                {{ $reviews->max('rating') ?? 0 }}/5
            </h2>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-blue-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Daftar Review
                </h2>

                <p class="text-sm text-slate-400">
                    Menampilkan seluruh ulasan pengunjung.
                </p>
            </div>

            <form method="GET" class="flex items-center bg-blue-50 rounded-xl px-4 py-2 w-full sm:w-72">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari review..."
                       class="bg-transparent outline-none text-sm w-full text-slate-600">
            </form>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-blue-50 text-blue-600 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-4">Pengunjung</th>
                        <th class="px-6 py-4">Museum</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Komentar</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse($reviews as $review)

                    <tr class="hover:bg-blue-50/30 transition">

                        {{-- USER --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">

                                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold uppercase">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 2)) }}
                                </div>

                                <div>
                                    <p class="font-bold text-slate-800">
                                        {{ $review->user->name ?? '-' }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        RVW-{{ str_pad($review->id, 4, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        {{-- MUSEUM --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $review->museum->name ?? '-' }}
                        </td>

                        {{-- RATING --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-600 text-xs font-semibold">
                                ⭐ {{ $review->rating }}/5
                            </span>
                        </td>

                        {{-- COMMENT --}}
                        <td class="px-6 py-4 text-slate-500 max-w-sm">
                            {{ Str::limit($review->comment, 50) }}
                        </td>

                        {{-- DATE --}}
                        <td class="px-6 py-4 text-slate-500">
                            {{ $review->created_at->format('d M Y') }}
                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('reviews.show', $review->id) }}"
                               class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold hover:bg-blue-600 hover:text-white transition">
                                Detail
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            Belum ada review.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection
