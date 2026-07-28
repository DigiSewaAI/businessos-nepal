<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Sales Routes
    Route::get('/sales/pos', [SaleController::class, 'pos'])->name('sales.pos');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');

});

require __DIR__.'/auth.php';