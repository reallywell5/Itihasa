{{-- $rating: angka 0-5, bisa desimal --}}
<div class="inline-flex items-center gap-0.5" aria-label="Rating {{ $rating }} dari 5">
    @for ($i = 1; $i <= 5; $i++)
        @php
            $fill = $i <= floor($rating) ? 1 : ($i - $rating < 1 ? 0.5 : 0);
        @endphp

        <svg class="w-4 h-4" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="star-fill-{{ $i }}-{{ $loop->parent->index ?? 0 }}">
                    <stop offset="{{ $fill * 100 }}%" stop-color="#B88A44" />
                    <stop offset="{{ $fill * 100 }}%" stop-color="#E5E7EB" />
                </linearGradient>
            </defs>
            <path fill="url(#star-fill-{{ $i }}-{{ $loop->parent->index ?? 0 }})"
                  d="M10 1.5l2.6 5.6 6.1.7-4.5 4.2 1.2 6-5.4-3-5.4 3 1.2-6L1.3 7.8l6.1-.7z" />
        </svg>
    @endfor
    <span class="text-sm text-slate-500 ml-1">({{ $rating }})</span>
</div>
