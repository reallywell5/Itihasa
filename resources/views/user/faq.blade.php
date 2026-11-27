@extends('layouts.user')

@section('title', 'FAQ & Bantuan')

@section('content')

<section class="max-w-3xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="text-center mb-10 pb-6 border-b border-neutral-200">
        <span class="inline-block text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] mb-2">
            Pusat Bantuan Pengunjung
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[#1A1A1A] tracking-tight">
            Pertanyaan yang Sering Diajukan
        </h1>
        <p class="text-xs sm:text-sm text-[#5A5A5A] mt-2 max-w-lg mx-auto leading-relaxed">
            Panduan lengkap seputar reservasi tiket, pembayaran terintegrasi, ketentuan pembatalan, dan tata cara kunjungan museum.
        </p>
    </div>

    @php
        $faqGroups = [
            'Booking & Pembayaran' => [
                [
                    'q' => 'Bagaimana cara memesan tiket museum?',
                    'a' => 'Pilih museum yang ingin dikunjungi, klik "Pesan Tiket", tentukan tanggal kunjungan dan jumlah tiket sesuai kategori (dewasa/pelajar/anak), lalu lanjutkan ke pembayaran. Tiket digital akan tersedia setelah pembayaran berhasil.',
                ],
                [
                    'q' => 'Apa itu booking rombongan dan bagaimana cara mengisinya?',
                    'a' => 'Booking untuk 6 orang atau lebih otomatis dianggap sebagai rombongan. Kamu akan diminta mengisi manifes berupa nama seluruh anggota, yang nantinya digunakan petugas untuk verifikasi saat kunjungan.',
                ],
                [
                    'q' => 'Berapa lama batas waktu pembayaran?',
                    'a' => 'Booking yang belum dibayar memiliki batas waktu tertentu sebelum otomatis kedaluwarsa. Kamu bisa memantau sisa waktu pembayaran di halaman Profil, bagian "Menunggu Pembayaran".',
                ],
            ],
            'Pembatalan & Perubahan' => [
                [
                    'q' => 'Bisakah saya membatalkan booking yang belum dibayar?',
                    'a' => 'Bisa, kapan saja sebelum batas waktu pembayaran habis. Buka halaman Profil, temukan booking terkait di bagian "Menunggu Pembayaran", lalu klik "Batal" dan isi alasan pembatalan.',
                ],
                [
                    'q' => 'Bisakah saya membatalkan booking yang sudah dibayar?',
                    'a' => 'Bisa, dengan syarat pembatalan dilakukan maksimal 24 jam sebelum jadwal kunjungan. Setelah masuk masa 24 jam menjelang kunjungan, booking tidak bisa dibatalkan lagi melalui sistem.',
                ],
                [
                    'q' => 'Apakah ada pengembalian dana (refund) setelah pembatalan?',
                    'a' => 'Proses pengembalian dana untuk booking yang sudah dibayar akan ditindaklanjuti secara manual oleh tim kami. Silakan hubungi kontak yang tersedia di halaman ini setelah pembatalan berhasil.',
                ],
            ],
            'Tiket & Kunjungan' => [
                [
                    'q' => 'Bagaimana cara menggunakan tiket saat berkunjung?',
                    'a' => 'Buka halaman "Buka E-Tiket QR" pada booking kamu, lalu tunjukkan QR Code tersebut kepada petugas di lokasi museum untuk divalidasi. Kamu juga bisa mengunduh tiket dalam bentuk gambar jika ingin menyimpannya offline.',
                ],
                [
                    'q' => 'Tiket saya kedaluwarsa, apa yang terjadi?',
                    'a' => 'Tiket akan berstatus kedaluwarsa jika kunjungan tidak dilakukan hingga melewati jam operasional museum pada tanggal yang dipilih. Tiket yang sudah kedaluwarsa tidak dapat digunakan atau dibatalkan.',
                ],
                [
                    'q' => 'Apakah saya bisa mengubah tanggal kunjungan setelah booking?',
                    'a' => 'Saat ini perubahan tanggal belum bisa dilakukan langsung. Kamu bisa membatalkan booking (sesuai ketentuan pembatalan) dan membuat booking baru dengan tanggal yang diinginkan.',
                ],
            ],
            'Ulasan & Akun' => [
                [
                    'q' => 'Kapan saya bisa memberi ulasan untuk museum?',
                    'a' => 'Ulasan hanya bisa diberikan setelah tiket kamu digunakan (divalidasi oleh petugas di lokasi). Kamu bisa memberi ulasan melalui halaman detail museum atau tombol "Beri Ulasan" di halaman Profil.',
                ],
                [
                    'q' => 'Bisakah saya mengedit ulasan yang sudah dikirim?',
                    'a' => 'Bisa. Buka halaman detail museum tempat kamu memberi ulasan, cari ulasanmu, lalu klik "Edit Ulasan" untuk mengubah rating maupun komentar.',
                ],
                [
                    'q' => 'Bagaimana cara mengubah data akun saya?',
                    'a' => 'Buka halaman Profil, klik "Edit Profil" untuk memperbarui nama, email, atau kata sandi.',
                ],
            ],
        ];
    @endphp

    {{-- FAQ GROUPS --}}
    <div class="space-y-8" x-data="{ openId: null }">
        @foreach ($faqGroups as $groupName => $items)
            <div>
                <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-3">
                    {{ $groupName }}
                </h2>

                <div class="bg-white rounded-sm border border-neutral-200 divide-y divide-neutral-200 overflow-hidden">
                    @foreach ($items as $item)
                        @php $itemId = Str::slug($groupName . '-' . $item['q']); @endphp

                        <div>
                            <button type="button"
                                    @click="openId = openId === '{{ $itemId }}' ? null : '{{ $itemId }}'"
                                    class="w-full flex items-center justify-between gap-4 text-left px-5 py-4 hover:bg-[#F8F7F4] transition duration-200">
                                <span class="font-medium text-sm text-[#1A1A1A]">{{ $item['q'] }}</span>
                                <span class="text-xs font-semibold text-[#B8860B] shrink-0"
                                      x-text="openId === '{{ $itemId }}' ? '−' : '+'"></span>
                            </button>

                            <div x-show="openId === '{{ $itemId }}'" x-cloak x-collapse>
                                <p class="px-5 pb-4 text-xs sm:text-sm text-[#5A5A5A] leading-relaxed border-t border-neutral-100 pt-3 bg-[#F8F7F4]/60">
                                    {{ $item['a'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- CONTACT CTA --}}
    <div class="mt-12 bg-[#121212] rounded-sm p-8 text-center text-white border border-neutral-800">
        <p class="text-[10px] uppercase tracking-[0.22em] text-[#C59B27] font-semibold mb-1.5">
            Layanan Pengunjung
        </p>
        <h3 class="font-serif text-xl sm:text-2xl font-medium mb-2">
            Masih Ada Pertanyaan Lain?
        </h3>
        <p class="text-neutral-400 text-xs sm:text-sm mb-5 max-w-md mx-auto">
            Tim layanan dukungan Itihasa siap membantu kendala reservasi dan kunjungan Anda.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 text-neutral-200 text-xs font-medium">
            <span>support@itihasa.com</span>
            <span class="hidden sm:inline text-neutral-600">•</span>
            <span>+62 812-3456-7890</span>
        </div>
    </div>

</section>

@endsection
