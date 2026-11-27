@extends('layouts.app')

@section('title', 'Edit Pengumuman / Broadcast')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.broadcasts.index') }}" class="text-xs text-purple-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Daftar Broadcast
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Edit Pengumuman Resmi
            </h1>
            <p class="text-xs text-slate-400">
                Perbarui judul, instruksi pesan, tingkat urgensi, atau target penerima pengumuman ini.
            </p>
        </div>
    </div>

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden p-6">
        <form action="{{ route('admin.broadcasts.update', $broadcast->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Judul Pengumuman <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $broadcast->title) }}" required
                       placeholder="Cth: Penyesuaian SOP Tiket dan Kuota Libur Nasional"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Isi Instruksi / Pesan <span class="text-red-500">*</span>
                </label>
                <textarea name="message" rows="5" required
                          placeholder="Tuliskan instruksi atau pengumuman secara rinci..."
                          class="w-full rounded-xl border border-slate-200 p-3 text-xs leading-relaxed focus:ring-2 focus:ring-purple-600 focus:outline-none">{{ old('message', $broadcast->message) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
                        <option value="umum" {{ old('category', $broadcast->category) == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="operasional" {{ old('category', $broadcast->category) == 'operasional' ? 'selected' : '' }}>Operasional</option>
                        <option value="kebijakan" {{ old('category', $broadcast->category) == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                        <option value="pemeliharaan" {{ old('category', $broadcast->category) == 'pemeliharaan' ? 'selected' : '' }}>Pemeliharaan Sistem</option>
                        <option value="tiket" {{ old('category', $broadcast->category) == 'tiket' ? 'selected' : '' }}>Info Tiket</option>
                        <option value="darurat" {{ old('category', $broadcast->category) == 'darurat' ? 'selected' : '' }}>Darurat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tingkat Urgensi <span class="text-red-500">*</span>
                    </label>
                    <select name="urgency" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
                        <option value="info" {{ old('urgency', $broadcast->urgency) == 'info' ? 'selected' : '' }}>Info Standar (Biru)</option>
                        <option value="warning" {{ old('urgency', $broadcast->urgency) == 'warning' ? 'selected' : '' }}>Peringatan Penting (Kuning)</option>
                        <option value="danger" {{ old('urgency', $broadcast->urgency) == 'danger' ? 'selected' : '' }}>Kritis / Mendesak (Merah)</option>
                        <option value="success" {{ old('urgency', $broadcast->urgency) == 'success' ? 'selected' : '' }}>Kabar Baik / Sukses (Hijau)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Bentuk Tampilan <span class="text-red-500">*</span>
                    </label>
                    <select name="display_type" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
                        <option value="bell" {{ old('display_type', $broadcast->display_type) == 'bell' ? 'selected' : '' }}>🔔 Notification Bell Saja</option>
                        <option value="banner" {{ old('display_type', $broadcast->display_type) == 'banner' ? 'selected' : '' }}>📢 Banner Atas Halaman</option>
                        <option value="popup" {{ old('display_type', $broadcast->display_type) == 'popup' ? 'selected' : '' }}>🪟 Modal Pop-up (Penting)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Target Penerima <span class="text-red-500">*</span>
                </label>
                <select name="target_role" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
                    <option value="all" {{ old('target_role', $broadcast->target_role) == 'all' ? 'selected' : '' }}>Semua Pengguna (Admin, Petugas, & Pengunjung)</option>
                    <option value="super_admin" {{ old('target_role', $broadcast->target_role) == 'super_admin' ? 'selected' : '' }}>Super Admin Saja</option>
                    <option value="admin" {{ old('target_role', $broadcast->target_role) == 'admin' ? 'selected' : '' }}>Admin Museum Saja</option>
                    <option value="staff" {{ old('target_role', $broadcast->target_role) == 'staff' ? 'selected' : '' }}>Petugas Gate Saja</option>
                    <option value="visitor" {{ in_array(old('target_role', $broadcast->target_role), ['visitor', 'user']) ? 'selected' : '' }}>Pengunjung (User) Saja</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Masa Berlaku Tayang (Opsional)
                </label>
                <input type="datetime-local" name="expires_at"
                       value="{{ old('expires_at', $broadcast->expires_at ? $broadcast->expires_at->format('Y-m-d\TH:i') : '') }}"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-none">
                <span class="text-[10px] text-slate-400 mt-1 block">Kosongkan jika ingin pengumuman tayang seterusnya tanpa batas waktu.</span>
            </div>

            <div class="pt-2 flex items-center gap-2">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       {{ old('is_active', $broadcast->is_active) ? 'checked' : '' }}
                       class="rounded text-purple-600 focus:ring-purple-500">
                <label for="is_active" class="text-xs font-semibold text-slate-700">
                    Status aktif (tayangkan di dashboard pengguna terkait)
                </label>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.broadcasts.index') }}"
                   class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

