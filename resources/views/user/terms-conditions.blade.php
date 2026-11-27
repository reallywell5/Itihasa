@extends('layouts.user')

@section('title', 'Syarat & Ketentuan')

@section('content')

<section class="max-w-3xl mx-auto py-6 sm:py-10">

    <div class="text-center mb-10 pb-6 border-b border-neutral-200">
        <span class="inline-block text-[11px] font-semibold uppercase tracking-[0.22em] text-[#B8860B] mb-2">
            Dokumen Kelembagaan
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-[#1A1A1A] tracking-tight">
            Syarat &amp; Ketentuan
        </h1>
        <p class="text-xs text-[#5A5A5A] mt-2">
            Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>

    <div class="bg-[#F8F7F4] rounded-sm border border-neutral-200 p-6 sm:p-10 space-y-6 text-[#5A5A5A] text-xs sm:text-sm leading-relaxed">

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">1. Pemesanan Tiket</h2>
            <p>Setiap pemesanan tiket harus dilakukan melalui akun terdaftar. Jumlah anggota yang diisi harus sesuai dengan total tiket yang dipilih. Untuk pemesanan 6 orang atau lebih, pengguna wajib mengisi manifes berupa nama seluruh anggota rombongan.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">2. Pembayaran</h2>
            <p>Booking yang belum dibayar memiliki batas waktu tertentu. Apabila batas waktu tersebut terlewati tanpa pembayaran, booking akan otomatis dibatalkan dan kuota tiket dikembalikan ke sistem.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">3. Kebijakan Pembatalan</h2>
            <ul class="list-disc list-inside space-y-1 pl-1">
                <li>Booking yang <strong class="text-[#1A1A1A]">belum dibayar</strong> dapat dibatalkan kapan saja sebelum batas waktu pembayaran habis.</li>
                <li>Booking yang <strong class="text-[#1A1A1A]">sudah dibayar</strong> hanya dapat dibatalkan maksimal <strong class="text-[#1A1A1A]">24 jam</strong> sebelum jadwal kunjungan.</li>
                <li>Alasan pembatalan wajib diisi setiap kali pengguna mengajukan pembatalan.</li>
                <li>Pengembalian dana (refund) untuk booking yang sudah dibayar akan ditindaklanjuti secara manual oleh tim kami setelah pembatalan berhasil diproses.</li>
                <li>Booking yang sudah digunakan (tiket telah divalidasi di lokasi museum) tidak dapat dibatalkan.</li>
            </ul>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">4. Masa Berlaku Tiket</h2>
            <p>Tiket hanya berlaku untuk tanggal kunjungan yang dipilih saat pemesanan, sesuai jam operasional museum pada tanggal tersebut. Tiket yang tidak digunakan hingga melewati jam operasional pada tanggal kunjungan akan berstatus kedaluwarsa dan tidak dapat digunakan atau dikembalikan.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">5. Tata Tertib Kunjungan</h2>
            <p>Pengunjung wajib menunjukkan QR Code tiket digital kepada petugas museum sebelum memasuki area museum. Setiap pengunjung diharapkan menjaga ketertiban dan mengikuti aturan yang berlaku di masing-masing museum selama kunjungan berlangsung.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">6. Ulasan Pengguna</h2>
            <p>Ulasan hanya dapat diberikan oleh pengguna yang telah menyelesaikan kunjungan (tiket telah divalidasi). Kami berhak menghapus ulasan yang dinilai tidak relevan, menyesatkan, atau melanggar ketentuan penggunaan platform.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">7. Batasan Tanggung Jawab</h2>
            <p>Itihasa berupaya menyediakan informasi jadwal dan ketersediaan tiket seakurat mungkin. Namun, kami tidak bertanggung jawab atas perubahan jadwal operasional museum yang terjadi di luar kendali kami, termasuk penutupan mendadak akibat kondisi tak terduga.</p>
        </div>

        <div>
            <h2 class="font-serif text-lg font-semibold text-[#1A1A1A] mb-2">8. Perubahan Ketentuan</h2>
            <p>Kami dapat memperbarui syarat dan ketentuan ini dari waktu ke waktu. Perubahan akan berlaku efektif sejak dipublikasikan pada halaman ini.</p>
        </div>

    </div>

</section>

@endsection
