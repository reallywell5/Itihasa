@auth
@php
    $banners = app(\App\Services\NotificationService::class)->getActiveBanners(Auth::user());
    $popups = app(\App\Services\NotificationService::class)->getActivePopups(Auth::user());
@endphp

{{-- 1. BROADCAST BANNERS (ATAS HALAMAN) --}}
@if($banners->isNotEmpty())
    <div class="space-y-2 mb-4">
        @foreach($banners as $banner)
            @php
                $theme = match($banner->urgency) {
                    'danger' => ['bg' => 'bg-rose-50 border-rose-200 text-rose-900', 'btn' => 'text-rose-700 hover:bg-rose-100', 'badge' => 'bg-rose-200/80 text-rose-900', 'icon' => '🚨'],
                    'warning' => ['bg' => 'bg-amber-50 border-amber-200 text-amber-900', 'btn' => 'text-amber-700 hover:bg-amber-100', 'badge' => 'bg-amber-200/80 text-amber-900', 'icon' => '⚠️'],
                    'success' => ['bg' => 'bg-emerald-50 border-emerald-200 text-emerald-900', 'btn' => 'text-emerald-700 hover:bg-emerald-100', 'badge' => 'bg-emerald-200/80 text-emerald-900', 'icon' => '✨'],
                    default => ['bg' => 'bg-blue-50 border-blue-200 text-blue-900', 'btn' => 'text-blue-700 hover:bg-blue-100', 'badge' => 'bg-blue-200/80 text-blue-900', 'icon' => '📢'],
                };
            @endphp
            <div x-data="{ dismissed: false }"
                 x-show="!dismissed"
                 x-transition
                 class="p-3.5 sm:p-4 rounded-2xl border {{ $theme['bg'] }} shadow-xs flex items-start justify-between gap-3 text-xs leading-relaxed">
                <div class="flex items-start gap-3">
                    <span class="text-base sm:text-lg select-none shrink-0">{{ $theme['icon'] }}</span>
                    <div>
                        <div class="flex flex-wrap items-center gap-1.5 mb-0.5">
                            <span class="font-black tracking-tight text-xs sm:text-sm">{{ $banner->title }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase {{ $theme['badge'] }}">
                                {{ $banner->category ?? 'Umum' }}
                            </span>
                        </div>
                        <p class="opacity-90">{{ $banner->message }}</p>
                    </div>
                </div>

                <button type="button"
                        @click="dismissed = true; fetch('{{ route('notifications.read', 'broadcast_' . $banner->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })"
                        class="p-1 rounded-lg {{ $theme['btn'] }} transition shrink-0"
                        title="Tutup & Tandai Dibaca">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endforeach
    </div>
@endif

{{-- 2. BROADCAST POPUP MODAL (TINGKAT PENTING / DARURAT) --}}
@if($popups->isNotEmpty())
    @foreach($popups as $popup)
        <div x-data="{ openPopup: true }"
             x-show="openPopup"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
             style="display: none;">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4 relative animate-scale-up"
                 @click.outside="openPopup = false; fetch('{{ route('notifications.read', 'broadcast_' . $popup->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })">

                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-sm font-bold">
                            📢
                        </span>
                        <div>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-purple-100 text-purple-800">
                                Broadcast Resmi • {{ strtoupper($popup->category ?? 'PENGUMUMAN') }}
                            </span>
                            <h3 class="text-base font-extrabold text-slate-800 mt-0.5">
                                {{ $popup->title }}
                            </h3>
                        </div>
                    </div>
                    <button type="button"
                            @click="openPopup = false; fetch('{{ route('notifications.read', 'broadcast_' . $popup->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })"
                            class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 whitespace-pre-line">
                    {{ $popup->message }}
                </div>

                <div class="text-[10px] text-slate-400 flex items-center justify-between">
                    <span>Diterbitkan: {{ $popup->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    <span>Oleh Super Administrator</span>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="button"
                            @click="openPopup = false; fetch('{{ route('notifications.read', 'broadcast_' . $popup->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-sm transition">
                        Saya Mengerti & Tutup
                    </button>
                </div>
            </div>
        </div>
    @endforeach
@endif
@endauth

