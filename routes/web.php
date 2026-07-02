<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Http\Controllers\Admin\MuseumWebController;
use App\Http\Controllers\Admin\TicketWebController;
use App\Http\Controllers\Admin\TransactionWebController;
use App\Http\Controllers\Admin\DashboardWebController;
use App\Http\Controllers\Admin\UserWebController;
use App\Http\Controllers\Admin\PaymentWebController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Petugas\DashboardController;
use App\Http\Controllers\Petugas\QRCodeController;
use App\Http\Controllers\Petugas\ValidasiController;
use App\Http\Controllers\Petugas\ScanController;
use App\Http\Controllers\Petugas\PengunjungController;
use App\Http\Controllers\Petugas\ProfileController as PetugasProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\MuseumController;

    Route::get('/', [HomeController::class, 'index'])->name('landing');

    Route::get('/user/home', [HomeController::class, 'index'])->name('user.home');

    Route::get('/museum/{museum}', [MuseumController::class, 'show'])
        ->name('museum.detail');


Route::middleware(['auth'])->group(function () {

    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('user.wishlist');

    Route::post('/wishlist/{museum}', [WishlistController::class, 'store'])
        ->name('user.wishlist.store');

    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])
        ->name('user.wishlist.destroy');

    Route::get('/profile', [UserProfileController::class, 'index'])
        ->name('user.profile');

    Route::get('/profile/edit', [UserProfileController::class, 'edit'])
        ->name('user.profile.edit');

    Route::put('/profile/update', [UserProfileController::class, 'update'])
        ->name('user.profile.update');

    // BOOKING
    Route::get('/booking/{museum}', [BookingController::class, 'create'])
        ->name('user.booking');

    Route::post('/booking/{museum}', [BookingController::class, 'store'])
        ->name('user.booking.store');

    // PAYMENT
    Route::get('/payment/{booking}', [PaymentController::class, 'index'])
        ->name('user.payment');

    Route::post('/payment/{booking}', [PaymentController::class, 'process'])
        ->name('user.payment.process');

    Route::get('/payment/show/{transaction}', [PaymentController::class, 'show3'])
        ->name('user.payment.show3');

    Route::post('/payment/confirm/{transaction}', [PaymentController::class, 'confirm'])
    ->name('user.payment.confirm');

    // TRANSACTION
    Route::get('/transaction/{transaction}', [TransactionController::class, 'show'])
        ->name('user.transaction.show');

    // QR TICKET
    Route::get('/ticket/{transaction}', [TransactionController::class, 'ticket'])
        ->name('user.ticket');

});

// Rute Uji Coba QR
Route::get('/test-qr', function () {
    return QrCode::size(200)->generate('Halo Aurel');
});

// =============================
// ROUTE ADMIN
// =============================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardWebController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('petugas', PetugasController::class)->names([
        'index'   => 'admin.petugas.index',
        'create'  => 'admin.petugas.create',
        'store'   => 'admin.petugas.store',
        'edit'    => 'admin.petugas.edit',
        'update'  => 'admin.petugas.update',
        'destroy' => 'admin.petugas.destroy',
    ]);

    Route::resource('museums', MuseumWebController::class);
    Route::resource('tickets', TicketWebController::class);
    Route::resource('users', UserWebController::class);
    Route::resource('payments', PaymentWebController::class);

    Route::get('/transactions', [TransactionWebController::class, 'index'])
        ->name('transactions.index');

    Route::get('/transactions/{id}', [TransactionWebController::class, 'show'])
        ->name('transactions.show');
});

// =============================
// ROUTE PETUGAS
// =============================
Route::prefix('petugas')->middleware(['auth', 'petugas'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('petugas.dashboard');

    Route::resource('qrcodes', QRCodeController::class)
        ->names('petugas.qrcodes');

    Route::get('/scan', [ScanController::class, 'index'])
        ->name('petugas.qrcodes.scan');

    Route::post('/scan/validate', [ScanController::class, 'validateQr'])
        ->name('petugas.qrcodes.validate');

    Route::get('/validasi', [ValidasiController::class, 'index'])
        ->name('petugas.validasi');

    Route::post('/petugas/validate', [QRCodeController::class, 'validateQr'])
        ->name('petugas.validateQr');

    Route::get('/pengunjung', [PengunjungController::class, 'index'])
        ->name('petugas.pengunjung');

    Route::get('/riwayat', [ScanController::class, 'riwayat'])
        ->name('petugas.riwayat');

    Route::get('/profil', [PetugasProfileController::class, 'index'])
        ->name('petugas.profil');

});

// Route Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

