<?php

use App\Http\Controllers\CustomerController;
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
    Route::post('user/{user}/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::post('user/{user}/{status}/banned', [UserController::class, 'banned'])->name('user.banned');
    Route::resource('user', UserController::class);
});
// akun

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('product', ProductController::class);
    Route::get('/transcation/history', [TransactionController::class, 'history']);
    Route::get('/transcation/edit/{id}', [TransactionController::class, 'edit'])->name('edit');
    Route::post('/transcation/update-tanggal/', [TransactionController::class, 'updateTanggal'])->name('update.tanggal');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::prefix('cart')->as('cart.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::post('add/{id}', [TransactionController::class, 'add'])->name('add');
        Route::post('remove/{id}', [TransactionController::class, 'remove'])->name('remove');
        Route::post('increase/{id}', [TransactionController::class, 'increase'])->name('increase');
        Route::post('decrease/{id}', [TransactionController::class, 'decrease'])->name('decrease');
        Route::post('clear', [TransactionController::class, 'clear'])->name('clear');
        Route::post('update/{id}', [TransactionController::class, 'update'])->name('update');
        Route::post('bayar', [TransactionController::class, 'bayar'])->name('bayar');
    });

    Route::get('/transcation/laporan/{id}', [TransactionController::class, 'laporan'])->name('laporan');
    Route::get('/transcation/cetak/laporan/{id}', [TransactionController::class, 'cetakLaporan'])->name('cetak.laporan');
});
