@extends('layouts.app')

@section('title', 'Broadcast & Pengumuman Resmi')

@section('content')
<div class="space-y-6" x-data="{ openCreateModal: false }">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-2.5">
                <span>📢</span>
                <span>Broadcast & Pengumuman Global</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Kirimkan instruksi, SOP operasional, dan pengumuman resmi Super Admin ke seluruh dashboard Admin Museum dan Petugas Gate.
            </p>
        </div>

        <button type="button" @click="openCreateModal = true"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition shrink-0">
            <span>+ Buat Pengumuman Baru</span>
        </button>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
            ✓ {{ session('success') }}
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
                        <th class="px-4 py-3.5">Urgensi</th>
                        <th class="px-4 py-3.5">Judul & Pesan</th>
                        <th class="px-4 py-3.5">Target Peran</th>
                        <th class="px-4 py-3.5">Masa Berlaku</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($broadcasts as $bc)
                        <tr class="hover:bg-slate-50/70 transition align-top">
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $urgencyBadge = match($bc->urgency) {
                                        'danger' => 'bg-red-50 text-red-700 border-red-200',
                                        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        default => 'bg-blue-50 text-blue-800 border-blue-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $urgencyBadge }}">
                                    {{ $bc->urgency }}
                                </span>
                            </td>
                            <td class="px-4 py-4 max-w-md">
                                <p class="font-bold text-slate-800 text-sm mb-1">{{ $bc->title }}</p>
                                <p class="text-slate-600 leading-relaxed text-xs">{{ $bc->message }}</p>
                                <span class="text-[10px] text-slate-400 mt-2 block">
                                    Dibuat oleh: {{ $bc->creator->name ?? 'Super Admin' }} • {{ $bc->created_at->diffForHumans() }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-[11px]">
                                    @if($bc->target_role === 'all')
                                        👥 Seluruh Ekosistem
                                    @elseif($bc->target_role === 'admin')
                                        🏛 Admin Museum Saja
                                    @else
                                        📷 Petugas Gate Saja
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-slate-500">
                                @if($bc->expires_at)
                                    <span class="text-[11px]">s/d {{ $bc->expires_at->format('d M Y, H:i') }}</span>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Tanpa Batas Waktu</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <form action="{{ route('admin.broadcasts.toggle', $bc->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $bc->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $bc->is_active ? '● Aktif Tayang' : '○ Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <form action="{{ route('admin.broadcasts.destroy', $bc->id) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-bold text-[11px] transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400">
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
                    📢 Terbitkan Pengumuman Resmi Super Admin
                </h3>
                <button type="button" @click="openCreateModal = false" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
            </div>

            <form action="{{ route('admin.broadcasts.store') }}" method="POST" class="space-y-3.5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Pengumuman</label>
                    <input type="text" name="title" required placeholder="Cth: Penyesuaian SOP Tiket Libur Lebaran"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Isi Instruksi / Pesan</label>
                    <textarea name="message" rows="3" required placeholder="Tuliskan detail instruksi operasional yang harus dipatuhi..."
                              class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Urgensi</label>
                        <select name="urgency" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                            <option value="info">ℹ️ Info Standar (Biru)</option>
                            <option value="warning">⚠️ Peringatan Penting (Kuning)</option>
                            <option value="danger">🚨 Kritis / Mendesak (Merah)</option>
                            <option value="success">🎉 Kabar Baik / Sukses (Hijau)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Penerima</label>
                        <select name="target_role" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs">
                            <option value="all">👥 Seluruh Tim (Admin + Petugas)</option>
                            <option value="admin">🏛 Khusus Admin Museum</option>
                            <option value="staff">📷 Khusus Petugas Gate</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Masa Berlaku (Opsional)</label>
                    <input type="datetime-local" name="expires_at"
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
