<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

// akun
Route::middleware(['verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/store', [ProfileController::class, 'store'])->name('profile.store');
    Route::post('/profile/change-password', [ProfileController::class, 'changePasswordStore'])->name('profile.change-password');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::post('user/{user}/change-password/', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::resource('user', UserController::class);
});
// akun

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('product', ProductController::class);
    Route::get('/transcation/history', [TransactionController::class, 'history']);
    Route::get('/transcation/edit/{id}', [TransactionController::class, 'edit'])->name('edit');
    Route::post('/transcation/update-tanggal/', [TransactionController::class, 'updateTanggal'])->name('update.tanggal');
});

Route::middleware(['auth', 'role:superadmin|kasir'])->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::get('/transcation', [TransactionController::class, 'index'])->name('pos');
    Route::post('/transcation/addproduct/{id}', [TransactionController::class, 'addProductCart']);
    Route::post('/transcation/removeproduct/{id}', [TransactionController::class, 'removeProductCart']);
    Route::post('/transcation/clear', [TransactionController::class, 'clear']);
    Route::post('/transcation/updateCart/{id}', [TransactionController::class, 'updateCart']);
    Route::post('/transcation/bayar', [TransactionController::class, 'bayar']);
    Route::get('/transcation/laporan/{id}', [TransactionController::class, 'laporan'])->name('laporan');
    Route::get('/transcation/cetak/laporan/{id}', [TransactionController::class, 'cetakLaporan'])->name('cetak.laporan');
});
