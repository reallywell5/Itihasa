@extends(Auth::user()->hasAdminAccess() ? 'layouts.app' : (Auth::user()->isPetugas() ? 'layouts.petugas' : 'layouts.user'))

@section('title', 'Notification Center')

@section('content')
<div class="max-w-5xl mx-auto py-4 sm:py-6 space-y-6">

    {{-- HEADER CARD --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-7 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-sm font-bold">
                    🔔
                </span>
                <span class="text-xs font-bold uppercase tracking-widest text-[#B88A44]">
                    Pusat Informasi & Notifikasi
                </span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-slate-800">
                Notification Center
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                Pantau seluruh pengumuman resmi dari Super Admin dan pembaruan otomatis terkait aktivitas pemesanan, pembayaran, serta validasi tiket.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-800 text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Tandai Semua Dibaca ({{ $unreadCount }})</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- TABS NAVIGATION --}}
    <div class="flex items-center gap-2 p-1.5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
        <a href="{{ route('notifications.index', ['tab' => 'semua']) }}"
           class="flex-1 py-2 text-center text-xs sm:text-sm font-bold rounded-xl transition {{ $tab === 'semua' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            Semua Notifikasi
        </a>
        <a href="{{ route('notifications.index', ['tab' => 'transaksi']) }}"
           class="flex-1 py-2 text-center text-xs sm:text-sm font-bold rounded-xl transition flex items-center justify-center gap-1.5 {{ $tab === 'transaksi' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <span>🎟️</span>
            <span>Aktivitas Tiket & Transaksi</span>
        </a>
        <a href="{{ route('notifications.index', ['tab' => 'broadcast']) }}"
           class="flex-1 py-2 text-center text-xs sm:text-sm font-bold rounded-xl transition flex items-center justify-center gap-1.5 {{ $tab === 'broadcast' ? 'bg-[#102A43] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <span>📢</span>
            <span>Broadcast Resmi Super Admin</span>
        </a>
    </div>

    {{-- NOTIFICATIONS LIST --}}
    <div class="space-y-3">
        @forelse($notifications as $item)
            @php
                $isBroadcast = $item['type'] === 'broadcast';
                $urgencyBadge = match($item['urgency'] ?? 'info') {
                    'danger' => 'bg-red-50 text-red-700 border-red-200',
                    'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    default => 'bg-blue-50 text-blue-800 border-blue-200',
                };
            @endphp

            <div class="bg-white rounded-2xl border {{ $item['is_read'] ? 'border-slate-200/70' : 'border-[#B88A44]/50 shadow-sm ring-1 ring-[#B88A44]/20' }} p-4 sm:p-5 transition flex flex-col sm:flex-row sm:items-start justify-between gap-4 hover:border-slate-300">
                
                {{-- LEFT ICON & BODY --}}
                <div class="flex items-start gap-3.5 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-base shrink-0 {{ $isBroadcast ? 'bg-purple-100 text-purple-700' : 'bg-[#FAF7F2] text-[#B88A44] border border-[#EADBC8]' }}">
                        @if($isBroadcast)
                            📢
                        @else
                            {{ match($item['category']) {
                                'pesanan_baru' => '🎟️',
                                'pembayaran_sukses' => '💰',
                                'pembayaran_gagal' => '❌',
                                'validasi_gate' => '✓',
                                default => '🔔',
                            } }}
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $urgencyBadge }}">
                                {{ $item['category_label'] }}
                            </span>

                            @if(! $item['is_read'])
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-blue-600 text-white tracking-wider uppercase">
                                    Baru
                                </span>
                            @endif

                            <span class="text-[11px] text-slate-400">
                                • {{ $item['time_ago'] }} ({{ $item['created_at']->translatedFormat('d M Y, H:i') }})
                            </span>
                        </div>

                        <h3 class="font-bold text-sm sm:text-base text-slate-800 mb-1">
                            {{ $item['title'] }}
                        </h3>

                        <p class="text-xs text-slate-600 leading-relaxed max-w-2xl">
                            {{ $item['message'] }}
                        </p>

                        {{-- ACTION LINK IF AVAILABLE --}}
                        @if($item['action_url'])
                            <div class="mt-2.5">
                                <a href="{{ $item['action_url'] }}"
                                   class="inline-flex items-center gap-1 text-xs font-bold text-[#102A43] hover:text-[#B88A44] transition">
                                    <span>Lihat Rincian Selengkapnya</span>
                                    <span>→</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- RIGHT ACTION BUTTON --}}
                <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                    @if(! $item['is_read'])
                        <form action="{{ route('notifications.read', $item['key']) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold transition"
                                    title="Tandai telah dibaca">
                                Tandai Dibaca
                            </button>
                        </form>
                    @else
                        <span class="text-slate-400 text-xs font-medium flex items-center gap-1">
                            <span>✓</span> Sudah Dibaca
                        </span>
                    @endif
                </div>

            </div>
        @empty
            <div class="text-center py-16 px-4 bg-white rounded-3xl border border-slate-200/80 shadow-xs">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center text-2xl">
                    📭
                </div>
                <h3 class="font-bold text-base text-slate-800">Belum Ada Notifikasi</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                    Seluruh pemberitahuan pemesanan, pembayaran, validasi gate, dan pengumuman resmi akan dicatat secara otomatis di sini.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection

