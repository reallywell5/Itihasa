<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Http\Controllers\Admin\MuseumWebController;
use App\Http\Controllers\Admin\TicketWebController;
use App\Http\Controllers\Admin\TransactionWebController;
use App\Http\Controllers\Admin\DashboardWebController;
use App\Http\Controllers\Admin\UserWebController;
use App\Http\Controllers\Admin\PaymentWebController;
use App\Http\Controllers\Admin\ReviewWebController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Petugas\DashboardController;
use App\Http\Controllers\Petugas\QRCodeController;
use App\Http\Controllers\Petugas\ValidasiController;
use App\Http\Controllers\Petugas\PengunjungController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\SuccessController;
use App\Http\Controllers\Frontend\AboutController;

// =============================
// ROUTE FRONTEND - PUBLIC
// =============================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/booking', [BookingController::class, 'index'])->name('booking');
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
Route::get('/success', [SuccessController::class, 'index'])->name('success');
Route::get('/about', [AboutController::class, 'index'])->name('about');

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

    Route::resource('reviews', ReviewWebController::class)
        ->only(['index', 'show', 'destroy']);
});

// =============================
// ROUTE PETUGAS
// =============================
Route::prefix('petugas')->middleware(['auth', 'petugas'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('petugas.dashboard');

    Route::resource('qrcodes', QRCodeController::class)
        ->names('petugas.qrcodes');

    Route::get('/qrcodes-scan', [QRCodeController::class, 'scan'])
        ->name('petugas.qrcodes.scan');

    Route::post('/qrcodes-validate', [QRCodeController::class, 'validateQr'])
        ->name('petugas.qrcodes.validate');

    Route::get('/validasi', [ValidasiController::class, 'index'])
        ->name('petugas.validasi');

    Route::get('/pengunjung', [PengunjungController::class, 'index'])
        ->name('petugas.pengunjung');

    Route::get('/riwayat', function () {
        return view('petugas.riwayat');
    })->name('petugas.riwayat');

    Route::get('/profil', function () {
        return view('petugas.profil');
    })->name('petugas.profil');
});

// Route Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');