# Roadmap Fitur Itihasa

## ✅ Sudah Selesai (Completed Features)

### 👑 1. Sisi Super Admin (Pengawas Ekosistem & Otoritas Global)
- **Dashboard Monitoring Ekosistem Real-time**:
  - 8 Metrik KPI global (Omzet nasional, total transaksi paid/pending, okupansi, total museum, users).
  - Grafik tren omzet 6 bulan & donut chart distribusi omzet per museum.
  - Matriks kinerja & penanggung jawab per museum dengan okupansi kuota harian.
  - Live feed transaksi nasional & tombol cetak laporan.
- **Filter Rentang Waktu Fleksibel (Fitur A Super Admin)**:
  - Preset filter: *Semua Waktu*, *Hari Ini*, *Minggu Ini*, *Bulan Ini*, *Tahun Ini*, serta *Kustom Rentang Tanggal*.
  - Menghitung KPI, performa, dan log transaksi secara dinamis sesuai tanggal terpilih.
- **Kontrol Status Operasional & Kemitraan Museum (Fitur C Super Admin)**:
  - Status kemitraan: `Aktif Buka`, `Tutup Sementara / Renovasi`, atau `Nonaktif`.
  - Integrasi proteksi: Otomatis memunculkan banner peringatan resmi dan mengunci form pembelian tiket di halaman publik saat museum berstatus renovasi/nonaktif.
- **Pusat Broadcast & Instruksi Resmi Global (Fitur E Super Admin)**:
  - CRUD & status toggle pengumuman resmi Super Admin dengan tingkat urgensi (*Info, Peringatan, Kritis, Sukses*), masa berlaku, dan target penerima (*Semua, Admin Museum, Petugas Gate*).
  - Banner notifikasi otomatis tampil di bagian teratas dashboard seluruh Admin Museum dan halaman pemindai Petugas Gate.
- **Manajemen Akun Admin Museum & Pengguna**:
  - Registrasi dan penugasan akun Admin untuk setiap museum mitra.
  - Pemisahan peran ketat (*Role isolation*): Super Admin fokus pada pendaftaran admin dan pemantauan (*View-Only / Mode Pantau*).
- **Redesain Direktori Museum Super Admin**:
  - Tampilan modern bertema ungu dengan 4 KPI ringkasan museum, multi-filter (*Kategori & Status*), data PIC admin penanggung jawab, dan aksi cepat publik/edit/status.

---

### 🏛️ 2. Sisi Admin Museum (Operasional & Manajemen)
- **Balas Ulasan Pengunjung / Respon Resmi Pengelola (Fitur B Admin)**:
  - Admin Museum dapat memberikan tanggapan resmi (*Official Response*) terhadap ulasan pengunjung.
  - Tampil di halaman publik museum dengan badge **🏛 Respon Resmi Pengelola**.
- **Kalender Hari Libur Khusus & Kuota Dinamis (Fitur C Admin)**:
  - Fitur **Blackout Dates**: Menandai tanggal libur khusus/pemeliharaan lokal sehingga terkunci di form reservasi online.
  - Fitur **Daily Quota Override**: Menyesuaikan kapasitas kuota harian khusus saat periode peak season / hari libur nasional.
- **Rekap Tutup Kas Harian & Ekspor Finansial Loket (Fitur D Admin)**:
  - Laporan tutup kas (*End of Day*): Rekapitulasi perbandingan pembayaran tunai loket vs QRIS/Transfer.
  - Ekspor data transaksi dan manifes pengunjung ke format CSV (UTF-8 BOM Excel-ready) serta format cetak arsip bendahara.
- **Kode Promo & Kupon Diskon Tiket (Fitur E Admin)**:
  - Pembuatan kupon diskon (tipe persentase atau potongan nominal tetap) dengan kuota klaim, minimal belanja, dan masa berlaku.
  - Validasi AJAX interaktif langsung di halaman pemesanan tiket pengunjung.
- **Pengaturan Mandiri Profil & Fasilitas Museum (Fitur F Admin)**:
  - Pengelolaan mandiri deskripsi, alamat, kontak WhatsApp/Telepon, jam operasional mingguan, fasilitas, serta galeri foto museum.
- **Manajemen Akun Petugas Loket**:
  - Admin Museum dapat membentuk dan mengelola akun staf pemindai (*gate officers*) khusus untuk museum binaannya.

---

### 📷 3. Sisi Petugas Gate / Scanner (Validasi Lapangan)
- **Validasi Manifes Rombongan Bertahap (Fitur A Petugas)**:
  - Pemindaian tiket rombongan (≥10 pax atau tiket grup) dengan checklist manifes per individu.
  - Tiket QR tetap aktif dan progress bar diperbarui secara real-time (*"X dari Y orang sudah masuk"*) hingga seluruh anggota rombongan selesai masuk.
- **Mode Offline Scanner & Auto-Sync (Fitur C Petugas)**:
  - Sinkronisasi data tiket hari berjalan ke *Local Storage* perangkat petugas scanner.
  - Scanner tetap dapat memvalidasi tiket meskipun koneksi internet drop/putus di gate, dan otomatis melakukan sinkronisasi massal (*Auto-Sync*) ke server saat sinyal internet pulih.
