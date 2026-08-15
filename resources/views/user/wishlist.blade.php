@extends('layouts.user')

@section('title', 'Wishlist Saya')

@section('content')

<section class="max-w-7xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#102A43]">
                Wishlist Museum Saya
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Daftar museum favorit yang kamu simpan untuk dikunjungi nanti.
            </p>
        </div>

        <div class="px-3.5 py-1.5 rounded-xl bg-white border border-[#EADBC8] text-[#102A43] text-xs font-bold self-start sm:self-auto shadow-sm">
            {{ $wishlists->count() }} Museum Tersimpan
        </div>
    </div>

    {{-- LIST GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($wishlists as $wishlist)
            <div class="bg-white rounded-2xl overflow-hidden border border-[#EADBC8] shadow-sm hover:shadow-md transition flex flex-col justify-between">
                {{-- IMAGE --}}
                <div class="relative h-44 sm:h-48 overflow-hidden bg-slate-100">
                    <img src="{{ $wishlist->museum->image
                        ? (Str::startsWith($wishlist->museum->image, 'storage/')
                            ? asset($wishlist->museum->image)
                            : asset('storage/' . $wishlist->museum->image))
                        : asset('images/default-museum.jpg') }}"
                        alt="{{ $wishlist->museum->name }}"
                        class="h-full w-full object-cover hover:scale-105 transition duration-300">

                    <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full bg-white/90 backdrop-blur-sm text-[#B88A44] text-[10px] font-bold shadow-sm">
                        ♥ Favorit
                    </span>
                </div>

                {{-- CONTENT --}}
                <div class="p-4 sm:p-5 flex flex-col flex-1 justify-between">
                    <div>
                        <h3 class="text-base font-bold text-[#102A43] line-clamp-1">
                            {{ $wishlist->museum->name }}
                        </h3>

                        <p class="text-slate-500 text-xs mt-1 line-clamp-2">
                            {{ $wishlist->museum->address }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-[#EADBC8]/60">
                        <a href="{{ route('museum.detail', $wishlist->museum->id) }}"
                           class="flex-1 py-2 rounded-xl bg-[#102A43] text-white text-center text-xs font-bold hover:bg-[#0c2238] transition shadow">
                            Lihat Detail
                        </a>

                        <form action="{{ route('user.wishlist.destroy', $wishlist->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-2 rounded-xl bg-red-50 text-red-600 text-xs font-bold hover:bg-red-100 transition border border-red-100">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl border border-[#EADBC8] p-8 sm:p-12 text-center shadow-sm max-w-lg mx-auto">
                    <div class="text-4xl mb-3 text-[#B88A44]">
                        ♡
                    </div>

                    <h2 class="text-lg font-bold text-[#102A43] mb-1">
                        Wishlist Masih Kosong
                    </h2>

                    <p class="text-xs text-slate-500 mb-5">
                        Yuk mulai simpan museum favoritmu untuk rencana kunjungan berikutnya.
                    </p>

                    <a href="{{ route('user.home') }}"
                       class="inline-flex px-5 py-2.5 rounded-xl bg-[#102A43] text-white text-xs font-bold hover:bg-[#0c2238] transition shadow">
                        Jelajahi Museum
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</section>

@endsection
