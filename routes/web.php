<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CashbookController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PageController; // <-- ADD THIS

// ========== PUBLIC / MARKETING PAGES ==========
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/features', [PageController::class, 'features'])->name('pages.features');
Route::get('/industries', [PageController::class, 'industries'])->name('pages.industries');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pages.pricing');
Route::get('/changelog', [PageController::class, 'changelog'])->name('pages.changelog');
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/careers', [PageController::class, 'careers'])->name('pages.careers');
Route::get('/help', [PageController::class, 'help'])->name('pages.help');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');

// ========== AUTH / APP ROUTES ==========
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Sales Routes
    Route::get('/sales/pos', [SaleController::class, 'pos'])->name('sales.pos');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    
    // Purchase Routes
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::post('/purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');
    
    // Expense Routes
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Cashbook Routes
    Route::get('/cashbook', [CashbookController::class, 'index'])->name('cashbook.index');
    Route::post('/cashbook/close', [CashbookController::class, 'close'])->name('cashbook.close');
    
    // Reports
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');

    // Plan Routes (Phase 6: SaaS)
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/upgrade', [PlanController::class, 'upgrade'])->name('plans.upgrade');
});

require __DIR__.'/auth.php';