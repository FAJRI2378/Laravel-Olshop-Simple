<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SppPaymentController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\SppPayment;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Autentikasi Laravel
Auth::routes();

// Rute untuk User (hanya pengguna yang sudah login)
Route::middleware(['auth'])->group(function () {
    // Halaman utama untuk user
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Rute untuk melihat produk oleh user
    Route::resource('produk', ProdukController::class);

    // Rute untuk transaksi pembayaran user
    Route::get('/payments/{payment}/edit', [SppPaymentController::class, 'edit'])->name('payments.edit');
    Route::get('/user/spp', [SppPaymentController::class, 'userIndex'])->name('user.spp.index');
    Route::put('/payments/{id}', [SppPaymentController::class, 'update'])->name('payments.update');

    // Kirim pembayaran oleh user
    Route::post('/spp/store-user', [SppPaymentController::class, 'storeByUser'])->name('payments.store');
    Route::post('/payments/{id}/upload', [SppPaymentController::class, 'upload'])->name('payments.upload');

    // Halaman untuk User melihat status transaksi mereka
    Route::get('/user/transactions', [SppPaymentController::class, 'userTransactions'])->name('user.transactions');
});

// Rute untuk Admin (admin yang sudah login)
Route::middleware(['auth'])->group(function () {
    // Halaman utama untuk admin
    Route::get('/admin/home', [AdminController::class, 'index'])->name('admin.index');

    // Kelola pembayaran SPP oleh admin
    Route::resource('admin/spp', SppPaymentController::class)->except(['show']);

    // Update status pembayaran SPP
    Route::patch('/admin/spp/update-status/{id}', [SppPaymentController::class, 'updateStatus'])->name('admin.spp.update-status');

    // Konfirmasi pembayaran
    Route::post('/payments/{id}/confirm', [SppPaymentController::class, 'confirm'])->name('payments.confirm');

    // Cek pembayaran
    Route::post('/payments/check/{id}', [SppPaymentController::class, 'check'])->name('payments.check');

    // Halaman untuk Admin melihat transaksi yang perlu di-approve
    Route::get('/admin/transactions', [SppPaymentController::class, 'approveIndex'])->name('admin.transactions.approve');
    Route::post('/admin/transactions/approve/{id}', [SppPaymentController::class, 'approveTransaction'])->name('admin.transactions.approve');
});

// Logout
Route::post('/logout', [LogoutController::class, 'signout'])->name('logout');
