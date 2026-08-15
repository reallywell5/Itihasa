@extends('layouts.user')

@section('title', $museum->name)

@section('content')

{{-- HERO IMAGE --}}
<section class="relative h-64 sm:h-80 lg:h-[440px] overflow-hidden rounded-3xl border border-[#EADBC8] shadow-lg mb-6">
    <img src="{{ $museum->image
        ? (Str::startsWith($museum->image, 'storage/')
            ? asset($museum->image)
            : asset('storage/' . $museum->image))
        : asset('images/default-museum.jpg') }}"
        alt="{{ $museum->name }}"
        class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
</section>

{{-- FLOATING DETAIL --}}
<section class="relative z-20 max-w-7xl mx-auto pb-12">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 lg:gap-8">

        {{-- LEFT --}}
        <div class="space-y-8">

            {{-- MAIN INFO --}}
            <div class="bg-white rounded-3xl shadow-sm border border-[#EADBC8] p-5 sm:p-7">

                <div class="mb-4">
                    <button onclick="history.back()"
                        class="inline-flex items-center gap-1.5 text-xs text-[#102A43] hover:text-[#B88A44] font-semibold transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali
                    </button>
                </div>

                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <p class="uppercase text-[11px] tracking-[0.25em] text-[#B88A44] font-bold mb-1.5">
                            Koleksi Bersejarah
                        </p>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#102A43]">
                            {{ $museum->name }}
                        </h1>
                    </div>

                    <div class="flex items-center gap-2">

                        {{-- SHARE BUTTON --}}
                        <div class="relative" x-data="{ shareOpen: false, copied: false }">
                            <button type="button"
                                    @click="shareOpen = !shareOpen"
                                    @click.outside="shareOpen = false"
                                    class="w-10 h-10 rounded-full flex items-center justify-center bg-[#F6F1E8] text-[#B88A44] hover:bg-[#EADBC8] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.684 13.342a3 3 0 100 2.316m0-2.316a3 3 0 100-2.316m0 2.316l6.632 3.316m-6.632-5.632l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 8.632a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                            </button>

                            <div x-show="shareOpen" x-cloak x-transition
                                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl border border-[#EADBC8] shadow-xl p-2 z-30">

                                <a href="https://wa.me/?text={{ urlencode('Yuk kunjungi ' . $museum->name . '! ' . url()->current()) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-[#F9F7F2] transition text-sm text-slate-700">
                                    <span class="text-lg">💬</span> Bagikan ke WhatsApp
                                </a>

                                <button type="button"
                                        @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-[#F9F7F2] transition text-sm text-slate-700 text-left">
                                    <span class="text-lg" x-text="copied ? '✅' : '🔗'"></span>
                                    <span x-text="copied ? 'Link tersalin!' : 'Salin Link'"></span>
                                </button>

                            </div>
                        </div>

                        @auth
                            @php
                                $isWishlisted = $museum->wishlists->contains('user_id', auth()->id());
                            @endphp

                            <form action="{{ route('user.wishlist.store', $museum->id) }}" method="POST">
                                @csrf
                                <button
                                    class="w-10 h-10 rounded-full flex items-center justify-center transition
                                    {{ $isWishlisted ? 'bg-red-100 text-red-500' : 'bg-[#F6F1E8] text-[#B88A44]' }}">
                                    {{ $isWishlisted ? '♥' : '♡' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                            class="w-10 h-10 rounded-full flex items-center justify-center bg-[#F6F1E8] text-[#B88A44]">
                                ♡
                            </a>
                        @endauth

                    </div>

                </div>

                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <span class="text-slate-400">
                        • {{ $museum->address }}
                    </span>
                </div>

                {{-- Multi paragraph description --}}
                <div class="text-slate-600 leading-relaxed text-lg space-y-5">
                    @foreach(explode("\n", $museum->description) as $paragraph)
                        @if(trim($paragraph))
                            <p>{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>

            </div>

            {{-- GALLERY --}}
            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8">

                <h3 class="text-xl font-bold text-[#102A43] mb-6">
                    Galeri Foto
                </h3>

                @if ($museum->galleries->isNotEmpty())
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach ($museum->galleries as $photo)
                            <button type="button"
                                    onclick="openGalleryModal('{{ asset('storage/' . $photo->image_path) }}')"
                                    class="group relative rounded-2xl overflow-hidden border border-[#EADBC8] aspect-square">
                                <img src="{{ asset('storage/' . $photo->image_path) }}"
                                    alt="{{ $photo->caption ?? $museum->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </button>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-400">Belum ada foto galeri untuk museum ini.</p>
                @endif

            </div>

            {{-- GALLERY MODAL --}}
            <div id="galleryModal"
                class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-6"
                onclick="closeGalleryModal()">
                <img id="galleryModalImg" src="" alt="Preview"
                    class="max-w-3xl max-h-[80vh] rounded-2xl shadow-2xl object-contain">
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
            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8" x-data="{ reportModalOpen: false, reportReviewId: null }">

                <h3 class="text-xl font-bold text-[#102A43] mb-6">
                    Ulasan Pengunjung
                </h3>

                @php
                    $allReviews = $museum->reviews()->with('user')->latest()->get();
                    $totalReviews = $allReviews->count();
                    $avgRating = $totalReviews ? round($allReviews->avg('rating'), 1) : 0;
                    $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                    foreach ($allReviews as $r) {
                        $ratingCounts[$r->rating] = ($ratingCounts[$r->rating] ?? 0) + 1;
                    }
                @endphp

                {{-- RATING SUMMARY --}}
                <div class="flex flex-col sm:flex-row gap-8 items-start sm:items-center bg-[#F9F7F2] rounded-2xl p-6 mb-8">

                    <div class="text-center shrink-0">
                        <p class="text-5xl font-bold text-[#102A43]">{{ number_format($avgRating, 1) }}</p>
                        @include('partials.star-rating', ['rating' => $avgRating])
                        <p class="text-xs text-slate-400 mt-1">{{ $totalReviews }} ulasan</p>
                    </div>

                    <div class="flex-1 w-full space-y-1.5">
                        @for ($star = 5; $star >= 1; $star--)
                            @php
                                $count = $ratingCounts[$star] ?? 0;
                                $percent = $totalReviews ? round(($count / $totalReviews) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-3 text-sm">
                                <span class="w-3 text-slate-500">{{ $star }}</span>
                                <div class="flex-1 h-2 bg-[#EADBC8]/50 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#B88A44] rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="w-6 text-right text-slate-400 text-xs">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>

                </div>

                <div class="space-y-5">
                    @forelse ($allReviews as $review)
                        <div class="flex gap-4 border-b border-[#EADBC8] pb-5 last:border-0"
                            @if(auth()->check() && auth()->id() === $review->user_id) x-data="{ editing: false }" @endif>

                            <div class="w-11 h-11 shrink-0 rounded-full bg-[#102A43] text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">

                                @if (auth()->check() && auth()->id() === $review->user_id)
                                    {{-- MODE TAMPIL --}}
                                    <div x-show="!editing">
                                        <div class="flex items-center justify-between gap-3 mb-1">
                                            <span class="font-semibold text-[#102A43]">{{ $review->user->name }}</span>
                                            @include('partials.star-rating', ['rating' => $review->rating])
                                        </div>
                                        @if ($review->comment)
                                            <p class="text-slate-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                            <button type="button" @click="editing = true"
                                                    class="text-xs font-semibold text-[#B88A44] hover:underline">
                                                Edit Ulasan
                                            </button>
                                        </div>
                                    </div>

                                    {{-- MODE EDIT --}}
                                    <div x-show="editing" x-cloak>
                                        <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="flex flex-row-reverse justify-end mb-3">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" value="{{ $i }}"
                                                        id="edit-star{{ $review->id }}-{{ $i }}"
                                                        class="hidden peer"
                                                        {{ $i == $review->rating ? 'checked' : '' }} required>
                                                    <label for="edit-star{{ $review->id }}-{{ $i }}"
                                                        class="cursor-pointer text-2xl text-[#D9CDBB] peer-checked:text-[#B88A44] hover:text-[#B88A44] hover:[&~label]:text-[#B88A44]">
                                                        ★
                                                    </label>
                                                @endfor
                                            </div>

                                            <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman kunjungan kamu (opsional)"
                                                    class="w-full rounded-xl border border-[#EADBC8] p-3 text-slate-600 text-sm focus:outline-none focus:ring-2 focus:ring-[#B88A44] mb-3">{{ $review->comment }}</textarea>

                                            <div class="flex gap-2">
                                                <button type="submit"
                                                        class="px-5 py-2 rounded-xl bg-[#102A43] text-white text-sm font-semibold hover:bg-[#0c2238] transition">
                                                    Simpan
                                                </button>
                                                <button type="button" @click="editing = false"
                                                        class="px-5 py-2 rounded-xl border border-[#EADBC8] text-slate-500 text-sm font-semibold hover:bg-[#F9F7F2] transition">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    {{-- REVIEW ORANG LAIN --}}
                                    <div class="flex items-center justify-between gap-3 mb-1">
                                        <span class="font-semibold text-[#102A43]">{{ $review->user->name }}</span>
                                        @include('partials.star-rating', ['rating' => $review->rating])
                                    </div>
                                    @if ($review->comment)
                                        <p class="text-slate-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                                    @endif
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                        @auth
                                            <button type="button"
                                                    @click="reportModalOpen = true; reportReviewId = {{ $review->id }}"
                                                    class="text-xs font-semibold text-slate-400 hover:text-red-500 transition">
                                                Laporkan
                                            </button>
                                        @endauth
                                    </div>
                                @endif

                            </div>

                        </div>
                    @empty
                        <p class="text-slate-400 text-center py-6">Belum ada ulasan untuk museum ini.</p>
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
                        <div class="bg-[#F6F1E8] rounded-2xl p-6 mb-8">
                            <h4 class="font-semibold text-[#102A43] mb-3">Beri Ulasan Kunjungan Kamu</h4>

                            <form action="{{ route('reviews.store', $reviewableBooking->id) }}" method="POST">
                                @csrf

                                <div class="flex flex-row-reverse justify-end mb-3" id="ratingInput">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden peer" required>
                                        <label for="star{{ $i }}"
                                            class="cursor-pointer text-3xl text-[#D9CDBB] peer-checked:text-[#B88A44] hover:text-[#B88A44] hover:[&~label]:text-[#B88A44]">
                                            ★
                                        </label>
                                    @endfor
                                </div>

                                <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman kunjungan kamu (opsional)"
                                        class="w-full rounded-xl border border-[#EADBC8] p-3 text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#B88A44] mb-3"></textarea>

                                <button type="submit"
                                        class="px-6 py-2.5 rounded-xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                {{-- MODAL LAPORKAN ULASAN --}}
                <div x-show="reportModalOpen" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6"
                    @click.self="reportModalOpen = false">
                    <div class="bg-white rounded-[28px] p-8 max-w-md w-full">
                        <h3 class="text-xl font-bold text-[#102A43] mb-1">Laporkan Ulasan</h3>
                        <p class="text-sm text-slate-500 mb-5">Beri tahu kami kenapa ulasan ini perlu ditinjau.</p>

                        <form :action="'/reviews/' + reportReviewId + '/report'" method="POST">
                            @csrf

                            <textarea name="reason" rows="3" required
                                    placeholder="Contoh: konten tidak relevan, spam, atau bahasa tidak pantas"
                                    class="w-full rounded-xl border border-[#EADBC8] p-3 text-slate-600 mb-4"></textarea>

                            <div class="flex gap-3">
                                <button type="button" @click="reportModalOpen = false"
                                        class="flex-1 py-2.5 rounded-xl border border-[#EADBC8] text-slate-500 font-semibold">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="flex-1 py-2.5 rounded-xl bg-red-500 text-white font-semibold hover:bg-red-600 transition">
                                    Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- LOCATION MAP --}}
            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8">

                <h3 class="text-xl font-bold text-[#102A43] mb-6">
                    Location Map
                </h3>

                <div class="rounded-2xl overflow-hidden border border-[#EADBC8] mb-5">

                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode($museum->address) }}&output=embed"
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
                class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>

                    Buka Rute ke Museum

                </a>

            </div>

        </div>

        {{-- RIGHT --}}
        <div>

            <div class="sticky top-28 space-y-6">

                {{-- QUICK INFO --}}
                <div class="bg-white rounded-[28px] border border-[#EADBC8] shadow-sm p-8">

                    <h2 class="text-2xl font-bold text-[#102A43] mb-6">
                        Informasi Cepat
                    </h2>

                    <div class="space-y-5">

                        <div class="flex justify-between">
                            <span class="text-slate-500">Jam Buka Hari Ini</span>
                            <span class="font-semibold text-[#102A43]">
                                @php $todaySessions = $museum->sessionsForDate(now()); @endphp
                                @if ($museum->isClosedOnDate(now()))
                                    <span class="text-red-500">Tutup</span>
                                @else
                                    {{ collect($todaySessions)->map(fn($s) => "{$s['open']}-{$s['close']}")->implode(', ') }}
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Kategori</span>
                            <span class="font-semibold text-[#102A43]">
                                Museum
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Durasi</span>
                            <span class="font-semibold text-[#102A43]">
                                2-3 Jam
                            </span>
                        </div>

                        <div class="border-t pt-5 flex justify-between items-center">

                            <div>
                                <p class="text-sm text-slate-400">
                                    Mulai dari
                                </p>

                                <h3 class="text-2xl font-bold text-[#B88A44]">
                                    Rp {{ number_format($museum->lowestTicketPrice() ?? 0, 0, ',', '.') }}
                                </h3>
                            </div>

                        </div>

                    </div>

                    <a href="{{ auth()->check()
                        ? route('user.booking', $museum->id)
                        : route('login') }}"
                       class="mt-8 w-full flex justify-center py-4 rounded-2xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                        Pesan Tiket
                    </a>

                </div>

                {{-- JADWAL OPERASIONAL --}}
                <div class="bg-white rounded-[28px] border border-[#EADBC8] p-6" x-data="{ scheduleOpen: false }">

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
                            <h3 class="text-lg font-bold text-[#102A43]">Jadwal Operasional</h3>
                            <p class="text-sm mt-1">
                                <span class="font-semibold {{ $isOpenNow ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $isOpenNow ? 'Buka' : 'Tutup' }}
                                </span>
                                <span class="text-slate-400">· {{ $summary }}</span>
                            </p>
                        </div>

                        <svg class="w-5 h-5 text-[#B88A44] transition-transform shrink-0"
                            :class="scheduleOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="scheduleOpen" x-collapse x-cloak class="mt-4 pt-4 border-t border-[#EADBC8] space-y-1">
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

                            <div class="flex items-start justify-between gap-2 px-3 py-2 rounded-xl {{ $isToday ? 'bg-[#F6F1E8]' : '' }}">
                                <span class="text-sm {{ $isToday ? 'font-bold text-[#102A43]' : 'text-slate-600' }}">
                                    {{ $dayLabel }}
                                </span>

                                @if ($isClosed)
                                    <span class="text-xs font-semibold text-red-500">Tutup</span>
                                @else
                                    <div class="flex flex-col items-end gap-0.5">
                                        @foreach ($schedule['sessions'] as $session)
                                            <span class="text-xs font-semibold text-slate-700">{{ $session['open'] }}–{{ $session['close'] }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                </div>

                {{-- FASILITAS --}}
                <div class="bg-white rounded-[28px] border border-[#EADBC8] p-8">

                    <h3 class="text-xl font-bold text-[#102A43] mb-5">
                        Fasilitas
                    </h3>

                    @if (!empty($museum->facilities))
                        <div class="space-y-4 text-slate-600">
                            @foreach ($museum->facilities as $facility)
                                <p>✔ {{ $facility }}</p>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-400">Informasi fasilitas belum tersedia.</p>
                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
