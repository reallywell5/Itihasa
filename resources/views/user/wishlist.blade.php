@extends('layouts.user')

@section('title', 'Wishlist Saya')

@section('content')

<section class="max-w-7xl mx-auto px-6 lg:px-8 py-14">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10">

        <div>
            <h1 class="text-4xl font-bold text-[#102A43]">
                Wishlist Saya
            </h1>

            <p class="text-slate-500 mt-2">
                Daftar museum yang kamu simpan untuk dikunjungi nanti.
            </p>
        </div>

        <div class="mt-4 md:mt-0 px-4 py-2 rounded-xl bg-[#F9F7F2] border border-[#EADBC8] text-[#102A43] font-semibold">
            {{ $wishlists->count() }} Museum Tersimpan
        </div>

    </div>

    {{-- LIST --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($wishlists as $wishlist)

        <div class="bg-white rounded-[28px] overflow-hidden border border-[#EADBC8] shadow-sm hover:shadow-xl transition duration-300">

            {{-- IMAGE --}}
            <div class="relative">

                <img src="{{ $wishlist->museum->image
                    ? asset('storage/' . $wishlist->museum->image)
                    : asset('images/default-museum.jpg') }}"
                     alt="{{ $wishlist->museum->name }}"
                     class="h-56 w-full object-cover">

                <div class="absolute top-4 left-4 px-3 py-1 rounded-full bg-white/90 text-[#B88A44] text-xs font-bold">
                    Favorit
                </div>

            </div>

            {{-- CONTENT --}}
            <div class="p-6">

                <h3 class="text-xl font-bold text-[#102A43] mb-3">
                    {{ $wishlist->museum->name }}
                </h3>

                <p class="text-slate-500 text-sm mb-6 line-clamp-2">
                    {{ $wishlist->museum->address }}
                </p>

                <div class="flex gap-3">

                    {{-- DETAIL --}}
                    <a href="{{ route('museum.detail', $wishlist->museum->id) }}"
                       class="flex-1 py-3 rounded-xl bg-[#102A43] text-white text-center font-semibold hover:bg-[#0c2238] transition">
                        Lihat Detail
                    </a>

                    {{-- REMOVE --}}
                    <form action="{{ route('user.wishlist.destroy', $wishlist->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button
                            class="px-4 py-3 rounded-xl bg-red-50 text-red-500 font-semibold hover:bg-red-100 transition">
                            Hapus
                        </button>
                    </form>

                </div>

            </div>

        </div>

        @empty

        <div class="col-span-full">

            <div class="bg-white rounded-[28px] border border-[#EADBC8] p-12 text-center shadow-sm">

                <div class="text-5xl mb-4">
                    ♡
                </div>

                <h2 class="text-2xl font-bold text-[#102A43] mb-3">
                    Wishlist Masih Kosong
                </h2>

                <p class="text-slate-500 mb-6">
                    Yuk mulai simpan museum favoritmu untuk dikunjungi nanti.
                </p>

                <a href="{{ route('user.home') }}"
                   class="inline-flex px-6 py-3 rounded-xl bg-[#102A43] text-white font-semibold hover:bg-[#0c2238] transition">
                    Jelajahi Museum
                </a>

            </div>

        </div>

        @endforelse

    </div>

</section>

@endsection
