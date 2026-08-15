@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('users.index') }}" class="text-xs text-purple-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Daftar Akun
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Tambah Akun Pengguna Baru
            </h1>
            <p class="text-xs text-slate-400">
                Daftarkan akun Admin Museum, Petugas Scan, atau akun Pengguna lainnya.
            </p>
        </div>

        <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold border border-purple-100">
            Super Admin
        </span>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

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
                        value="{{ old('name') }}"
                        placeholder="Contoh: Budi Santoso"
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
                        value="{{ old('email') }}"
                        placeholder="Contoh: admin.museum@itihasa.com"
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
                        <option value="">-- Pilih Peran / Role Akun --</option>
                        <option value="admin" {{ old('role', 'admin') == 'admin' ? 'selected' : '' }}>
                            🏛 Admin Museum (Pengelola Operasional Museum)
                        </option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>
                            🛡 Petugas Lapangan (Petugas Validasi / Scan QR Gate)
                        </option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>
                            👑 Super Admin (Pemantauan Global Semua Museum)
                        </option>
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Akun pengunjung mendaftar secara mandiri melalui form registrasi.</p>
                </div>

                {{-- MUSEUM (Wajib untuk Admin & Petugas) --}}
                <div id="museum_field" style="{{ in_array(old('role', 'admin'), ['admin', 'staff']) ? '' : 'display: none;' }}" class="p-4 rounded-xl bg-purple-50/70 border border-purple-200/80 space-y-1.5">
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
                            <option value="{{ $museum->id }}" {{ old('museum_id') == $museum->id ? 'selected' : '' }}>
                                🏛 {{ $museum->name }} ({{ $museum->address ?? 'Indonesia' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-purple-700">Pilih museum yang akan dikelola oleh Admin atau Petugas ini.</p>
                </div>

                {{-- PASSWORD --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-600 mb-1.5">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-600 mb-1.5">
                            Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi kata sandi"
                            required
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
                    Simpan Akun
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
