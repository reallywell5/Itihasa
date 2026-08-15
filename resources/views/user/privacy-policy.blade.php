@extends('layouts.user')

@section('title', 'Kebijakan Privasi')

@section('content')

<section class="max-w-3xl mx-auto py-6 sm:py-10">

    <div class="text-center mb-6 sm:mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#B88A44]/15 text-[#B88A44] text-xs font-bold uppercase tracking-wider mb-2">
            Dokumen Legal
        </span>
        <h1 class="text-xl sm:text-2xl font-bold text-[#102A43]">
            Kebijakan Privasi
        </h1>
        <p class="text-xs text-slate-500 mt-1">
            Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-8 space-y-6 text-slate-600 text-xs sm:text-sm leading-relaxed shadow-sm">

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">1. Data yang Kami Kumpulkan</h2>
            <p class="mb-2">Untuk memberikan layanan pemesanan tiket museum, kami mengumpulkan data berikut saat kamu menggunakan Itihasa:</p>
            <ul class="list-disc list-inside space-y-1 text-slate-600 pl-1">
                <li>Nama lengkap dan alamat email (saat pendaftaran akun)</li>
                <li>Nomor telepon (saat melakukan booking)</li>
                <li>Kota atau negara asal (saat melakukan booking)</li>
                <li>Riwayat transaksi dan kunjungan museum</li>
                <li>Ulasan dan rating yang kamu berikan</li>
                <li>Nama anggota rombongan (khusus booking rombongan, disimpan sebagai manifes kunjungan)</li>
            </ul>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">2. Bagaimana Data Digunakan</h2>
            <p>Data yang kamu berikan digunakan semata-mata untuk memproses pemesanan tiket, mengirimkan tiket digital, memvalidasi kunjungan di lokasi museum, serta menampilkan riwayat transaksi dan ulasan pada akunmu. Kami tidak menggunakan data pribadi untuk tujuan di luar operasional platform.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">3. Berbagi Data dengan Pihak Ketiga</h2>
            <p>Saat ini Itihasa tidak membagikan data pribadi kamu ke pihak ketiga mana pun. Ke depan, jika kami mengintegrasikan layanan pembayaran atau notifikasi pihak ketiga (misalnya payment gateway atau layanan pesan otomatis), data yang dibagikan akan dibatasi hanya pada informasi yang diperlukan untuk memproses transaksi tersebut, dan kebijakan ini akan diperbarui sesuai kebutuhan.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">4. Penyimpanan Data</h2>
            <p>Data kamu disimpan selama akun masih aktif digunakan. Data transaksi dan riwayat kunjungan tetap disimpan untuk keperluan riwayat dan pelaporan, kecuali kamu secara eksplisit meminta penghapusan akun.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">5. Hak Kamu Sebagai Pengguna</h2>
            <ul class="list-disc list-inside space-y-1 text-slate-600 pl-1">
                <li>Mengakses dan memperbarui data pribadi melalui halaman Profil</li>
                <li>Meminta penghapusan akun dan data terkait dengan menghubungi kontak di bawah</li>
                <li>Menanyakan penggunaan data pribadimu kapan saja</li>
            </ul>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">6. Hubungi Kami</h2>
            <p>Jika kamu memiliki pertanyaan mengenai kebijakan privasi ini, silakan hubungi kami melalui email <strong class="text-[#102A43]">support@itihasa.com</strong> atau telepon <strong class="text-[#102A43]">+62 812-3456-7890</strong>.</p>
        </div>

    </div>

</section>

@endsection
