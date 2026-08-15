@extends('layouts.user')

@section('title', 'Syarat & Ketentuan')

@section('content')

<section class="max-w-3xl mx-auto py-6 sm:py-10">

    <div class="text-center mb-6 sm:mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#B88A44]/15 text-[#B88A44] text-xs font-bold uppercase tracking-wider mb-2">
            Dokumen Legal
        </span>
        <h1 class="text-xl sm:text-2xl font-bold text-[#102A43]">
            Syarat & Ketentuan
        </h1>
        <p class="text-xs text-slate-500 mt-1">
            Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-[#EADBC8] p-4 sm:p-8 space-y-6 text-slate-600 text-xs sm:text-sm leading-relaxed shadow-sm">

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">1. Pemesanan Tiket</h2>
            <p>Setiap pemesanan tiket harus dilakukan melalui akun terdaftar. Jumlah anggota yang diisi harus sesuai dengan total tiket yang dipilih. Untuk pemesanan 10 orang atau lebih, pengguna wajib mengisi manifes berupa nama seluruh anggota rombongan.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">2. Pembayaran</h2>
            <p>Booking yang belum dibayar memiliki batas waktu tertentu. Apabila batas waktu tersebut terlewati tanpa pembayaran, booking akan otomatis dibatalkan dan kuota tiket dikembalikan ke sistem.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">3. Kebijakan Pembatalan</h2>
            <ul class="list-disc list-inside space-y-1 text-slate-600 pl-1">
                <li>Booking yang <strong>belum dibayar</strong> dapat dibatalkan kapan saja sebelum batas waktu pembayaran habis.</li>
                <li>Booking yang <strong>sudah dibayar</strong> hanya dapat dibatalkan maksimal <strong>24 jam</strong> sebelum jadwal kunjungan.</li>
                <li>Alasan pembatalan wajib diisi setiap kali pengguna mengajukan pembatalan.</li>
                <li>Pengembalian dana (refund) untuk booking yang sudah dibayar akan ditindaklanjuti secara manual oleh tim kami setelah pembatalan berhasil diproses.</li>
                <li>Booking yang sudah digunakan (tiket telah divalidasi di lokasi museum) tidak dapat dibatalkan.</li>
            </ul>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">4. Masa Berlaku Tiket</h2>
            <p>Tiket hanya berlaku untuk tanggal kunjungan yang dipilih saat pemesanan, sesuai jam operasional museum pada tanggal tersebut. Tiket yang tidak digunakan hingga melewati jam operasional pada tanggal kunjungan akan berstatus kedaluwarsa dan tidak dapat digunakan atau dikembalikan.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">5. Tata Tertib Kunjungan</h2>
            <p>Pengunjung wajib menunjukkan QR Code tiket digital kepada petugas museum sebelum memasuki area museum. Setiap pengunjung diharapkan menjaga ketertiban dan mengikuti aturan yang berlaku di masing-masing museum selama kunjungan berlangsung.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">6. Ulasan Pengguna</h2>
            <p>Ulasan hanya dapat diberikan oleh pengguna yang telah menyelesaikan kunjungan (tiket telah divalidasi). Kami berhak menghapus ulasan yang dinilai tidak relevan, menyesatkan, atau melanggar ketentuan penggunaan platform.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">7. Batasan Tanggung Jawab</h2>
            <p>Itihasa berupaya menyediakan informasi jadwal dan ketersediaan tiket seakurat mungkin. Namun, kami tidak bertanggung jawab atas perubahan jadwal operasional museum yang terjadi di luar kendali kami, termasuk penutupan mendadak akibat kondisi tak terduga.</p>
        </div>

        <div>
            <h2 class="text-sm sm:text-base font-bold text-[#102A43] mb-1.5">8. Perubahan Ketentuan</h2>
            <p>Kami dapat memperbarui syarat dan ketentuan ini dari waktu ke waktu. Perubahan akan berlaku efektif sejak dipublikasikan pada halaman ini.</p>
        </div>

    </div>

</section>

@endsection
