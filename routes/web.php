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
use App\Http\Controllers\Admin\QRCodeController;
use App\Http\Controllers\Admin\PetugasController; // Pastikan ini di-import

// Rute Uji Coba
Route::get('/test-qr', function () {
    return QrCode::size(200)->generate('Halo Aurel');
});

// --- GRUP ROUTE ADMIN (KITA MATIKAN DULU AUTH-NYA) ---
Route::prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardWebController::class, 'index'])
        ->name('admin.dashboard');

    // Resource Petugas
    Route::resource('petugas', PetugasController::class)->names([
        'index'   => 'admin.petugas.index',
        'create'  => 'admin.petugas.create',
        'store'   => 'admin.petugas.store',
        'edit'    => 'admin.petugas.edit',
        'update'  => 'admin.petugas.update',
        'destroy' => 'admin.petugas.destroy',
    ]);

    // Master Data Resources
    Route::resource('museums', MuseumWebController::class);
    Route::resource('tickets', TicketWebController::class);
    Route::resource('users', UserWebController::class);
    Route::resource('payments', PaymentWebController::class);

    // Transaksi
    Route::get('/transactions', [TransactionWebController::class, 'index'])
        ->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionWebController::class, 'show'])
        ->name('transactions.show');

    // Ulasan / Reviews
    Route::resource('reviews', ReviewWebController::class)
        ->only(['index', 'show', 'destroy']);

    // QR Codes & Scanner
    Route::resource('qrcodes', QRCodeController::class);
    Route::get('/qrcodes-scan', [QRCodeController::class, 'scan'])
        ->name('qrcodes.scan');
    Route::post('/qrcodes-validate', [QRCodeController::class, 'validateQr'])
        ->name('qrcodes.validate');
});
