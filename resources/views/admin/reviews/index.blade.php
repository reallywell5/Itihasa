@extends('layouts.app')

@section('title', auth()->user()->isSuperAdmin() ? 'Pemantauan Ulasan' : 'Ulasan Pengunjung')

@section('content')
<div class="max-w-7xl mx-auto space-y-5" x-data="{ replyModal: false, selectedReviewId: null, selectedReviewUser: '', selectedReviewComment: '', currentReply: '' }">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border {{ auth()->user()->isSuperAdmin() ? 'border-purple-100' : 'border-blue-100' }} p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                Ulasan & Respon Pengelola Museum
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                {{ auth()->user()->isSuperAdmin() ? 'Memantau sentimen, bintang kepuasan, dan laporan ulasan pengunjung dari seluruh museum.' : 'Kelola ulasan pengunjung, berikan tanggapan resmi pengelola museum, dan moderasi laporan.' }}
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
                            <th class="px-4 py-3">Komentar & Respon Resmi</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3 text-center">Laporan</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach ($reviews as $review)
                            <tr class="hover:bg-slate-50/70 transition align-top">
                                <td class="px-4 py-3.5 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $review->user->name ?? 'Pengunjung' }}
                                </td>
                                <td class="px-4 py-3.5 text-slate-600 whitespace-nowrap">
                                    🏛 {{ $review->museum->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold">
                                        {{ $review->rating }} ★
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-slate-600 max-w-md">
                                    <p class="leading-relaxed">
                                        {{ $review->comment ?: '-' }}
                                    </p>

                                    {{-- ATTACHED PHOTOS --}}
                                    @if(!empty($review->photos) && is_array($review->photos))
                                        <div class="flex gap-1.5 mt-2">
                                            @foreach($review->photoUrls() as $pUrl)
                                                <a href="{{ $pUrl }}" target="_blank" class="w-8 h-8 rounded-lg overflow-hidden border border-slate-200 inline-block hover:scale-110 transition shadow-xs" title="Lihat foto penuh">
                                                    <img src="{{ $pUrl }}" class="w-full h-full object-cover">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- OFFICIAL ADMIN REPLY (FITUR B ADMIN) --}}
                                    @if($review->admin_reply)
                                        <div class="mt-2.5 p-2.5 rounded-xl bg-blue-50/70 border border-blue-200 text-slate-700 text-[11px]">
                                            <div class="flex items-center justify-between gap-2 font-bold text-blue-900 mb-1">
                                                <span class="flex items-center gap-1">
                                                    <span>🏛️</span>
                                                    <span>Respon Resmi Pengelola:</span>
                                                </span>
                                                <span class="text-[9px] text-blue-600 font-normal">
                                                    {{ $review->admin_replied_at?->translatedFormat('d M Y, H:i') }}
                                                </span>
                                            </div>
                                            <p class="leading-normal">{{ $review->admin_reply }}</p>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-slate-400 text-[11px] whitespace-nowrap">
                                    {{ $review->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    @if ($review->reports_count > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-50 text-red-600 text-[10px] font-bold">
                                            🚩 {{ $review->reports_count }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if(! auth()->user()->isSuperAdmin())
                                            {{-- BUTTON BALAS / EDIT BALASAN (FITUR B ADMIN) --}}
                                            <button type="button"
                                                    @click="
                                                        replyModal = true;
                                                        selectedReviewId = {{ $review->id }};
                                                        selectedReviewUser = '{{ addslashes($review->user->name ?? 'Pengunjung') }}';
                                                        selectedReviewComment = '{{ addslashes($review->comment ?? '') }}';
                                                        currentReply = '{{ addslashes($review->admin_reply ?? '') }}';
                                                    "
                                                    class="px-2.5 py-1 rounded-lg {{ $review->admin_reply ? 'bg-blue-50 hover:bg-blue-100 text-blue-700' : 'bg-amber-50 hover:bg-amber-100 text-amber-800' }} text-[11px] font-bold transition">
                                                {{ $review->admin_reply ? 'Edit Tanggapan' : '💬 Balas' }}
                                            </button>

                                            {{-- BUTTON HAPUS ULASAN OLEH ADMIN MUSEUM --}}
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
                                            <span class="px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 text-[10px] font-bold border border-purple-100">
                                                👁️ Mode Pantau
                                            </span>
                                        @endif
                                    </div>
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

    {{-- MODAL BALAS ULASAN (FITUR B ADMIN) --}}
    <div x-show="replyModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
         @click.self="replyModal = false">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-base font-bold text-slate-800">
                    Tanggapan Resmi Pengelola Museum
                </h3>
                <button type="button" @click="replyModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <p class="text-xs text-slate-500 mb-4">
                Menanggapi ulasan dari <strong class="text-slate-700" x-text="selectedReviewUser"></strong>. Tanggapan ini akan tampil di halaman publik museum.
            </p>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600 mb-4 italic" x-text="'“' + selectedReviewComment + '”'"></div>

            <form :action="'/admin/reviews/' + selectedReviewId + '/reply'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Isi Tanggapan Resmi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="admin_reply" rows="4" required x-model="currentReply"
                              placeholder="Terima kasih atas kunjungannya, kami senang Anda menikmati koleksi kami..."
                              class="w-full rounded-xl border border-slate-200 p-3 text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" @click="replyModal = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-sm">
                        Publikasikan Respon 🏛️
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
