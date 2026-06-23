<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FuelProductController;
use App\Http\Controllers\TankController;
use App\Http\Controllers\PumpController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CreditCustomerController;
use App\Http\Controllers\CreditRepaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SettingController;
use App\Models\StockAlert;

Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - redirects based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management - Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Fuel Products - Admin & Manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('fuel-products', FuelProductController::class);
    });

    // Tanks - Admin for CRUD, Manager/Supervisor for View
    Route::resource('tanks', TankController::class)->only(['index', 'show']);
    Route::middleware('role:admin')->group(function () {
        Route::resource('tanks', TankController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    // Pumps - Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::resource('pumps', PumpController::class);
    });

    // Shifts - Supervisor & Admin Only
    Route::middleware('role:supervisor,admin')->group(function () {
        Route::resource('shifts', ShiftController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');
        Route::put('shifts/{shift}/close', [ShiftController::class, 'closeShift'])->name('shifts.closeShift');
    });

    // Sales - Attendant for create, All for view
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);

    // Inventory - Manager, Supervisor, Admin
    Route::middleware('role:manager,supervisor,admin')->group(function () {
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('inventory/alerts', [InventoryController::class, 'alerts'])->name('inventory.alerts');
    });

    // Reconciliations - Supervisor & Manager & Admin
    Route::middleware('role:supervisor,manager,admin')->group(function () {
        Route::get('reconciliations', [ReconciliationController::class, 'index'])->name('reconciliations.index');
        Route::get('reconciliations/create/{shift}', [ReconciliationController::class, 'create'])->name('reconciliations.create');
        Route::post('reconciliations', [ReconciliationController::class, 'store'])->name('reconciliations.store');
        Route::get('reconciliations/{reconciliation}', [ReconciliationController::class, 'show'])->name('reconciliations.show');
        Route::middleware('role:manager,admin')->group(function () {
            Route::get('reconciliations/{reconciliation}/review', [ReconciliationController::class, 'review'])->name('reconciliations.review');
            Route::put('reconciliations/{reconciliation}/review', [ReconciliationController::class, 'submitReview'])->name('reconciliations.submitReview');
        });
    });

    // Deliveries - Supervisor & Admin
    Route::middleware('role:supervisor,admin')->group(function () {
        Route::resource('deliveries', DeliveryController::class)->only(['index', 'create', 'store', 'show']);
    });

    // Credit Customers - Manager & Admin for management, Supervisor & Admin for repayments
    Route::middleware('role:manager,admin')->group(function () {
        Route::resource('credit-customers', CreditCustomerController::class)->except(['destroy']);
    });
    
    Route::middleware('role:supervisor,admin')->group(function () {
        Route::get('credit-customers/{creditCustomer}/repayment', [CreditRepaymentController::class, 'create'])->name('credit-customers.repayment');
        Route::post('credit-customers/{creditCustomer}/repayment', [CreditRepaymentController::class, 'store']);
    });

    // AJAX API routes
    Route::get('api/pumps/{pump}/info', [SaleController::class, 'getPumpInfo'])->name('api.pumps.info');
    Route::get('api/tanks/{tank}/info', [DeliveryController::class, 'getTankInfo'])->name('api.tanks.info');

    // Stock Alerts
    Route::middleware('role:supervisor,admin')->group(function () {
        Route::get('stock-alerts', function () {
            $alerts = StockAlert::where('is_resolved', false)
                ->with('tank')
                ->latest()
                ->paginate(50);
            return view('stock-alerts.index', compact('alerts'));
        })->name('stock-alerts.index');
    });

    // Reports - Manager and Admin
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
        Route::get('reports/reconciliation', [ReportController::class, 'reconciliationReport'])->name('reports.reconciliation');
        Route::get('reports/deliveries', [ReportController::class, 'deliveryReport'])->name('reports.deliveries');
        Route::get('reports/credit', [ReportController::class, 'creditReport'])->name('reports.credit');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    // Audit Logs - Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('audit-logs/{log}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    });

    // Settings - Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';

