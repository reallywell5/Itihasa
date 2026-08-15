@extends('layouts.app')

@section('title', 'Edit Petugas Loket')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.petugas.index') }}" class="text-xs text-blue-600 hover:underline font-semibold flex items-center gap-1 mb-1">
                ← Kembali ke Daftar Petugas
            </a>
            <h1 class="text-xl font-bold text-slate-800">
                Edit Akun Petugas
            </h1>
            <p class="text-xs text-slate-400">
                Perbarui nama, email, atau atur ulang kata sandi petugas gate.
            </p>
        </div>

        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
            Admin • {{ Auth::user()?->museum?->name ?? 'Museum' }}
        </span>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <form action="{{ route('admin.petugas.update', $petugas->id) }}" method="POST">
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

                {{-- INFORMASI PENUGASAN --}}
                <div class="p-3.5 rounded-xl bg-blue-50/60 border border-blue-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-base">🏛</span>
                        <span class="text-xs font-bold text-blue-900">
                            Penugasan: {{ $petugas->museum?->name ?? (Auth::user()?->museum?->name ?? 'Museum Anda') }}
                        </span>
                    </div>
                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                        Staff ID: STF-{{ str_pad($petugas->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                {{-- NAME --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Nama Lengkap Petugas <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $petugas->name) }}"
                        required
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 font-medium"
                    >
                </div>

                {{-- EMAIL --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-600 mb-1.5">
                        Alamat Email Login <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $petugas->email) }}"
                        required
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 font-mono"
                    >
                </div>

                {{-- PASSWORD INFO --}}
                <div class="p-3 bg-amber-50/60 border border-amber-200 rounded-xl">
                    <p class="text-[11px] text-amber-900">
                        💡 <em>Kosongkan kolom kata sandi di bawah jika Anda tidak ingin mengubah kata sandi lama akun petugas ini.</em>
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
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600"
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
                            class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                    </div>
                </div>

            </div>

            {{-- ACTION --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <a href="{{ route('admin.petugas.index') }}"
                   class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-100 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold shadow hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
