<?php

use Illuminate\Support\Facades\Route;
use Modules\Pendaftaran\Http\Controllers\PendaftaranController;

Route::middleware('auth')->prefix('pendaftarans')->name('pendaftaran.')->group(function () {
    Route::get('/', [PendaftaranController::class, 'index'])->name('index');
    Route::post('/', [PendaftaranController::class, 'store'])->name('store');
    Route::patch('/{registration}', [PendaftaranController::class, 'update'])->name('update');
    Route::delete('/{registration}', [PendaftaranController::class, 'destroy'])->name('destroy');
});
