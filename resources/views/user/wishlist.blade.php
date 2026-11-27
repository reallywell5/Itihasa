@extends('layouts.user')

@section('title', 'Wishlist Saya')

@section('content')

<section class="py-4 sm:py-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-6 mb-8 border-b border-neutral-200">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] mb-2">
                Kurasi Pribadi
            </p>
            <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[#1A1A1A] tracking-tight">
                Wishlist Museum Saya
            </h1>
            <p class="text-xs sm:text-sm text-[#5A5A5A] mt-1.5">
                Daftar museum pilihan yang Anda simpan untuk agenda kunjungan mendatang.
            </p>
        </div>

        <div class="px-4 py-2 rounded-sm bg-[#F8F7F4] border border-neutral-200 text-[#1A1A1A] text-xs font-semibold self-start sm:self-auto">
            {{ $wishlists->count() }} Museum Tersimpan
        </div>
    </div>

    {{-- LIST GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
        @forelse($wishlists as $wishlist)
            <article class="group bg-white rounded-sm overflow-hidden border border-neutral-200 hover:border-[#B8860B] transition duration-300 flex flex-col justify-between">
                {{-- IMAGE --}}
                <div>
                    <div class="relative aspect-[4/3] overflow-hidden bg-neutral-100">
                        <a href="{{ route('museum.detail', $wishlist->museum->id) }}" class="block w-full h-full">
                            <img src="{{ $wishlist->museum->image
                                ? (Str::startsWith($wishlist->museum->image, 'storage/')
                                    ? asset($wishlist->museum->image)
                                    : asset('storage/' . $wishlist->museum->image))
                                : asset('images/institutional/hero-museum.jpg') }}"
                                alt="{{ $wishlist->museum->name }}"
                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                        </a>

                        <span class="absolute top-3.5 left-3.5 px-2.5 py-1 rounded-sm bg-[#121212]/90 text-[#C59B27] text-[10px] font-semibold uppercase tracking-wider">
                            ♥ Tersimpan
                        </span>
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-6">
                        <p class="text-[11px] uppercase tracking-[0.15em] text-[#8C3B2B] font-semibold mb-1.5">
                            {{ ucfirst($wishlist->museum->category ?? 'Museum Sejarah') }}
                        </p>
                        <h3 class="font-serif text-xl font-semibold text-[#1A1A1A] line-clamp-1 group-hover:text-[#8C3B2B] transition-colors duration-300">
                            <a href="{{ route('museum.detail', $wishlist->museum->id) }}">
                                {{ $wishlist->museum->name }}
                            </a>
                        </h3>
                        <p class="text-[#5A5A5A] text-xs sm:text-sm mt-2 line-clamp-2 leading-relaxed">
                            {{ $wishlist->museum->address }}
                        </p>
                    </div>
                </div>

                <div class="px-6 pb-6 pt-4 border-t border-neutral-200 flex items-center gap-2.5">
                    <a href="{{ route('museum.detail', $wishlist->museum->id) }}"
                       class="flex-1 py-2.5 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-center text-xs font-semibold uppercase tracking-wider transition duration-300">
                        Lihat Detail
                    </a>

                    <form action="{{ route('user.wishlist.destroy', $wishlist->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2.5 rounded-sm border border-neutral-300 text-[#8C3B2B] text-xs font-semibold hover:bg-[#F8F7F4] hover:border-[#8C3B2B] transition duration-300">
                            Hapus
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="col-span-full">
                <div class="bg-[#F8F7F4] rounded-sm border border-neutral-200 p-10 sm:p-14 text-center max-w-lg mx-auto">
                    <div class="text-3xl mb-3 text-[#B8860B]">
                        ♡
                    </div>

                    <h2 class="font-serif text-2xl font-semibold text-[#1A1A1A] mb-2">
                        Wishlist Masih Kosong
                    </h2>

                    <p class="text-xs sm:text-sm text-[#5A5A5A] mb-6 leading-relaxed">
                        Mulai jelajahi katalog museum kami dan simpan destinasi favorit Anda untuk rencana kunjungan berikutnya.
                    </p>

                    <a href="{{ route('user.home') }}#pameran"
                       class="inline-flex px-6 py-3 rounded-sm bg-[#1A1A1A] hover:bg-[#B8860B] text-white text-xs font-semibold uppercase tracking-wider transition duration-300">
                        Jelajahi Museum
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</section>

@endsection
