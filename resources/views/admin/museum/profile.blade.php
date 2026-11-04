@extends('layouts.app')

@section('title', 'Pengaturan Profil & Fasilitas Museum')

@section('content')
<div class="max-w-5xl mx-auto space-y-5" x-data="{
    activeTab: 'info',
    facilities: {{ json_encode($museum->facilities ?? ['Mushola', 'Area Parkir', 'Toilet Bersih', 'Ramah Kursi Roda']) }},
    newFacility: '',
    addFacility() {
        if (this.newFacility.trim() && !this.facilities.includes(this.newFacility.trim())) {
            this.facilities.push(this.newFacility.trim());
            this.newFacility = '';
        }
    },
    removeFacility(index) {
        this.facilities.splice(index, 1);
    }
}">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                Profil, Fasilitas & Jam Operasional
            </h1>
            <p class="text-xs text-slate-400 mt-0.5 max-w-xl">
                Kelola informasi publik museum, foto profil/banner, daftar fasilitas penunjang wisata, dan jam buka-tutup mingguan secara mandiri.
            </p>
        </div>

        @if($isSuperAdmin && $allMuseums->isNotEmpty())
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Pilih Museum</label>
                <form method="GET" action="{{ route('admin.museum.profile.edit') }}">
                    <select name="museum_id" onchange="this.form.submit()" class="rounded-xl border border-slate-200 p-2 text-xs font-semibold text-slate-800">
                        @foreach($allMuseums as $m)
                            <option value="{{ $m->id }}" {{ $m->id === $museum->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-xl px-4 py-3">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TAB SWITCHER --}}
    <div class="flex border-b border-slate-200 gap-6">
        <button type="button" @click="activeTab = 'info'"
                :class="activeTab === 'info' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="py-3 border-b-2 text-xs font-semibold transition flex items-center gap-2">
            <span>Informasi & Deskripsi</span>
        </button>
        <button type="button" @click="activeTab = 'facilities'"
                :class="activeTab === 'facilities' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="py-3 border-b-2 text-xs font-semibold transition flex items-center gap-2">
            <span>Fasilitas Penunjang</span>
        </button>
        <button type="button" @click="activeTab = 'schedule'"
                :class="activeTab === 'schedule' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="py-3 border-b-2 text-xs font-semibold transition flex items-center gap-2">
            <span>Jadwal Operasional Harian</span>
        </button>
    </div>

    {{-- FORM UTAMA --}}
    <form action="{{ route('admin.museum.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @if($isSuperAdmin)
            <input type="hidden" name="museum_id" value="{{ $museum->id }}">
        @endif

        {{-- TAB 1: INFORMASI & DESKRIPSI --}}
        <div x-show="activeTab === 'info'" class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Nama Museum <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required value="{{ old('name', $museum->name) }}"
                           class="w-full rounded-xl border border-slate-200 p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Kategori Museum <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required class="w-full rounded-xl border border-slate-200 p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                        <option value="museum" {{ old('category', $museum->category) === 'museum' ? 'selected' : '' }}>Museum Sejarah / Umum</option>
                        <option value="seni" {{ old('category', $museum->category) === 'seni' ? 'selected' : '' }}>Galeri Seni (Art Gallery)</option>
                        <option value="budaya" {{ old('category', $museum->category) === 'budaya' ? 'selected' : '' }}>Pusat Kebudayaan & Adat</option>
                        <option value="alam" {{ old('category', $museum->category) === 'alam' ? 'selected' : '' }}>Sains, Geologi & Alam</option>
                        <option value="religius" {{ old('category', $museum->category) === 'religius' ? 'selected' : '' }}>Situs Religi & Candi</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Alamat Lengkap Museum <span class="text-red-500">*</span>
                </label>
                <textarea name="address" rows="2" required
                          class="w-full rounded-xl border border-slate-200 p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">{{ old('address', $museum->address) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Deskripsi Lengkap Museum <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="5" required
                          class="w-full rounded-xl border border-slate-200 p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">{{ old('description', $museum->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Foto Banner Utama Museum
                </label>
                @if($museum->image)
                    <div class="mb-3 w-48 h-28 rounded-xl overflow-hidden border border-slate-200">
                        <img src="{{ asset('storage/'.$museum->image) }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200">
                <span class="text-[10px] text-slate-400 block mt-1">Format: JPG, PNG, WEBP. Maksimal 3MB.</span>
            </div>
        </div>

        {{-- TAB 2: FASILITAS PENUNJANG --}}
        <div x-show="activeTab === 'facilities'" x-cloak class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-5">
            <div>
                <h3 class="text-sm font-bold text-slate-800">
                    Daftar Fasilitas Penunjang Wisata
                </h3>
                <p class="text-xs text-slate-400">
                    Fasilitas ini akan ditampilkan dalam bentuk chip ikonik di halaman detail museum untuk memudahkan pengunjung.
                </p>
            </div>

            {{-- INPUT ADD TAG --}}
            <div class="flex gap-2 max-w-md">
                <input type="text" x-model="newFacility" @keydown.enter.prevent="addFacility()"
                       placeholder="Ketik fasilitas (cth: Audio Guide) lalu tekan Tambah"
                       class="flex-1 rounded-xl border border-slate-200 p-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-blue-500">
                <button type="button" @click="addFacility()"
                        class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                    + Tambah
                </button>
            </div>

            {{-- LIST TAGS --}}
            <div class="flex flex-wrap gap-2 pt-2">
                <template x-for="(fac, index) in facilities" :key="index">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-semibold">
                        <span x-text="'✨ ' + fac"></span>
                        <input type="hidden" name="facilities[]" :value="fac">
                        <button type="button" @click="removeFacility(index)" class="text-blue-400 hover:text-red-500 font-bold ml-1">✕</button>
                    </div>
                </template>
            </div>

            {{-- PRESET SUGGESTIONS --}}
            <div class="pt-4 border-t border-slate-100">
                <span class="text-[11px] font-bold text-slate-500 block mb-2">Rekomendasi Fasilitas Cepat (Klik untuk menambah):</span>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['Mushola Nyaman', 'Area Parkir Luas', 'Toilet Bersih & Ramah Anak', 'Ramah Kursi Roda & Difabel', 'Audio Guide Digital', 'Wi-Fi Gratis', 'Pemandu Wisata Resmi', 'Toko Cenderamata', 'Kantin & Kafe', 'Loker Penitipan Barang', 'Ruang Menyusui / Laktasi', 'Spot Foto Instagramable'] as $preset)
                        <button type="button"
                                @click="if(!facilities.includes('{{ $preset }}')) facilities.push('{{ $preset }}')"
                                class="px-2.5 py-1 rounded-lg border border-slate-200 text-[11px] text-slate-600 hover:bg-slate-50 transition">
                            + {{ $preset }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TAB 3: JADWAL OPERASIONAL HARIAN --}}
        <div x-show="activeTab === 'schedule'" x-cloak class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800">
                    Jadwal Operasional Mingguan
                </h3>
                <p class="text-xs text-slate-400">
                    Atur jam buka dan tutup untuk setiap hari dalam seminggu.
                </p>
            </div>

            <div class="divide-y divide-slate-100">
                @php
                    $dayLabels = \App\Models\Museum::dayLabels();
                    $operationalHours = $museum->operational_hours ?? [];
                @endphp

                @foreach($dayLabels as $key => $label)
                    @php
                        $dayData = $operationalHours[$key] ?? ['closed' => false, 'sessions' => [['open' => '08:00', 'close' => '16:00']]];
                        $isDayClosed = !empty($dayData['closed']);
                        $firstSession = $dayData['sessions'][0] ?? ['open' => '08:00', 'close' => '16:00'];
                    @endphp
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                         x-data="{ dayClosed: {{ $isDayClosed ? 'true' : 'false' }} }">
                        <div class="w-32 font-bold text-xs text-slate-800 flex items-center gap-2">
                            <span>{{ $label }}</span>
                        </div>

                        <div class="flex items-center gap-4 flex-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="operational_hours[{{ $key }}][closed]" value="1"
                                       x-model="dayClosed"
                                       class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                <span class="text-xs font-semibold" :class="dayClosed ? 'text-red-600 font-bold' : 'text-slate-500'">
                                    Tutup / Libur
                                </span>
                            </label>

                            <div x-show="!dayClosed" class="flex items-center gap-2">
                                <input type="time" name="operational_hours[{{ $key }}][sessions][0][open]"
                                       value="{{ $firstSession['open'] ?? '08:00' }}"
                                       class="rounded-xl border border-slate-200 px-2.5 py-1.5 text-xs text-slate-800">
                                <span class="text-xs text-slate-400">s/d</span>
                                <input type="time" name="operational_hours[{{ $key }}][sessions][0][close]"
                                       value="{{ $firstSession['close'] ?? '16:00' }}"
                                       class="rounded-xl border border-slate-200 px-2.5 py-1.5 text-xs text-slate-800">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div class="pt-4 flex justify-end">
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-sm flex items-center gap-2">
                <span>💾</span>
                <span>Simpan Perubahan Museum</span>
            </button>
        </div>
    </form>

</div>
@endsection
