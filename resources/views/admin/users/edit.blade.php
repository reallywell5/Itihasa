@extends('layouts.app')

@section('title', 'Edit Akun Pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('users.index') }}" class="text-xs text-purple-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Daftar Akun
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Edit Akun: {{ $user->name }}
            </h1>
            <p class="text-xs text-slate-400">
                Perbarui data akun pengguna, peran, penugasan museum, atau reset kata sandi.
            </p>
        </div>

        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-100">
            Edit Mode
        </span>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-4">

                {{-- ERROR GLOBAL --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- NAME --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"
                    >
                </div>

                {{-- EMAIL --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"
                    >
                </div>

                {{-- ROLE --}}
                <div>
                    <label for="role" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Peran Akun (Role) <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        onchange="toggleMuseumSelect(this.value)"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 font-medium"
                    >
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                            🏛 Admin Museum (Pengelola Operasional Museum)
                        </option>
                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
                            🛡 Petugas Lapangan (Scan Tiket QR Gate)
                        </option>
                        <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>
                            👑 Super Admin (Pemantauan Global Seluruh Museum)
                        </option>
                        @if($user->role === 'visitor')
                            <option value="visitor" {{ old('role', $user->role) == 'visitor' ? 'selected' : '' }}>
                                👤 Pengunjung (Visitor)
                            </option>
                        @endif
                    </select>
                </div>

                {{-- MUSEUM (Wajib untuk Admin & Petugas) --}}
                <div id="museum_field" style="{{ in_array(old('role', $user->role), ['admin', 'staff']) ? '' : 'display: none;' }}" class="p-4 rounded-xl bg-purple-50/70 border border-purple-200/80 space-y-1.5">
                    <label for="museum_id" class="block text-xs font-bold text-purple-900">
                        Penugasan Museum <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="museum_id"
                        name="museum_id"
                        class="w-full px-3.5 py-2.5 border border-purple-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white font-medium text-slate-800"
                    >
                        <option value="">-- Pilih Salah Satu Museum --</option>
                        @foreach ($museums as $museum)
                            <option value="{{ $museum->id }}" {{ old('museum_id', $user->museum_id) == $museum->id ? 'selected' : '' }}>
                                🏛 {{ $museum->name }} ({{ $museum->address ?? 'Indonesia' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-purple-700">Akun Admin dan Petugas wajib terikat ke museum tertentu.</p>
                </div>

                {{-- PASSWORD INFO --}}
                <div class="p-3 bg-amber-50/50 border border-amber-100 rounded-xl">
                    <p class="text-[11px] text-amber-800">
                        💡 <em>Kosongkan kolom kata sandi di bawah jika Anda tidak ingin mengubah kata sandi lama akun ini.</em>
                    </p>
                </div>

                {{-- PASSWORD --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-600 mb-1.5">
                            Kata Sandi Baru (opsional)
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Kosongkan jika tidak diganti"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-600 mb-1.5">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi kata sandi baru"
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"
                        >
                    </div>
                </div>

            </div>

            {{-- ACTION --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <a href="{{ route('users.index') }}"
                   class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold shadow hover:bg-purple-700 transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    function toggleMuseumSelect(role) {
        const museumField = document.getElementById('museum_field');
        const museumSelect = document.getElementById('museum_id');
        if (role === 'admin' || role === 'staff') {
            museumField.style.display = 'block';
            museumSelect.setAttribute('required', 'required');
        } else {
            museumField.style.display = 'none';
            museumSelect.removeAttribute('required');
            museumSelect.value = '';
        }
    }
</script>
@endsection
