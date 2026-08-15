@extends('layouts.user')

@section('title', 'FAQ & Bantuan')

@section('content')

<section class="max-w-3xl mx-auto py-6 sm:py-10">

    {{-- HEADER --}}
    <div class="text-center mb-6 sm:mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#B88A44]/15 text-[#B88A44] text-xs font-bold uppercase tracking-wider mb-2">
            Pusat Bantuan
        </span>
        <h1 class="text-xl sm:text-2xl font-bold text-[#102A43]">
            Pertanyaan yang Sering Diajukan
        </h1>
        <p class="text-xs text-slate-500 mt-1">
            Temukan panduan lengkap seputar reservasi tiket, pembayaran, dan kunjungan museum kamu.
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
                    'a' => 'Booking untuk 10 orang atau lebih otomatis dianggap sebagai rombongan. Kamu akan diminta mengisi manifes berupa nama seluruh anggota, yang nantinya digunakan petugas untuk verifikasi saat kunjungan.',
                ],
                [
                    'q' => 'Berapa lama batas waktu pembayaran?',
                    'a' => 'Booking yang belum dibayar memiliki batas waktu tertentu sebelum otomatis kedaluwarsa. Kamu bisa memantau sisa waktu pembayaran di halaman Profil, bagian "Menunggu Pembayaran".',
                ],
            ],
            'Pembatalan & Perubahan' => [
                [
                    'q' => 'Bisakah saya membatalkan booking yang belum dibayar?',
                    'a' => 'Bisa, kapan saja sebelum batas waktu pembayaran habis. Buka halaman Profil, temukan booking terkait di bagian "Menunggu Pembayaran", lalu klik "Batalkan" dan isi alasan pembatalan.',
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
                    'a' => 'Buka halaman "Lihat Tiket QR" pada booking kamu, lalu tunjukkan QR Code tersebut kepada petugas di lokasi museum untuk divalidasi. Kamu juga bisa mengunduh tiket dalam bentuk gambar jika ingin menyimpannya offline.',
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
    <div class="space-y-6" x-data="{ openId: null }">
        @foreach ($faqGroups as $groupName => $items)
            <div>
                <h2 class="text-sm font-bold text-[#102A43] mb-2 px-1">
                    {{ $groupName }}
                </h2>

                <div class="bg-white rounded-2xl border border-[#EADBC8] divide-y divide-[#EADBC8]/60 overflow-hidden shadow-sm">
                    @foreach ($items as $item)
                        @php $itemId = Str::slug($groupName . '-' . $item['q']); @endphp

                        <div>
                            <button type="button"
                                    @click="openId = openId === '{{ $itemId }}' ? null : '{{ $itemId }}'"
                                    class="w-full flex items-center justify-between gap-3 text-left p-3.5 sm:p-4 hover:bg-[#F9F7F2] transition">
                                <span class="font-bold text-xs text-[#102A43]">{{ $item['q'] }}</span>
                                <svg class="w-4 h-4 shrink-0 text-[#B88A44] transition-transform"
                                     :class="openId === '{{ $itemId }}' ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="openId === '{{ $itemId }}'" x-cloak x-collapse>
                                <p class="px-3.5 sm:px-4 pb-3.5 text-xs text-slate-600 leading-relaxed border-t border-[#EADBC8]/30 pt-2 bg-[#F9F7F2]/40">
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
    <div class="mt-8 bg-[#102A43] rounded-2xl p-5 sm:p-6 text-center text-white shadow">
        <h3 class="text-sm sm:text-base font-bold mb-1">
            Masih ada pertanyaan lain?
        </h3>
        <p class="text-white/70 text-xs mb-3">
            Tim layanan dukungan Itihasa siap membantu kendala kamu.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 text-white/90 text-xs">
            <span>📧 support@itihasa.com</span>
            <span class="hidden sm:inline">•</span>
            <span>📞 +62 812-3456-7890</span>
        </div>
    </div>

</section>

@endsection
