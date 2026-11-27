@extends('layouts.user')

@section('title', 'Kebijakan Privasi')

@section('content')

<section class="max-w-3xl mx-auto py-6 sm:py-10">

    <div class="text-center mb-10 pb-6 border-b border-neutral-200">
        <span class="inline-block text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] mb-2">
            Dokumen Kelembagaan
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[#1A1A1A] tracking-tight">
            Kebijakan Privasi
        </h1>
        <p class="text-xs text-[#5A5A5A] mt-2">
            Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>

    <div class="bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6 sm:p-10 space-y-6 text-[#5A5A5A] text-xs sm:text-sm leading-relaxed">

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">1. Data yang Kami Kumpulkan</h2>
            <p class="mb-2">Untuk memberikan layanan pemesanan tiket museum, kami mengumpulkan data berikut saat kamu menggunakan Itihasa:</p>
            <ul class="list-disc list-inside space-y-1 pl-1">
                <li>Nama lengkap dan alamat email (saat pendaftaran akun)</li>
                <li>Nomor telepon (saat melakukan booking)</li>
                <li>Kota atau negara asal (saat melakukan booking)</li>
                <li>Riwayat transaksi dan kunjungan museum</li>
                <li>Ulasan dan rating yang kamu berikan</li>
                <li>Nama anggota rombongan (khusus booking rombongan, disimpan sebagai manifes kunjungan)</li>
            </ul>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">2. Bagaimana Data Digunakan</h2>
            <p>Data yang kamu berikan digunakan semata-mata untuk memproses pemesanan tiket, mengirimkan tiket digital, memvalidasi kunjungan di lokasi museum, serta menampilkan riwayat transaksi dan ulasan pada akunmu. Kami tidak menggunakan data pribadi untuk tujuan di luar operasional platform.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">3. Berbagi Data dengan Pihak Ketiga</h2>
            <p>Saat ini Itihasa tidak membagikan data pribadi kamu ke pihak ketiga mana pun. Ke depan, jika kami mengintegrasikan layanan pembayaran atau notifikasi pihak ketiga (misalnya payment gateway atau layanan pesan otomatis), data yang dibagikan akan dibatasi hanya pada informasi yang diperlukan untuk memproses transaksi tersebut, dan kebijakan ini akan diperbarui sesuai kebutuhan.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">4. Penyimpanan Data</h2>
            <p>Data kamu disimpan selama akun masih aktif digunakan. Data transaksi dan riwayat kunjungan tetap disimpan untuk keperluan riwayat dan pelaporan, kecuali kamu secara eksplisit meminta penghapusan akun.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">5. Hak Kamu Sebagai Pengguna</h2>
            <ul class="list-disc list-inside space-y-1 pl-1">
                <li>Mengakses dan memperbarui data pribadi melalui halaman Profil</li>
                <li>Meminta penghapusan akun dan data terkait dengan menghubungi kontak di bawah</li>
                <li>Menanyakan penggunaan data pribadimu kapan saja</li>
            </ul>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">6. Hubungi Kami</h2>
            <p>Jika kamu memiliki pertanyaan mengenai kebijakan privasi ini, silakan hubungi kami melalui email <strong class="text-[#1A1A1A]">support@itihasa.com</strong> atau telepon <strong class="text-[#1A1A1A]">+62 812-3456-7890</strong>.</p>
        </div>

    </div>

</section>

@endsection
