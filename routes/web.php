<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CashbookController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\Restaurant\TableController;
use App\Http\Controllers\Restaurant\OrderController;
use App\Http\Controllers\Restaurant\KOTController;
use App\Http\Controllers\Restaurant\QRController;
use App\Http\Controllers\School\StudentController;
use App\Http\Controllers\School\FeeController;
use App\Http\Controllers\School\AttendanceController;
use App\Http\Controllers\School\ExamController;
use App\Http\Controllers\AI\AIController;
use App\Http\Controllers\AI\ForecastController;
use App\Http\Controllers\AI\AnomalyController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\School\TeacherController;

// ========== PUBLIC / MARKETING PAGES ==========
Route::get('/', function () {
    return view('home');
})->name('home');

// ========== AI WARMUP (Model Pre-load) ==========
Route::get('/ai/warmup', function () {
    $ollama = app(\App\Services\AI\OllamaService::class);
    $response = $ollama->generate('Hello, warmup');
    return response()->json(['status' => 'Model loaded', 'time' => now()]);
});

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

// ============================================================
// 👇 PUBLIC AI ROUTES (No auth required - for demo)
// ============================================================
Route::get('/ai/chat', [AIController::class, 'chat'])->name('ai.chat');
Route::post('/ai/message', [AIController::class, 'sendMessage'])
    ->name('ai.message')
    ->middleware(['throttle:20,1']); // 20 requests per minute (public)

// ============================================================
// 👇 PUBLIC PRODUCT SEARCH (No auth required - demo)
// ============================================================
Route::get('/products/search', [App\Http\Controllers\ProductController::class, 'search'])->name('products.search');

// ========== AUTH / APP ROUTES ==========
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========== ONBOARDING ROUTES ==========
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // ========== ORGANIZATION & BRANCH ROUTES (Requires onboarding) ==========
    Route::middleware(['check.onboarding'])->group(function () {
        Route::get('/organization/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
        Route::put('/organization', [OrganizationController::class, 'update'])->name('organization.update');
        Route::resource('branches', BranchController::class)->except(['show']);
    });

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
    
    // ========== PRODUCT ROUTES (Full CRUD) ==========
    Route::resource('products', App\Http\Controllers\ProductController::class);
    // Plan Routes (Phase 6: SaaS)
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/upgrade', [PlanController::class, 'upgrade'])->name('plans.upgrade');

    // Accounts
    Route::resource('accounts', AccountController::class)->except(['show']);
    Route::get('accounts/{account}', [AccountController::class, 'show'])->name('accounts.show');

    // Journal Entries
    Route::resource('journal-entries', JournalEntryController::class);

    // Financial Reports
    Route::get('reports/trial-balance', [FinancialReportController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('reports/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('reports.income-statement');
    Route::get('reports/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('reports.balance-sheet');

    // Restaurant Routes
    Route::prefix('restaurant')->name('restaurant.')->group(function () {
        // Tables
        Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
        Route::get('/tables/layout', [TableController::class, 'layout'])->name('tables.layout');
        Route::get('/tables/create', [TableController::class, 'create'])->name('tables.create');
        Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
        Route::get('/tables/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
        Route::put('/tables/{table}', [TableController::class, 'update'])->name('tables.update');
        Route::post('/tables/{table}/toggle/{status}', [TableController::class, 'toggleStatus'])->name('tables.toggle');
        Route::post('/tables/{table}/qr', [TableController::class, 'generateQR'])->name('tables.qr');

        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/active', [OrderController::class, 'active'])->name('orders.active');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::post('/orders/{order}/convert', [OrderController::class, 'convertToSale'])->name('orders.convert');

        // KOT
        Route::get('/kot', [KOTController::class, 'index'])->name('kot.index');
        Route::get('/kot/pending', [KOTController::class, 'pending'])->name('kot.pending');
        Route::post('/kot/{kot}/print', [KOTController::class, 'markPrinted'])->name('kot.print');
        Route::get('/kitchen', [OrderController::class, 'kitchen'])->name('kitchen');
    });

    // Public QR (No auth - but inside auth group? Actually it should be public. Moving outside.)
    // Note: QR routes should be public, but they are inside auth group? Let's keep as original.

    // School Routes
    Route::prefix('school')->name('school.')->group(function () {
        // Students
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);

        // Fees
        Route::get('fees/invoices/{student}', [FeeController::class, 'generate'])->name('fees.generate');
        Route::post('fees/pay/{invoice}', [FeeController::class, 'pay'])->name('fees.pay');
        Route::get('fees/summary/{student}', [FeeController::class, 'summary'])->name('fees.summary');

        // Attendance
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

        // Exams
        Route::resource('exams', ExamController::class);
        Route::post('exams/{exam}/results', [ExamController::class, 'saveResults'])->name('exams.results');
        Route::get('exams/{exam}/results', [ExamController::class, 'viewResults'])->name('exams.results.view');
    });

    // ========== AI ROUTES (auth required) ==========
Route::prefix('ai')->name('ai.')->group(function () {
    // Note: chat and message are PUBLIC (defined above)
    // These are protected routes that need login
    Route::get('/conversation/{id}', [AIController::class, 'conversation'])->name('conversation');
    Route::delete('/conversation/{id}', [AIController::class, 'deleteConversation'])->name('conversation.delete');
    
    // ✅ Corrected: name is 'dashboard' (prefix 'ai.' makes it 'ai.dashboard')
    Route::get('/dashboard', [AIController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast');
    Route::post('/forecast/generate', [ForecastController::class, 'generate'])->name('forecast.generate');
    Route::get('/anomalies', [AnomalyController::class, 'index'])->name('anomalies');
    Route::post('/anomalies/check', [AnomalyController::class, 'check'])->name('anomalies.check');
    Route::post('/anomalies/{id}/read', [AnomalyController::class, 'markRead'])->name('anomalies.read');
    Route::get('/export', [AIController::class, 'exportConversations'])->name('export');
});
});

// ========== PUBLIC QR ROUTES (No auth) ==========
// Note: Moving these outside auth group to make them truly public
Route::get('/restaurant/menu/{table}', [QRController::class, 'menu'])->name('restaurant.menu');
Route::post('/restaurant/order/{table}', [QRController::class, 'placeOrder'])->name('restaurant.place-order');

// ========== API ROUTES FOR DYNAMIC DROPDOWNS (auth, but outside verified) ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/api/sections', function (Illuminate\Http\Request $request) {
        $classId = $request->class_id;
        if (!$classId) {
            return response()->json([]);
        }

        $sections = App\Models\School\Section::where('school_class_id', $classId)
            ->where('is_active', true)
            ->select('id', 'name')
            ->get();

        return response()->json($sections);
    });
});

require __DIR__.'/auth.php';