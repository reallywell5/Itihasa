@extends('layouts.app')

@section('title', auth()->user()->isSuperAdmin() ? 'Pemantauan Ulasan' : 'Ulasan Pengunjung')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border {{ auth()->user()->isSuperAdmin() ? 'border-purple-100' : 'border-blue-100' }} p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if(auth()->user()->isSuperAdmin())
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[11px] font-bold mb-2">
                    👑 Super Admin • Pemantauan Ulasan & Rating Seluruh Museum
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold mb-2">
                    🏛 Admin • {{ auth()->user()->museum?->name ?? 'Museum' }}
                </span>
            @endif
            <h1 class="text-xl font-bold text-slate-800">
                Ulasan & Kepuasan Pengunjung
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                {{ auth()->user()->isSuperAdmin() ? 'Memantau sentimen, bintang kepuasan, dan laporan ulasan pengunjung dari seluruh museum.' : 'Kelola dan moderasi feedback serta ulasan yang masuk untuk museum Anda.' }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold">
                Total: {{ method_exists($reviews, 'total') ? $reviews->total() : $reviews->count() }} Ulasan
            </span>
        </div>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-xl px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($reviews->isEmpty())
            <div class="text-center py-12 text-xs text-slate-400">
                Belum ada ulasan pengunjung.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Pengunjung</th>
                            <th class="px-4 py-3">Museum</th>
                            <th class="px-4 py-3 text-center">Rating</th>
                            <th class="px-4 py-3">Komentar Ulasan</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3 text-center">Laporan</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach ($reviews as $review)
                            <tr class="hover:bg-slate-50/70 transition align-middle">
                                <td class="px-4 py-3 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $review->user->name ?? 'Pengunjung' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                    🏛 {{ $review->museum->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold">
                                        {{ $review->rating }} ★
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 max-w-sm">
                                    <p class="line-clamp-2">
                                        {{ $review->comment ?: '-' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-slate-400 text-[11px] whitespace-nowrap">
                                    {{ $review->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if ($review->reports_count > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-50 text-red-600 text-[10px] font-bold">
                                            🚩 {{ $review->reports_count }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if(! auth()->user()->isSuperAdmin())
                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-[11px] font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-medium italic">
                                            Read-only
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($reviews, 'links'))
                <div class="p-4 border-t border-slate-100">
                    {{ $reviews->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
