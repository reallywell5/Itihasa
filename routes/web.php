<?php

use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\DashboardWebController;
use App\Http\Controllers\Admin\GalleryWebController;
use App\Http\Controllers\Admin\MuseumProfileController;
use App\Http\Controllers\Admin\MuseumWebController;
use App\Http\Controllers\Admin\PaymentWebController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ReviewWebController;
use App\Http\Controllers\Admin\SpecialDateController;
use App\Http\Controllers\Admin\TicketWebController;
use App\Http\Controllers\Admin\TransactionWebController;
use App\Http\Controllers\Admin\UserWebController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Petugas\DashboardController;
use App\Http\Controllers\Petugas\PengunjungController;
use App\Http\Controllers\Petugas\ProfileController as PetugasProfileController;
use App\Http\Controllers\Petugas\QRCodeController;
use App\Http\Controllers\Petugas\ScanController;
use App\Http\Controllers\Petugas\ValidasiController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewReportController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\MuseumController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/', [HomeController::class, 'index'])->name('landing');
Route::get('/user/home', [HomeController::class, 'index'])->name('user.home');
Route::get('/museum/{museum}', [MuseumController::class, 'show'])->name('museum.detail');

Route::middleware(['auth'])->group(function () {

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('user.wishlist');
    Route::post('/wishlist/{museum}', [WishlistController::class, 'store'])->name('user.wishlist.store');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('user.wishlist.destroy');

    Route::get('/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');

    // BOOKING
    Route::get('/booking/{museum}', [BookingController::class, 'create'])->name('user.booking');
    Route::post('/booking/{museum}', [BookingController::class, 'store'])->name('user.booking.store');
    Route::get('/booking/{museum}/quota', [BookingController::class, 'checkQuota'])->name('user.booking.quota');
    Route::post('/booking/{museum}/check-promo', [BookingController::class, 'checkPromo'])->name('user.booking.check-promo');

    // PAYMENT
    Route::get('/payment/{booking}', [PaymentController::class, 'index'])->name('user.payment');
    Route::post('/payment/{booking}', [PaymentController::class, 'process'])->name('user.payment.process');
    Route::get('/payment/show/{transaction}', [PaymentController::class, 'show3'])->name('user.payment.show3');
    Route::post('/payment/confirm/{transaction}', [PaymentController::class, 'confirm'])->name('user.payment.confirm');

    // TRANSACTION
    Route::get('/transaction/{transaction}', [TransactionController::class, 'show'])->name('user.transaction.show');

    // QR TICKET
    Route::get('/ticket/{transaction}', [TransactionController::class, 'ticket'])->name('user.ticket');
    Route::get('/transaction/{id}/download-ticket', [TransactionController::class, 'downloadTicket'])
        ->name('user.ticket.download');

    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])
        ->name('reviews.store')
        ->middleware('auth');

    Route::put('/reviews/{review}', [ReviewController::class, 'update'])
        ->name('reviews.update')
        ->middleware('auth');

    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel')
        ->middleware('auth');

    Route::post('/reviews/{review}/report', [ReviewReportController::class, 'store'])
        ->name('reviews.report')
        ->middleware('auth');
});

// Rute Uji Coba QR
Route::get('/test-qr', function () {
    return QrCode::size(200)->generate('Halo Aurel');
});

