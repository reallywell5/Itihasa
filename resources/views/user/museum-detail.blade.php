@extends('layouts.user')

@section('title', $museum->name)

@if($museum->isActive())
    @section('cta_booking_url', auth()->check() ? route('user.booking', $museum->id) : route('login'))
@endif

@section('content')

<div class="pb-12 pt-2">

    {{-- TOP BREADCRUMB --}}
    <div class="mb-6 flex items-center justify-between gap-4">
        <a href="{{ route('user.home') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#5A5A5A] hover:text-[#1A1A1A] transition duration-300">
            <span>←</span>
            <span>Kembali ke Beranda</span>
        </a>

        <span class="text-[11px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold hidden sm:inline">
            Profil Koleksi &amp; Destinasi
        </span>
    </div>

    {{-- HERO IMAGE BANNER --}}
    <section class="relative h-64 sm:h-80 lg:h-[440px] overflow-hidden rounded-sm border border-neutral-200 bg-[#121212] mb-10">
        <img src="{{ $museum->image
            ? (Str::startsWith($museum->image, 'storage/')
                ? asset($museum->image)
                : asset('storage/' . $museum->image))
            : asset('images/institutional/hero-museum.jpg') }}"
            alt="{{ $museum->name }}"
            class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-[#121212]/70 via-[#121212]/20 to-transparent"></div>
    </section>

    {{-- MAIN 2-COLUMN EDITORIAL LAYOUT --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 lg:gap-10 items-start">

        {{-- LEFT COLUMN --}}
        <div class="space-y-8">

            {{-- MAIN INFO CARD --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8 lg:p-10">

                <div class="flex items-start justify-between gap-4 pb-6 border-b border-neutral-200">
                    <div>
                        <p class="uppercase text-[11px] tracking-[0.22em] text-[#B8860B] font-semibold mb-2">
                            {{ ucfirst($museum->category ?? 'Koleksi Bersejarah') }}
                        </p>
                        <h1 class="font-serif text-3xl sm:text-4xl lg:text-[42px] font-normal text-[#1A1A1A] tracking-[-0.015em] leading-[1.12]">
                            {{ $museum->name }}
                        </h1>
                        <p class="text-xs sm:text-sm text-[#5A5A5A] mt-2 flex items-center gap-1.5">
                            <span>{{ $museum->address }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        {{-- SHARE BUTTON --}}
                        <div class="relative" x-data="{ shareOpen: false, copied: false }">
                            <button type="button"
                                    @click="shareOpen = !shareOpen"
                                    @click.outside="shareOpen = false"
                                    title="Bagikan"
                                    class="w-10 h-10 rounded-sm flex items-center justify-center bg-[#F8F7F4] border border-neutral-200 text-[#1A1A1A] hover:border-[#B8860B] hover:text-[#B8860B] transition duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.684 13.342a3 3 0 100 2.316m0-2.316a3 3 0 100-2.316m0 2.316l6.632 3.316m-6.632-5.632l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 8.632a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </button>

                            <div x-show="shareOpen" x-cloak x-transition
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-sm border border-neutral-200 shadow-lg p-1.5 z-30">
                                <a href="https://wa.me/?text={{ urlencode('Yuk kunjungi ' . $museum->name . '! ' . url()->current()) }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-sm hover:bg-[#F8F7F4] transition text-xs font-medium text-[#1A1A1A]">
                                    <span>Bagikan ke WhatsApp</span>
                                </a>

                                <button type="button"
                                        @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-sm hover:bg-[#F8F7F4] transition text-xs font-medium text-[#1A1A1A] text-left">
                                    <span x-text="copied ? '✓ Tautan Tersalin' : 'Salin Tautan Halaman'"></span>
                                </button>
                            </div>
                        </div>

                        {{-- WISHLIST BUTTON --}}
                        @auth
                            @php
                                $isWishlisted = $museum->wishlists->contains('user_id', auth()->id());
                            @endphp
                            <form action="{{ route('user.wishlist.store', $museum->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        title="Simpan ke Wishlist"
                                        class="w-10 h-10 rounded-sm flex items-center justify-center border transition duration-300 {{ $isWishlisted ? 'bg-[#8C3B2B] border-[#8C3B2B] text-white' : 'bg-[#F8F7F4] border-neutral-200 text-[#1A1A1A] hover:border-[#8C3B2B] hover:text-[#8C3B2B]' }}">
                                    {{ $isWishlisted ? '♥' : '♡' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                               title="Masuk untuk Menyimpan"
                               class="w-10 h-10 rounded-sm flex items-center justify-center bg-[#F8F7F4] border border-neutral-200 text-[#1A1A1A] hover:border-[#B8860B] hover:text-[#B8860B] transition duration-300">
                                ♡
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- MULTI PARAGRAPH EDITORIAL DESCRIPTION --}}
                <div class="pt-6 text-[#5A5A5A] leading-relaxed text-sm sm:text-base space-y-4">
                    @foreach(explode("\n", $museum->description) as $paragraph)
                        @if(trim($paragraph))
                            <p>{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>

            </div>

            {{-- GALLERY --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8">
                <div class="flex items-end justify-between mb-6 pb-4 border-b border-neutral-200">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">Dokumentasi Ruang</p>
                        <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A]">
                            Galeri Foto
                        </h2>
                    </div>
                </div>

                @if ($museum->galleries->isNotEmpty())
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
                        @foreach ($museum->galleries as $photo)
                            <button type="button"
                                    onclick="openGalleryModal('{{ asset('storage/' . $photo->image_path) }}')"
                                    class="group relative rounded-sm overflow-hidden border border-neutral-200 aspect-square bg-neutral-100">
                                <img src="{{ asset('storage/' . $photo->image_path) }}"
                                     alt="{{ $photo->caption ?? $museum->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                            </button>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs sm:text-sm text-[#5A5A5A]">Belum ada foto galeri tambahan untuk museum ini.</p>
                @endif
            </div>

            {{-- GALLERY MODAL --}}
            <div id="galleryModal"
                 class="fixed inset-0 z-50 hidden items-center justify-center bg-[#121212]/85 p-6"
                 onclick="closeGalleryModal()">
                <img id="galleryModalImg" src="" alt="Preview"
                     class="max-w-4xl max-h-[85vh] rounded-sm border border-neutral-700 object-contain">
            </div>

            <script>
                function openGalleryModal(src) {
                    document.getElementById('galleryModalImg').src = src;
                    const modal = document.getElementById('galleryModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
                function closeGalleryModal() {
                    const modal = document.getElementById('galleryModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            </script>

            {{-- REVIEWS --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8" x-data="{ reportModalOpen: false, reportReviewId: null, lightboxOpen: false, lightboxImg: '' }">
                <div class="mb-6 pb-4 border-b border-neutral-200">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">Tanggapan Publik</p>
                    <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A]">
                        Ulasan Pengunjung
                    </h2>
                </div>

                @php
                    $allReviews = $allReviews ?? ($museum->relationLoaded('reviews') ? $museum->reviews : $museum->reviews()->with('user')->latest()->get());
                    $totalReviews = $allReviews->count();
                    $avgRating = $totalReviews ? round($allReviews->avg('rating'), 1) : 0;
                    $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                    foreach ($allReviews as $r) {
                        $ratingCounts[$r->rating] = ($ratingCounts[$r->rating] ?? 0) + 1;
                    }
                @endphp

                {{-- RATING SUMMARY --}}
                <div class="flex flex-col sm:flex-row gap-8 items-start sm:items-center bg-[#F8F7F4] border border-neutral-200 rounded-sm p-6 mb-8">
                    <div class="text-center shrink-0 sm:pr-6 sm:border-r sm:border-neutral-200">
                        <p class="font-serif text-5xl font-semibold text-[#1A1A1A]">{{ number_format($avgRating, 1) }}</p>
                        <div class="mt-1">
                            @include('partials.star-rating', ['rating' => $avgRating])
                        </div>
                        <p class="text-xs text-[#5A5A5A] mt-1">{{ $totalReviews }} ulasan</p>
                    </div>

                    <div class="flex-1 w-full space-y-2">
                        @for ($star = 5; $star >= 1; $star--)
                            @php
                                $count = $ratingCounts[$star] ?? 0;
                                $percent = $totalReviews ? round(($count / $totalReviews) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-3 text-xs">
                                <span class="w-4 font-semibold text-[#1A1A1A]">{{ $star }} ★</span>
                                <div class="flex-1 h-1.5 bg-neutral-200 rounded-sm overflow-hidden">
                                    <div class="h-full bg-[#B8860B]" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="w-6 text-right text-[#5A5A5A]">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="divide-y divide-neutral-200">
                    @forelse ($allReviews as $review)
                        <div class="flex gap-4 py-5 first:pt-0 last:pb-0"
                             @if(auth()->check() && auth()->id() === $review->user_id) x-data="{ editing: false }" @endif>

                            <div class="w-10 h-10 shrink-0 rounded-full bg-[#1A1A1A] text-white flex items-center justify-center font-semibold text-xs">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">

                                @if (auth()->check() && auth()->id() === $review->user_id)
                                    {{-- MODE TAMPIL --}}
                                    <div x-show="!editing">
                                        <div class="flex items-center justify-between gap-3 mb-1.5">
                                            <span class="font-semibold text-sm text-[#1A1A1A]">{{ $review->user->name }}</span>
                                            @include('partials.star-rating', ['rating' => $review->rating])
                                        </div>
                                        @if ($review->comment)
                                            <p class="text-[#5A5A5A] text-xs sm:text-sm leading-relaxed">{{ $review->comment }}</p>
                                        @endif

                                        {{-- FOTO ULASAN --}}
                                        @if (!empty($review->photos) && is_array($review->photos))
                                            <div class="flex flex-wrap gap-2 mt-3">
                                                @foreach ($review->photoUrls() as $photoUrl)
                                                    <button type="button" @click="lightboxOpen = true; lightboxImg = '{{ $photoUrl }}'"
                                                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-sm overflow-hidden border border-neutral-200 hover:border-[#B8860B] transition group relative">
                                                        <img src="{{ $photoUrl }}" alt="Foto ulasan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-3 mt-2.5">
                                            <span class="text-[11px] text-[#5A5A5A]">{{ $review->created_at->diffForHumans() }}</span>
                                            <button type="button" @click="editing = true"
                                                    class="text-[11px] font-semibold text-[#B8860B] hover:text-[#8C3B2B] hover:underline">
                                                Edit Ulasan
                                            </button>
                                        </div>
                                    </div>

                                    {{-- MODE EDIT --}}
                                    <div x-show="editing" x-cloak>
                                        <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="flex flex-row-reverse justify-end mb-3">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" value="{{ $i }}"
                                                           id="edit-star{{ $review->id }}-{{ $i }}"
                                                           class="hidden peer"
                                                           {{ $i == $review->rating ? 'checked' : '' }} required>
                                                    <label for="edit-star{{ $review->id }}-{{ $i }}"
                                                           class="cursor-pointer text-2xl text-neutral-300 peer-checked:text-[#B8860B] hover:text-[#B8860B] hover:[&~label]:text-[#B8860B]">
                                                        ★
                                                    </label>
                                                @endfor
                                            </div>

                                            <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman kunjungan kamu (opsional)"
                                                      class="w-full rounded-sm border border-neutral-200 p-3 text-[#1A1A1A] text-xs sm:text-sm focus:outline-none focus:border-[#B8860B] mb-3">{{ $review->comment }}</textarea>

                                            <div class="mb-3">
                                                <label class="block text-xs font-semibold text-[#1A1A1A] mb-1">Tambah Foto (Maks 3 Foto)</label>
                                                <input type="file" name="photos[]" multiple accept="image/*"
                                                       class="text-xs text-[#5A5A5A] file:mr-3 file:py-1.5 file:px-3 file:rounded-sm file:border file:border-neutral-200 file:text-xs file:font-semibold file:bg-[#F8F7F4] file:text-[#1A1A1A] hover:file:border-[#B8860B]">
                                            </div>

                                            <div class="flex gap-2">
                                                <button type="submit"
                                                        class="px-5 py-2 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                                                    Simpan
                                                </button>
                                                <button type="button" @click="editing = false"
                                                        class="px-5 py-2 rounded-sm border border-neutral-300 text-[#5A5A5A] text-xs font-semibold hover:bg-[#F8F7F4] transition duration-300">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    {{-- REVIEW ORANG LAIN --}}
                                    <div class="flex items-center justify-between gap-3 mb-1.5">
                                        <span class="font-semibold text-sm text-[#1A1A1A]">{{ $review->user->name }}</span>
                                        @include('partials.star-rating', ['rating' => $review->rating])
                                    </div>
                                    @if ($review->comment)
                                        <p class="text-[#5A5A5A] text-xs sm:text-sm leading-relaxed">{{ $review->comment }}</p>
                                    @endif

                                    {{-- FOTO ULASAN --}}
                                    @if (!empty($review->photos) && is_array($review->photos))
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            @foreach ($review->photoUrls() as $photoUrl)
                                                <button type="button" @click="lightboxOpen = true; lightboxImg = '{{ $photoUrl }}'"
                                                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-sm overflow-hidden border border-neutral-200 hover:border-[#B8860B] transition group relative">
                                                    <img src="{{ $photoUrl }}" alt="Foto ulasan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-3 mt-2.5">
                                        <span class="text-[11px] text-[#5A5A5A]">{{ $review->created_at->diffForHumans() }}</span>
                                        @auth
                                            <button type="button"
                                                    @click="reportModalOpen = true; reportReviewId = {{ $review->id }}"
                                                    class="text-[11px] font-semibold text-[#5A5A5A] hover:text-[#8C3B2B] transition">
                                                Laporkan
                                            </button>
                                        @endauth
                                    </div>
                                @endif

                                {{-- RESPON RESMI PENGELOLA MUSEUM --}}
                                @if($review->admin_reply)
                                    <div class="mt-4 p-4 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-[#5A5A5A] text-xs">
                                        <div class="flex items-center justify-between gap-2 font-semibold text-[#1A1A1A] mb-1.5">
                                            <span class="flex items-center gap-2">
                                                <span>Respon Resmi Pengelola</span>
                                                <span class="px-2 py-0.5 rounded-sm bg-[#1A1A1A] text-[#C59B27] text-[9px] uppercase tracking-wider font-semibold">Terverifikasi</span>
                                            </span>
                                            <span class="text-[10px] text-[#5A5A5A] font-normal">
                                                {{ $review->admin_replied_at?->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="leading-relaxed pl-3 border-l-2 border-[#B8860B]">
                                            {{ $review->admin_reply }}
                                        </p>
                                    </div>
                                @endif

                            </div>

                        </div>
                    @empty
                        <p class="text-[#5A5A5A] text-sm text-center py-6">Belum ada ulasan untuk museum ini.</p>
                    @endforelse
                </div>

                @auth
                    @php
                        $reviewableBooking = auth()->user()->bookings()
                            ->where('museum_id', $museum->id)
                            ->whereHas('transactions', fn($q) => $q->where('payment_status', 'paid')->whereNotNull('used_at'))
                            ->whereDoesntHave('review')
                            ->first();
                    @endphp

                    @if ($reviewableBooking)
                        <div class="bg-[#F8F7F4] border border-neutral-200 rounded-sm p-6 mt-6">
                            <h4 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-3">Beri Ulasan Kunjungan Anda</h4>

                            <form action="{{ route('reviews.store', $reviewableBooking->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="flex flex-row-reverse justify-end mb-3" id="ratingInput">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden peer" required>
                                        <label for="star{{ $i }}"
                                               class="cursor-pointer text-3xl text-neutral-300 peer-checked:text-[#B8860B] hover:text-[#B8860B] hover:[&~label]:text-[#B8860B]">
                                            ★
                                        </label>
                                    @endfor
                                </div>

                                <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman kunjungan kamu (opsional)"
                                          class="w-full rounded-sm border border-neutral-200 p-3 text-[#1A1A1A] text-sm focus:outline-none focus:border-[#B8860B] mb-3 bg-white"></textarea>

                                {{-- UNGGAH FOTO PENGALAMAN --}}
                                <div x-data="{ photoCount: 0, previews: [] }" class="mb-4">
                                    <label class="block text-xs font-semibold text-[#1A1A1A] mb-1.5">
                                        Unggah Foto Pengalaman (Maksimal 3 Foto, opsional)
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <label class="px-4 py-2 rounded-sm bg-white border border-neutral-200 text-xs font-semibold text-[#1A1A1A] hover:border-[#B8860B] transition cursor-pointer">
                                            <span>Pilih Foto</span>
                                            <input type="file" name="photos[]" multiple accept="image/*" class="hidden"
                                                   @change="
                                                       previews = [];
                                                       let files = Array.from($event.target.files).slice(0, 3);
                                                       photoCount = files.length;
                                                       files.forEach(f => {
                                                           let r = new FileReader();
                                                           r.onload = (e) => previews.push(e.target.result);
                                                           r.readAsDataURL(f);
                                                       });
                                                   ">
                                        </label>
                                        <span class="text-xs text-[#5A5A5A]" x-text="photoCount > 0 ? photoCount + ' foto dipilih (maks 3)' : 'Format: JPG, PNG, WEBP (maks 2MB)'"></span>
                                    </div>
                                    <template x-if="previews.length > 0">
                                        <div class="flex gap-2 mt-3">
                                            <template x-for="(p, idx) in previews" :key="idx">
                                                <div class="w-16 h-16 rounded-sm overflow-hidden border border-[#B8860B] relative">
                                                    <img :src="p" class="w-full h-full object-cover">
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                <button type="submit"
                                        class="px-6 py-2.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                                    Kirim Ulasan &amp; Foto
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                {{-- MODAL LAPORKAN ULASAN --}}
                <div x-show="reportModalOpen" x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center bg-[#121212]/70 p-6"
                     @click.self="reportModalOpen = false">
                    <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8 max-w-md w-full">
                        <h3 class="font-serif text-xl font-semibold text-[#1A1A1A] mb-1">Laporkan Ulasan</h3>
                        <p class="text-xs sm:text-sm text-[#5A5A5A] mb-5">Beri tahu kami mengapa ulasan ini perlu ditinjau kurator.</p>

                        <form :action="'/reviews/' + reportReviewId + '/report'" method="POST">
                            @csrf
                            <textarea name="reason" rows="3" required
                                      placeholder="Contoh: konten tidak relevan, spam, atau bahasa tidak pantas"
                                      class="w-full rounded-sm border border-neutral-200 p-3 text-xs sm:text-sm text-[#1A1A1A] focus:outline-none focus:border-[#8C3B2B] mb-4"></textarea>

                            <div class="flex gap-3">
                                <button type="button" @click="reportModalOpen = false"
                                        class="flex-1 py-2.5 rounded-sm border border-neutral-300 text-[#5A5A5A] text-xs font-semibold hover:bg-[#F8F7F4] transition">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="flex-1 py-2.5 rounded-sm bg-[#8C3B2B] text-white text-xs font-semibold hover:bg-[#1A1A1A] transition">
                                    Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- LIGHTBOX MODAL FOTO ULASAN --}}
                <div x-show="lightboxOpen" x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center bg-[#121212]/85 p-4"
                     @click.self="lightboxOpen = false">
                    <div class="relative max-w-3xl w-full max-h-[85vh] flex flex-col items-center">
                        <button type="button" @click="lightboxOpen = false"
                                class="absolute -top-10 right-0 text-white font-semibold text-xs uppercase tracking-wider bg-white/15 hover:bg-white/25 px-3 py-1.5 rounded-sm transition">
                            ✕ Tutup
                        </button>
                        <img :src="lightboxImg" alt="Pratinjau Foto Ulasan" class="max-w-full max-h-[80vh] rounded-sm object-contain border border-neutral-700">
                    </div>
                </div>
            </div>

            {{-- LOCATION MAP --}}
            <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8">
                <div class="mb-6 pb-4 border-b border-neutral-200">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">Akses &amp; Peta</p>
                    <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A]">
                        Peta Lokasi
                    </h2>
                </div>

                <div class="rounded-sm overflow-hidden border border-neutral-200 mb-5">
                    <iframe src="https://www.google.com/maps?q={{ urlencode($museum->address) }}&output=embed"
                            width="100%"
                            height="350"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy">
                    </iframe>
                </div>

                <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($museum->address) }}&travelmode=driving"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="w-full flex items-center justify-center gap-2 py-3.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <span>Buka Petunjuk Arah</span>
                </a>
            </div>

            {{-- REKOMENDASI RUTE & MUSEUM TERDEKAT --}}
            @if(isset($nearbyMuseums) && $nearbyMuseums->count() > 0)
                <div class="bg-white rounded-sm border border-neutral-200 p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-6 pb-4 border-b border-neutral-200">
                        <div>
                            <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] block mb-1">
                                RUTE WISATA 1 HARI
                            </span>
                            <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A]">
                                Destinasi &amp; Museum Terdekat
                            </h2>
                        </div>
                        <span class="text-xs text-[#5A5A5A]">
                            Lanjutkan rute jelajah budaya Anda
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach($nearbyMuseums as $index => $nearby)
                            @php
                                $todayBoundsNearby = $nearby->operatingBoundsForDate(now());
                                $nowTime = now()->format('H:i');
                                $isNearbyOpen = false;
                                if (!$nearby->isClosedOnDate(now()) && $todayBoundsNearby && $nowTime >= $todayBoundsNearby['open'] && $nowTime <= $todayBoundsNearby['close']) {
                                    $isNearbyOpen = true;
                                }
                                $lowestNearbyPrice = $nearby->lowestTicketPrice();
                            @endphp

                            <div class="group bg-[#F8F7F4] rounded-sm border border-neutral-200 hover:border-[#B8860B] p-4 transition duration-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0 flex-1">
                                    <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-sm overflow-hidden shrink-0 bg-neutral-200 border border-neutral-200">
                                        <img src="{{ $nearby->image
                                                ? (Str::startsWith($nearby->image, 'storage/')
                                                    ? asset($nearby->image)
                                                    : asset('storage/' . $nearby->image))
                                                : asset('images/institutional/hero-museum.jpg') }}"
                                             alt="{{ $nearby->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                        <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded-sm bg-[#121212]/90 text-[#C59B27] text-[9px] font-semibold">
                                            #{{ $index + 1 }}
                                        </span>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded-sm text-[9px] font-semibold uppercase tracking-wider bg-white text-[#8C3B2B] border border-neutral-200">
                                                {{ ucfirst($nearby->category ?? 'Museum') }}
                                            </span>
                                            <span class="text-[10px] font-semibold {{ $isNearbyOpen ? 'text-emerald-700' : 'text-[#8C3B2B]' }}">
                                                {{ $isNearbyOpen ? '● Buka' : '○ Tutup' }}
                                            </span>
                                        </div>

                                        <h3 class="font-serif font-semibold text-base text-[#1A1A1A] truncate group-hover:text-[#8C3B2B] transition-colors duration-300">
                                            {{ $nearby->name }}
                                        </h3>

                                        <p class="text-[11px] text-[#5A5A5A] truncate mt-0.5">
                                            {{ $nearby->address }}
                                        </p>

                                        <div class="flex items-center gap-2 mt-2 text-[11px] text-[#5A5A5A] flex-wrap">
                                            <span class="font-semibold text-[#1A1A1A] bg-white px-2 py-0.5 rounded-sm border border-neutral-200">
                                                {{ $nearby->trail_distance }}
                                            </span>
                                            <span>•</span>
                                            <span>Est. {{ $nearby->trail_time }}</span>
                                            <span>•</span>
                                            <span class="font-semibold text-[#B8860B]">
                                                @if($lowestNearbyPrice !== null && $lowestNearbyPrice > 0)
                                                    Rp {{ number_format($lowestNearbyPrice, 0, ',', '.') }}
                                                @else
                                                    Gratis
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex sm:flex-col items-stretch gap-2 w-full sm:w-auto shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-neutral-200">
                                    <a href="{{ route('museum.detail', $nearby->id) }}"
                                       class="flex-1 sm:flex-none px-4 py-2 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300 text-center">
                                        Lihat Museum →
                                    </a>
                                    <a href="{{ $nearby->trail_maps_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="flex-1 sm:flex-none px-3 py-1.5 rounded-sm border border-neutral-300 text-[#5A5A5A] hover:text-[#1A1A1A] hover:border-[#1A1A1A] text-xs font-medium transition duration-300 text-center">
                                        Rute Maps
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div>
            <div class="sticky top-28 space-y-6">

                {{-- QUICK INFO --}}
                <div class="bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6 sm:p-7">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B8860B] font-semibold mb-1">Reservasi Kunjungan</p>
                    <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A] mb-6 pb-4 border-b border-neutral-200">
                        Informasi Cepat
                    </h2>

                    <div class="space-y-4 text-xs sm:text-sm">
                        <div class="flex justify-between gap-3">
                            <span class="text-[#5A5A5A]">Jam Buka Hari Ini</span>
                            <span class="font-semibold text-[#1A1A1A] text-right">
                                @php $todaySessions = $museum->sessionsForDate(now()); @endphp
                                @if ($museum->isClosedOnDate(now()))
                                    <span class="text-[#8C3B2B]">Tutup</span>
                                @else
                                    {{ collect($todaySessions)->map(fn($s) => "{$s['open']}–{$s['close']}")->implode(', ') }}
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-[#5A5A5A]">Kategori</span>
                            <span class="font-semibold text-[#1A1A1A]">
                                {{ ucfirst($museum->category ?? 'Museum') }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-[#5A5A5A]">Estimasi Durasi</span>
                            <span class="font-semibold text-[#1A1A1A]">
                                2–3 Jam
                            </span>
                        </div>

                        <div class="border-t border-neutral-200 pt-4">
                            @php
                                $lowestDetailPrice = $museum->relationLoaded('tickets') ? $museum->tickets->min('price') : $museum->lowestTicketPrice();
                            @endphp
                            <p class="text-[11px] uppercase tracking-wider text-[#5A5A5A]">Mulai dari</p>
                            <p class="font-sans text-2xl font-semibold text-[#B8860B] mt-0.5">
                                Rp {{ number_format($lowestDetailPrice ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    @if($museum->isUnderRenovation())
                        <div class="mt-6 p-4 rounded-sm bg-amber-50 border border-amber-200 text-amber-900 text-center space-y-1.5">
                            <p class="font-semibold text-xs uppercase tracking-wider">Sedang Renovasi</p>
                            <p class="text-[11px] text-amber-800 leading-relaxed">
                                Museum ini sedang dalam pemeliharaan berkala. Pemesanan tiket dibuka kembali setelah renovasi selesai.
                            </p>
                        </div>
                    @elseif($museum->isInactive())
                        <div class="mt-6 p-4 rounded-sm bg-rose-50 border border-rose-200 text-[#8C3B2B] text-center space-y-1.5">
                            <p class="font-semibold text-xs uppercase tracking-wider">Kemitraan Nonaktif</p>
                            <p class="text-[11px] leading-relaxed">
                                Saat ini museum tidak membuka reservasi tiket secara daring.
                            </p>
                        </div>
                    @else
                        <a href="{{ auth()->check()
                            ? route('user.booking', $museum->id)
                            : route('login') }}"
                           class="mt-6 w-full flex justify-center py-3.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                            Pesan Tiket Sekarang
                        </a>
                    @endif
                </div>

                {{-- JADWAL OPERASIONAL --}}
                <div class="bg-white rounded-sm border border-neutral-200 p-6" x-data="{ scheduleOpen: false }">
                    @php
                        $todayBounds = $museum->operatingBoundsForDate(now());
                        $nowTime = now()->format('H:i');
                        $isOpenNow = $todayBounds && $nowTime >= $todayBounds['open'] && $nowTime <= $todayBounds['close'];

                        if ($isOpenNow) {
                            $summary = 'Tutup pukul ' . $todayBounds['close'];
                        } else {
                            $next = $museum->nextOpening();

                            if ($next) {
                                $dayLabels = \App\Models\Museum::dayLabels();
                                $dayKeys = \App\Models\Museum::dayKeys();
                                $label = $next['is_today'] ? 'hari ini' : $dayLabels[$dayKeys[$next['date']->dayOfWeekIso - 1]];
                                $summary = 'Buka ' . $label . ' pukul ' . $next['time'];
                            } else {
                                $summary = 'Jadwal belum tersedia';
                            }
                        }
                    @endphp

                    <button type="button" @click="scheduleOpen = !scheduleOpen"
                            class="w-full flex items-center justify-between gap-3 text-left">
                        <div>
                            <h3 class="font-serif text-lg font-semibold text-[#1A1A1A]">Jadwal Operasional</h3>
                            <p class="text-xs mt-1">
                                <span class="font-semibold {{ $isOpenNow ? 'text-emerald-700' : 'text-[#8C3B2B]' }}">
                                    {{ $isOpenNow ? 'Buka' : 'Tutup' }}
                                </span>
                                <span class="text-[#5A5A5A]"> · {{ $summary }}</span>
                            </p>
                        </div>

                        <svg class="w-4 h-4 text-[#B8860B] transition-transform duration-300 shrink-0"
                             :class="scheduleOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="scheduleOpen" x-collapse x-cloak class="mt-4 pt-4 border-t border-neutral-200 space-y-1">
                        @php
                            $dayLabels = \App\Models\Museum::dayLabels();
                            $todayKey = \App\Models\Museum::dayKeys()[now()->dayOfWeekIso - 1];
                        @endphp

                        @foreach ($dayLabels as $dayKey => $dayLabel)
                            @php
                                $schedule = $museum->operational_hours[$dayKey] ?? ['closed' => true, 'sessions' => []];
                                $isToday = $dayKey === $todayKey;
                                $isClosed = $schedule['closed'] ?? true;
                            @endphp

                            <div class="flex items-start justify-between gap-2 px-3 py-2 rounded-sm {{ $isToday ? 'bg-[#F8F7F4]' : '' }}">
                                <span class="text-xs {{ $isToday ? 'font-semibold text-[#1A1A1A]' : 'text-[#5A5A5A]' }}">
                                    {{ $dayLabel }}
                                </span>

                                @if ($isClosed)
                                    <span class="text-xs font-medium text-[#8C3B2B]">Tutup</span>
                                @else
                                    <div class="flex flex-col items-end gap-0.5">
                                        @foreach ($schedule['sessions'] as $session)
                                            <span class="text-xs font-medium text-[#1A1A1A]">{{ $session['open'] }}–{{ $session['close'] }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- FASILITAS --}}
                <div class="bg-white rounded-sm border border-neutral-200 p-6">
                    <h3 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-4 pb-3 border-b border-neutral-200">
                        Fasilitas Pengunjung
                    </h3>

                    @if (!empty($museum->facilities))
                        <ul class="space-y-2.5 text-xs sm:text-sm text-[#5A5A5A]">
                            @foreach ($museum->facilities as $facility)
                                <li class="flex items-center gap-2.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#B8860B] shrink-0"></span>
                                    <span>{{ $facility }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-[#5A5A5A]">Informasi fasilitas belum tersedia.</p>
                    @endif
                </div>

            </div>
        </div>

    </div>

</div>

@endsection
