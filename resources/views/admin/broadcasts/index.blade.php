@extends('layouts.app')

@section('title', 'Broadcast & Pengumuman Resmi')

@section('content')
<div class="space-y-6" x-data="{ openCreateModal: {{ $errors->any() ? 'true' : 'false' }} }">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">
                Broadcast dan Pengumuman Global
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Kirimkan instruksi, SOP operasional, kebijakan, dan pengumuman resmi Super Admin ke Super Admin, Admin Museum, Petugas Gate, maupun Pengunjung melalui Bell, Banner, atau Popup.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" @click="openCreateModal = true"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-sm transition shrink-0">
                <span>+ Buat Pengumuman Baru</span>
            </button>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-xs font-semibold">
            <p class="font-bold mb-1">Gagal menerbitkan pengumuman:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- BROADCAST LIST TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800">
                Daftar Broadcast Terbit
            </h2>
            <span class="text-xs text-slate-400">Total: {{ $broadcasts->total() }} pengumuman</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3.5">Kategori & Urgensi</th>
                        <th class="px-4 py-3.5">Tampilan</th>
                        <th class="px-4 py-3.5">Judul & Pesan</th>
                        <th class="px-4 py-3.5">Target Peran</th>
                        <th class="px-4 py-3.5">Statistik Baca</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($broadcasts as $bc)
                        <tr class="hover:bg-slate-50/70 transition align-top">
                            <td class="px-4 py-4 whitespace-nowrap space-y-1.5">
                                @php
                                    $urgencyBadge = match($bc->urgency) {
                                        'danger' => 'bg-red-50 text-red-700 border-red-200',
                                        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        default => 'bg-blue-50 text-blue-800 border-blue-200',
                                    };
                                    $catLabel = match($bc->category) {
                                        'operasional' => 'Operasional',
                                        'kebijakan' => 'Kebijakan',
                                        'pemeliharaan' => 'Maintenance',
                                        'tiket' => 'Tiket',
                                        'darurat' => 'Darurat',
                                        default => 'Umum',
                                    };
                                @endphp
                                <div>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $catLabel }}
                                    </span>
                                </div>
                                <div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase border {{ $urgencyBadge }}">
                                        {{ $bc->urgency }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $displayBadge = match($bc->display_type) {
                                        'banner' => ['label' => 'Banner Atas', 'class' => 'bg-amber-100 text-amber-800 border-amber-300', 'icon' => '📢'],
                                        'popup' => ['label' => 'Modal Popup', 'class' => 'bg-rose-100 text-rose-800 border-rose-300', 'icon' => '🪟'],
                                        default => ['label' => 'Notification Bell', 'class' => 'bg-blue-100 text-blue-800 border-blue-300', 'icon' => '🔔'],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $displayBadge['class'] }}">
                                    <span>{{ $displayBadge['icon'] }}</span>
                                    <span>{{ $displayBadge['label'] }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-4 max-w-sm">
                                <p class="font-bold text-slate-800 text-sm mb-1">{{ $bc->title }}</p>
                                <p class="text-slate-600 leading-relaxed text-xs">{{ $bc->message }}</p>
                                <span class="text-[10px] text-slate-400 mt-2 block">
                                    Dibuat: {{ $bc->creator->name ?? 'Super Admin' }} • {{ $bc->created_at->diffForHumans() }}
                                    @if($bc->expires_at)
                                        @if($bc->isExpired())
                                            • <span class="text-rose-600 font-bold">Batas waktu lewat: {{ $bc->expires_at->format('d M Y H:i') }} ({{ $bc->expires_at->diffForHumans() }})</span>
                                        @else
                                            • <span class="text-slate-600 font-medium">Berlaku s/d: {{ $bc->expires_at->format('d M Y H:i') }} ({{ $bc->expires_at->diffForHumans() }})</span>
                                        @endif
                                    @else
                                        • <span class="text-slate-400 italic">Tanpa batas waktu</span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-[11px] inline-flex items-center gap-1">
                                    @if($bc->target_role === 'all')
                                        <span>🌐</span> Seluruh Role & Pengunjung
                                    @elseif($bc->target_role === 'super_admin')
                                        <span>👑</span> Super Admin Saja
                                    @elseif($bc->target_role === 'admin')
                                        <span>🏛️</span> Admin Museum Saja
                                    @elseif($bc->target_role === 'staff')
                                        <span>🛡️</span> Petugas Gate Saja
                                    @else
                                        <span>🎟️</span> Pengunjung (User)
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $bc->reads_count ?? 0 }} pengguna</span>
                                </div>
                                <span class="text-[10px] text-slate-400">telah membuka</span>
                            </td>
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                @if($bc->isExpired())
                                    <div class="inline-flex flex-col items-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200" title="Batas waktu telah habis">
                                            <span>⏱️</span>
                                            <span>Kedaluwarsa</span>
                                        </span>
                                        <span class="text-[9px] text-slate-400 mt-1">Nonaktif Otomatis</span>
                                    </div>
                                @else
                                    <form action="{{ route('admin.broadcasts.toggle', $bc->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $bc->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                            {{ $bc->is_active ? '● Aktif Tayang' : '○ Nonaktif' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.broadcasts.edit', $bc->id) }}"
                                       class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.broadcasts.destroy', $bc->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-bold text-[11px] transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                Belum ada pengumuman / broadcast resmi yang diterbitkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($broadcasts->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $broadcasts->links() }}
            </div>
        @endif
    </div>

    {{-- CREATE MODAL --}}
    <div x-show="openCreateModal"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         x-transition.opacity
         style="display: none;">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border border-slate-100"
             @click.outside="openCreateModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-800">
                    Terbitkan Pengumuman Resmi Super Admin
                </h3>
                <button type="button" @click="openCreateModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('admin.broadcasts.store') }}" method="POST" class="space-y-3.5">
                @csrf

                @if($errors->any())
                    <div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded-xl text-xs font-medium">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Pengumuman</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Cth: Penyesuaian SOP Tiket Libur Lebaran"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Isi Instruksi / Pesan</label>
                    <textarea name="message" rows="3" required placeholder="Tuliskan detail instruksi operasional yang harus dipatuhi..."
                              class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-2 focus:ring-blue-600">{{ old('message') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="category" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                            <option value="umum" {{ old('category') == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="operasional" {{ old('category') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="kebijakan" {{ old('category') == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                            <option value="pemeliharaan" {{ old('category') == 'pemeliharaan' ? 'selected' : '' }}>Pemeliharaan Sistem</option>
                            <option value="tiket" {{ old('category') == 'tiket' ? 'selected' : '' }}>Info Tiket</option>
                            <option value="darurat" {{ old('category') == 'darurat' ? 'selected' : '' }}>Darurat</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Urgensi</label>
                        <select name="urgency" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                            <option value="info" {{ old('urgency') == 'info' ? 'selected' : '' }}>Info Standar (Biru)</option>
                            <option value="warning" {{ old('urgency') == 'warning' ? 'selected' : '' }}>Peringatan Penting (Kuning)</option>
                            <option value="danger" {{ old('urgency') == 'danger' ? 'selected' : '' }}>Kritis / Mendesak (Merah)</option>
                            <option value="success" {{ old('urgency') == 'success' ? 'selected' : '' }}>Kabar Baik / Sukses (Hijau)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Bentuk Tampilan</label>
                        <select name="display_type" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                            <option value="bell" {{ old('display_type') == 'bell' ? 'selected' : '' }}>🔔 Bell Notifikasi Saja</option>
                            <option value="banner" {{ old('display_type') == 'banner' ? 'selected' : '' }}>📢 Banner Atas Halaman</option>
                            <option value="popup" {{ old('display_type') == 'popup' ? 'selected' : '' }}>🪟 Modal Pop-up (Penting)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Penerima</label>
                    <select name="target_role" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                        <option value="all" {{ old('target_role') == 'all' ? 'selected' : '' }}>Semua Pengguna (Admin, Petugas, & Pengunjung)</option>
                        <option value="super_admin" {{ old('target_role') == 'super_admin' ? 'selected' : '' }}>Super Admin Saja</option>
                        <option value="admin" {{ old('target_role') == 'admin' ? 'selected' : '' }}>Admin Museum Saja</option>
                        <option value="staff" {{ old('target_role') == 'staff' ? 'selected' : '' }}>Petugas Gate Saja</option>
                        <option value="visitor" {{ old('target_role') == 'visitor' ? 'selected' : '' }}>Pengunjung (User) Saja</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Masa Berlaku (Opsional)</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-600">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Kosongkan jika berlaku tanpa batas waktu.</span>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                    <button type="button" @click="openCreateModal = false"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                        Terbitkan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
