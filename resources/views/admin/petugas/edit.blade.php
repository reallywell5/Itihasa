@extends('layouts.app')

@section('title', 'Edit Petugas')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Staff Management
            </p>

            <h1 class="text-2xl font-bold text-slate-800">
                Edit Petugas
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Perbarui data akun petugas yang telah terdaftar.
            </p>
        </div>

        <div class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-semibold border border-blue-100">
            Staff ID #{{ $petugas->id }}
        </div>

    </div>

    <form action="{{ route('admin.petugas.update', $petugas->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- LEFT --}}
            <div class="lg:col-span-2 bg-white border border-zinc-200 rounded-3xl shadow-sm p-8 space-y-6">

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- NAME --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Nama Petugas
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $petugas->name) }}"
                           required
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', $petugas->email) }}"
                           required
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Password Baru
                    </label>

                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah"
                               class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">

                        <button type="button"
                                onclick="togglePassword('password')"
                                class="absolute right-4 top-3 text-zinc-400 text-sm">
                            Show
                        </button>
                    </div>
                </div>

                {{-- PASSWORD CONFIRM --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Konfirmasi Password Baru
                    </label>

                    <div class="relative">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Ulangi password baru"
                               class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">

                        <button type="button"
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-4 top-3 text-zinc-400 text-sm">
                            Show
                        </button>
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">

                {{-- PROFILE SUMMARY --}}
                <div class="bg-white border border-zinc-200 rounded-3xl shadow-sm p-6 space-y-4">

                    <h3 class="font-bold text-zinc-900">
                        Informasi Akun
                    </h3>

                    <div class="flex justify-between">
                        <span class="text-sm text-zinc-500">Role</span>
                        <span class="text-sm font-semibold text-zinc-900">
                            Staff
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-zinc-500">Status</span>
                        <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-xs font-semibold">
                            Active
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-zinc-500">Terdaftar</span>
                        <span class="text-sm font-semibold text-zinc-900">
                            {{ $petugas->created_at->format('d M Y') }}
                        </span>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="bg-white border border-zinc-200 rounded-3xl shadow-sm p-6 space-y-3">

                    <button type="submit"
                            class="w-full py-3 rounded-xl bg-zinc-900 text-white font-semibold hover:bg-zinc-800 transition">
                        Update Petugas
                    </button>

                    <a href="{{ route('admin.petugas.index') }}"
                       class="w-full flex justify-center py-3 rounded-xl border border-zinc-200 text-zinc-700 font-semibold hover:bg-zinc-50 transition">
                        Batal
                    </a>

                </div>

            </div>

        </div>
    </form>

</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);

    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>
@endsection
