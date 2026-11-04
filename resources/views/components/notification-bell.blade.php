@auth
@php
    $unreadCount = app(\App\Services\NotificationService::class)->getUnreadCount(Auth::user());
    $recentItems = app(\App\Services\NotificationService::class)->getRecentNotifications(Auth::user(), 6);
@endphp

<div class="relative"
     x-data="{
        notifOpen: false,
        unread: {{ (int) $unreadCount }},
        markRead(key, url) {
            fetch('{{ url('/notifications') }}/' + key + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).catch(console.error);

            if (this.unread > 0) {
                this.unread--;
            }

            if (url && url !== '#') {
                window.location.href = url;
            }
        },
        markAllRead() {
            fetch('{{ route('notifications.mark-all-read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => {
                this.unread = 0;
                window.location.reload();
            }).catch(console.error);
        }
     }"
     @click.outside="notifOpen = false">

    {{-- BELL BUTTON --}}
    <button type="button"
            @click="notifOpen = !notifOpen"
            class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition focus:outline-none focus:ring-2 focus:ring-purple-500"
            title="Pusat Notifikasi & Broadcast">
        <svg class="w-5 h-5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- UNREAD BADGE --}}
        <template x-if="unread > 0">
            <span class="absolute top-1.5 right-1.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-rose-600 text-[9px] font-black text-white ring-2 ring-white animate-pulse"
                  x-text="unread > 9 ? '9+' : unread">
            </span>
        </template>
    </button>

    {{-- NOTIFICATION DROPDOWN MENU --}}
    <div x-show="notifOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white shadow-2xl border border-slate-100 py-0 z-50 overflow-hidden divide-y divide-slate-100"
         style="display: none;">

        {{-- DROPDOWN HEADER --}}
        <div class="px-4 py-3 bg-slate-50/80 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="font-bold text-xs text-slate-800">Notification Center</span>
                <template x-if="unread > 0">
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-100 text-rose-700" x-text="unread + ' baru'"></span>
                </template>
            </div>

            <template x-if="unread > 0">
                <button type="button"
                        @click="markAllRead()"
                        class="text-[11px] font-bold text-purple-600 hover:text-purple-800 transition">
                    Tandai Semua Dibaca
                </button>
            </template>
        </div>

        {{-- RECENT LIST --}}
        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
            @forelse($recentItems as $item)
                @php
                    $isBroadcast = ($item['type'] ?? '') === 'broadcast';
                    $isRead = !empty($item['is_read']);
                    $catClass = match($item['category'] ?? '') {
                        'booking' => 'bg-blue-100 text-blue-800',
                        'payment' => 'bg-emerald-100 text-emerald-800',
                        'ticket_used' => 'bg-purple-100 text-purple-800',
                        'cancellation' => 'bg-rose-100 text-rose-800',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp
                <div @click="markRead('{{ $item['key'] }}', '{{ $item['action_url'] ?? '#' }}')"
                     class="p-3.5 hover:bg-slate-50 transition cursor-pointer flex items-start gap-3 {{ $isRead ? 'opacity-70 bg-white' : 'bg-purple-50/20' }}">

                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-sm {{ $isBroadcast ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-700' }}">
                        @if($isBroadcast)
                            📢
                        @elseif(($item['category'] ?? '') === 'payment')
                            💳
                        @elseif(($item['category'] ?? '') === 'ticket_used')
                            🎟️
                        @elseif(($item['category'] ?? '') === 'cancellation')
                            ⚠️
                        @else
                            🔔
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-extrabold uppercase {{ $catClass }}">
                                {{ $item['category'] ?? 'Umum' }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-mono shrink-0">
                                {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $item['title'] }}</p>
                        <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5 leading-snug">{{ $item['message'] }}</p>
                    </div>

                    @if(!$isRead)
                        <span class="w-2 h-2 rounded-full bg-purple-600 mt-2 shrink-0"></span>
                    @endif
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 text-xs">
                    <p class="font-bold text-slate-500 mb-0.5">Tidak ada notifikasi baru</p>
                    <p class="text-[11px]">Seluruh pembaruan tiket dan pengumuman akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        {{-- DROPDOWN FOOTER --}}
        <div class="p-2.5 bg-slate-50 text-center">
            <a href="{{ route('notifications.index') }}"
               class="text-xs font-bold text-purple-600 hover:text-purple-800 inline-flex items-center gap-1 transition">
                <span>Lihat Seluruh Riwayat Notifikasi</span>
                <span>→</span>
            </a>
        </div>
    </div>
</div>
@endauth