// =============================
// ROUTE ADMIN
// =============================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('admin.dashboard');

    Route::resource('petugas', PetugasController::class)->names([
        'index' => 'admin.petugas.index',
        'create' => 'admin.petugas.create',
        'store' => 'admin.petugas.store',
        'edit' => 'admin.petugas.edit',
        'update' => 'admin.petugas.update',
        'destroy' => 'admin.petugas.destroy',
    ]);

    // Fitur C Super Admin: Kontrol Status Operasional & Kemitraan Museum
    Route::post('/museums/{museum}/status', [MuseumWebController::class, 'updateStatus'])->name('admin.museums.status');
    Route::resource('museums', MuseumWebController::class);

    // Fitur F Admin: Pengaturan Mandiri Profil & Fasilitas Museum
    Route::get('/museum-profile', [MuseumProfileController::class, 'edit'])->name('admin.museum.profile.edit');
    Route::put('/museum-profile', [MuseumProfileController::class, 'update'])->name('admin.museum.profile.update');

    Route::resource('tickets', TicketWebController::class);
    Route::resource('users', UserWebController::class)->middleware('super_admin');

    // Fitur E Super Admin: Broadcast / Pengumuman Resmi Global
    Route::post('/broadcasts/{broadcast}/toggle', [BroadcastController::class, 'toggle'])->name('admin.broadcasts.toggle')->middleware('super_admin');
    Route::resource('broadcasts', BroadcastController::class)->names('admin.broadcasts')->middleware('super_admin');

    Route::resource('payments', PaymentWebController::class)->only(['index', 'show']);

    // Fitur D Admin: Rekap Tutup Kas & Ekspor CSV
    Route::get('/transactions/rekap', [TransactionWebController::class, 'rekapKas'])->name('admin.transactions.rekap');
    Route::get('/transactions/export', [TransactionWebController::class, 'exportCsv'])->name('admin.transactions.export');
    Route::resource('transactions', TransactionWebController::class)->only(['index', 'show']);

    Route::resource('galleries', GalleryWebController::class)
        ->only(['index', 'create', 'store', 'destroy']);

    // Fitur B Admin: Balas Ulasan Pengunjung
    Route::post('/reviews/{review}/reply', [ReviewWebController::class, 'reply'])->name('admin.reviews.reply');
    Route::delete('/reviews/{review}/reply', [ReviewWebController::class, 'destroyReply'])->name('admin.reviews.reply.destroy');
    Route::resource('reviews', ReviewWebController::class)
        ->names('admin.reviews')
        ->only(['index', 'destroy']);

    // Fitur C Admin: Kalender Hari Libur Khusus / Blackout Dates & Kuota Dinamis
    Route::get('/special-dates', [SpecialDateController::class, 'index'])->name('admin.special-dates.index');
    Route::post('/special-dates', [SpecialDateController::class, 'store'])->name('admin.special-dates.store');
    Route::delete('/special-dates/{specialDate}', [SpecialDateController::class, 'destroy'])->name('admin.special-dates.destroy');

    // Fitur E Admin: Kode Promo & Kupon Diskon
    Route::post('/promos/{promo}/toggle', [PromoController::class, 'toggle'])->name('admin.promos.toggle');
    Route::resource('promos', PromoController::class)->names('admin.promos');
});

// =============================
// ROUTE PETUGAS
// =============================
Route::prefix('petugas')->middleware(['auth', 'petugas'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('petugas.dashboard');

    Route::get('/qrcodes', [QRCodeController::class, 'index'])->name('petugas.qrcodes.index');
    Route::get('/qrcodes/{transaction}', [QRCodeController::class, 'show'])->name('petugas.qrcodes.show');

    Route::get('/scan', [ScanController::class, 'index'])->name('petugas.qrcodes.scan');
    Route::post('/scan/validate', [ScanController::class, 'validateQr'])->name('petugas.qrcodes.validate');
    Route::get('/scan/offline-cache', [ScanController::class, 'offlineCache'])->name('petugas.scan.offline-cache');
    Route::post('/scan/sync-offline', [ScanController::class, 'syncOffline'])->name('petugas.scan.sync-offline');

    Route::get('/validasi', [ValidasiController::class, 'index'])->name('petugas.validasi');

    Route::get('/pengunjung', [PengunjungController::class, 'index'])->name('petugas.pengunjung');
    Route::get('/riwayat', [ScanController::class, 'riwayat'])->name('petugas.riwayat');

    Route::get('/profil', [PetugasProfileController::class, 'index'])->name('petugas.profil');
    Route::get('/profil/edit', [PetugasProfileController::class, 'edit'])->name('petugas.profil.edit');
    Route::put('/profil/update', [PetugasProfileController::class, 'update'])->name('petugas.profil.update');
});

// Route Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::view('/faq', 'user.faq')->name('faq');
Route::view('/privacy-policy', 'user.privacy-policy')->name('privacy-policy');
Route::view('/terms-conditions', 'user.terms-conditions')->name('terms-conditions');
