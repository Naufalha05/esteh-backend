<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:owner,supervisor')->group(function () {
        Route::apiResource('outlets', \App\Http\Controllers\OwnerSupervisor\OutletController::class);
        Route::apiResource('users', \App\Http\Controllers\OwnerSupervisor\UserController::class);
        Route::get('laporan/pendapatan', [\App\Http\Controllers\OwnerSupervisor\LaporanController::class, 'pendapatan']);
        Route::get('laporan/export', [\App\Http\Controllers\OwnerSupervisor\LaporanController::class, 'exportCsv']);
    });

    Route::middleware('role:gudang')->group(function () {
        Route::apiResource('bahan', \App\Http\Controllers\Gudang\BahanController::class);
        Route::apiResource('barang-masuk', \App\Http\Controllers\Gudang\BarangMasukController::class);
        Route::apiResource('barang-keluar', \App\Http\Controllers\Gudang\BarangKeluarController::class);
    });

    Route::middleware('role:karyawan')->group(function () {
        Route::apiResource('produk', \App\Http\Controllers\Karyawan\ProdukController::class);
        Route::post('transaksi', [\App\Http\Controllers\Karyawan\TransaksiController::class, 'store']);
        Route::get('transaksi', [\App\Http\Controllers\Karyawan\TransaksiController::class, 'index']);
    });
});