- **Widget Ringkasan Shift Kerja Petugas (Fitur D Petugas)**:
  - Menampilkan jam mulai bertugas, total pemindaian tiket hari ini, total pengunjung yang telah dilayani, dan status gate aktif.
- **Catatan Pintu Masuk & Label Insiden / Gate Notes (Fitur E Petugas)**:
  - Fitur sematan label cepat (*VIP, Kursi Roda/Disabilitas, Barang Tertinggal, Kompensasi Tiket, Kendala Gate*) dan catatan teks saat validasi tiket.

---

### 🎒 4. Sisi User / Pengunjung (Eksplorasi, Gamifikasi & Ulasan)
- **Unggah Foto Pengalaman pada Ulasan (Fitur B User)**:
  - Pengunjung dapat melampirkan 1–3 foto pengalaman kunjungan saat memberikan ulasan dan rating bintang.
  - Dilengkapi Lightbox modal interaktif untuk melihat foto ulasan beresolusi penuh.
- **Paspor Digital Museum & Lencana Wisatawan (Fitur C User)**:
  - Stempel digital (*Digital Stamp*) otomatis di halaman profil setiap kali tiket berhasil dipindai oleh petugas gate.
  - Koleksi 6 Lencana Penjelajah (*Explorer Badges*): *"Perintis Budaya"*, *"Penjelajah Sejarah"*, *"Apresiator Seni"*, *"Pecinta Sains & Bumi"*, *"Mahapatih Nusantara"*, dan *"Kurator Visual"*.
  - Level penjelajah bertingkat (*Perintis Pemula*, *Wisatawan Muda*, *Penjelajah Madya*, *Mahapatih Budaya*).
- **Rekomendasi Rute Wisata & Destinasi Terdekat (Fitur D User)**:
  - Modul *One-Day Cultural Trail* pada detail museum: merekomendasikan museum terdekat di area yang sama, lengkap dengan estimasi jarak, waktu tempuh, status buka/tutup, dan tombol rute Google Maps.
- **Filter Cepat Berdasarkan Kota, Kategori & Rentang Harga (Fitur F User)**:
  - Fitur pencarian multi-kriteria di homepage: Filter instan berdasarkan Kota (*Bandung, Jakarta, Yogyakarta, Solo, dll.*), Kategori (*Sejarah, Seni & Galeri, Budaya, Alam & Sains, Religius*), dan Rentang Harga (*Gratis, ≤ 25k, 25k-50k, > 50k*).
  - Quick filter chips 1-klik dan tombol reset filter.
- **Fitur Pendukung Pengunjung Lainnya**:
  - Wishlist / Favorit museum, bagikan museum ke WhatsApp/salin tautan, pembatalan booking mandiri (*Cancel Ticket*), pusat bantuan & FAQ, panduan privasi & ketentuan layanan.

---

### ⚙️ 5. Fondasi Sistem & Keamanan
- **Arsitektur 4 Role Pengguna**: `super_admin`, `admin`, `staff` (petugas), dan `visitor` (pengunjung) dengan isolasi middleware yang ketat.
- **Desain Antarmuka Responsif Modern**:
  - Tata letak responsif penuh (*Mobile drawer*, *Tablet icon-bar*, *Desktop sidebar*).
  - Tema visual Heritage bernada hangat (*Warm Cream `#F6F1E8`*, *Navy `#102A43`*, *Heritage Gold `#B88A44`* untuk publik dan *Executive Purple* untuk Super Admin).
- **Automated Test Suite**: 31 unit & feature test suites dengan 151 assertions lulus 100% dan standar format Laravel Pint.

---

## 📋 Sisa Tugas / Rencana Pengembangan Berikutnya (Backlog)

### 1. Kasir Penjualan Manual di Loket (Walk-in Ticket / POS)
- Form "Jual Tiket Manual" di dashboard Admin Museum untuk pengunjung yang datang langsung membeli di loket fisik museum (tanpa reservasi online mandiri).
- Menggunakan kalkulasi kuota yang sama (`getSoldQty()`) agar kuota tiket fisik dan online tetap sinkron serta terhindar dari *over-capacity*.
- Transaksi langsung berstatus `paid` dan waktu pemakaian `used_at` langsung terisi otomatis.

### 2. Integrasi Midtrans Payment Gateway (Otomatisasi Pembayaran)
- Verifikasi status pembayaran tiket secara otomatis dan real-time menggunakan Webhook / HTTP Notification Callback Midtrans (Snap/Core API).
- Menggantikan atau melengka
pi alur pembayaran manual yang ada saat ini.

### 3. Auto-send Tiket Digital via Email
- Pengiriman e-ticket otomatis (file PDF tiket / gambar QR Code) langsung ke alamat email pengunjung begitu status pembayaran terkonfirmasi lunas dari Midtrans.

---

## 💡 Ringkasan Alur Ketergantungan Sisa Fitur
$$\text{Kasir Loket (Walk-in)} \longrightarrow \text{Integrasi Midtrans (Online)} \longrightarrow \text{Auto-send Email Tiket}$$
