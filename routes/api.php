<?php

use App\Http\Controllers\Api\Wali\AuthController;
use App\Http\Controllers\Api\Wali\DashboardController;
use App\Http\Controllers\Api\Wali\KonfirmasiPembayaranController;
use App\Http\Controllers\Api\Wali\PembayaranController;
use App\Http\Controllers\Api\Wali\TagihanController;
use Illuminate\Support\Facades\Route;

Route::prefix('wali')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);

        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::get('tagihan', [TagihanController::class, 'index']);
        Route::get('tagihan/{tagihan}', [TagihanController::class, 'show']);

        Route::get('pembayaran', [PembayaranController::class, 'index']);

        Route::get('konfirmasi-pembayaran', [KonfirmasiPembayaranController::class, 'index']);
        Route::post('konfirmasi-pembayaran', [KonfirmasiPembayaranController::class, 'store']);
        Route::get('konfirmasi-pembayaran/{konfirmasiPembayaran}', [KonfirmasiPembayaranController::class, 'show']);
    });
});
