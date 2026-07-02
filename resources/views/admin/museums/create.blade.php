@extends('layouts.app')

@section('title', 'Tambah Museum')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-indigo-600">
                <a href="{{ route('museums.index') }}" class="hover:text-indigo-700">
                    Museum
                </a>
                <span>/</span>
                <span class="text-zinc-500">Tambah Data</span>
            </div>

            <h1 class="text-3xl font-bold text-zinc-900 mt-2">
                Tambah Museum Baru
            </h1>

            <p class="text-sm text-zinc-500 mt-1">
                Lengkapi informasi museum untuk ditampilkan kepada pengunjung.
            </p>
        </div>

        <div class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-semibold border border-emerald-200">
            Draft Baru
        </div>
    </div>

    <form action="{{ route('museums.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- LEFT --}}
            <div class="lg:col-span-2 bg-white border border-zinc-200 rounded-2xl shadow-sm p-8 space-y-6">

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

                {{-- NAMA --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Nama Museum
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Contoh: Museum Nasional Indonesia"
                           class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                </div>

                {{-- ALAMAT --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Alamat Museum
                    </label>

                    <textarea name="address"
                              rows="4"
                              placeholder="Masukkan alamat lengkap..."
                              class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">{{ old('address') }}</textarea>
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-2">
                        Deskripsi Museum
                    </label>

                    <textarea name="description"
                              rows="6"
                              placeholder="Ceritakan sejarah, koleksi, dan keunikan museum..."
                              class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">{{ old('description') }}</textarea>
                </div>

                {{-- JAM --}}
                <div class="grid md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">
                            Jam Buka
                        </label>

                        <input type="time"
                               id="opening_time"
                               name="opening_time"
                               value="{{ old('opening_time') }}"
                               class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-2">
                            Jam Tutup
                        </label>

                        <input type="time"
                               id="closing_time"
                               name="closing_time"
                               value="{{ old('closing_time') }}"
                               class="w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm">
                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">

                {{-- IMAGE --}}
                <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-6">

                    <label class="block text-sm font-semibold text-zinc-700 mb-4">
                        Foto Museum
                    </label>

                    <div class="w-full h-56 rounded-2xl border border-dashed border-zinc-300 overflow-hidden bg-zinc-50 flex items-center justify-center">
                        <img id="preview-image"
                             src="{{ asset('images/default-museum.jpg') }}"
                             alt="Preview"
                             class="w-full h-full object-cover">
                    </div>

                    <input type="file"
                           id="image"
                           name="image"
                           accept="image/*"
                           class="mt-4 w-full text-sm">

                    <p class="text-xs text-zinc-400 mt-2">
                        Upload gambar museum.
                    </p>

                </div>

                {{-- STATUS --}}
                <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-6 space-y-4">

                    <h3 class="font-bold text-zinc-900">
                        Preview Operasional
                    </h3>

                    <div class="flex justify-between">
                        <span class="text-sm text-zinc-500">Jam Operasional</span>

                        <span id="operational-preview"
                              class="text-sm font-semibold text-zinc-900">
                            -
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-zinc-500">Status</span>

                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold">
                            Menunggu Publish
                        </span>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-6 space-y-3">

                    <button type="submit"
                            class="w-full py-3 rounded-xl bg-zinc-900 text-white font-semibold hover:bg-zinc-800 transition">
                        Simpan Museum
                    </button>

                    <a href="{{ route('museums.index') }}"
                       class="w-full flex justify-center py-3 rounded-xl border border-zinc-200 text-zinc-700 font-semibold hover:bg-zinc-50 transition">
                        Batal
                    </a>

                </div>

            </div>

        </div>
    </form>
</div>

<script>
const imageInput = document.getElementById('image');

imageInput.addEventListener('change', function(e) {
    if (!e.target.files.length) return;

    const reader = new FileReader();

    reader.onload = function(event) {
        document.getElementById('preview-image').src = event.target.result;
    };

    reader.readAsDataURL(e.target.files[0]);
});

function updateOperationalPreview() {
    const open = document.getElementById('opening_time').value;
    const close = document.getElementById('closing_time').value;

    if (open && close) {
        document.getElementById('operational-preview').innerText =
            `${open} - ${close}`;
    }
}

document.getElementById('opening_time').addEventListener('change', updateOperationalPreview);
document.getElementById('closing_time').addEventListener('change', updateOperationalPreview);
</script>
@endsection
