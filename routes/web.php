<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseReleaseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\WarehouseReceiptController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\InventoryTransferController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\WholesaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerReturnsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserController;

/*
| Web Routes

|
| Role IDs:
|   1 = Admin
|   2 = Nhân viên kho
|   3 = Nhân viên bán hàng
|   4 = Kế toán
|   5 = Trưởng kho
|
*/

// ADMIN AUTH ROUTES (Không yêu cầu đăng nhập)
Route::prefix('admin')->name('admin.auth.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// ADMIN ROUTES (Yêu cầu đăng nhập guard 'admin')
Route::middleware('auth:admin')->group(function () {

    // ── Dashboard (Tất cả roles) ──────────────────────────────
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // ── Tài khoản cá nhân (Tất cả roles) ─────────────────────
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/profile', function () {
            return view('admin.account.profile');
        })->name('profile');

        Route::get('/password', function () {
            return view('admin.account.password');
        })->name('password');
    });

    //  KHO HÀNG — Admin(1), NV Kho(2), Trưởng kho(5)
    Route::middleware('role:1,2,5')->group(function () {

        // Phiếu nhập kho
        Route::prefix('imports')->name('imports.')->group(function () {
            Route::get('/', [WarehouseReceiptController::class, 'index'])->name('index');
            Route::get('/export', [WarehouseReceiptController::class, 'export'])->name('export');
            Route::get('/create', [WarehouseReceiptController::class, 'create'])->name('create');
            Route::post('/', [WarehouseReceiptController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [WarehouseReceiptController::class, 'edit'])->name('edit');
            Route::put('/{id}', [WarehouseReceiptController::class, 'update'])->name('update');
            Route::get('/{id}/inspect', [WarehouseReceiptController::class, 'show'])->name('inspect');
            Route::post('/{id}/inspect', [WarehouseReceiptController::class, 'saveDraft'])->name('saveDraft');
            Route::post('/{id}/arrived', [WarehouseReceiptController::class, 'markArrived'])->name('markArrived');
            Route::delete('/{id}', [WarehouseReceiptController::class, 'destroy'])->name('destroy');
            Route::get('/advanced-search', [WarehouseReceiptController::class, 'advancedSearch'])->name('advancedSearch');
        });

        // Phiếu xuất kho
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [WarehouseReleaseController::class, 'index'])->name('index');
            Route::get('/export', [WarehouseReleaseController::class, 'export'])->name('export');
            Route::get('/create', [WarehouseReleaseController::class, 'create'])->name('create');
            Route::post('/', [WarehouseReleaseController::class, 'store'])->name('store');
            Route::get('/{id}', [WarehouseReleaseController::class, 'show'])->name('show');
            Route::post('/{id}/confirm', [WarehouseReleaseController::class, 'confirm'])->name('confirm');
            Route::post('/{id}/shipping', [WarehouseReleaseController::class, 'markAsShipping'])->name('shipping');
            Route::post('/{id}/complete', [WarehouseReleaseController::class, 'markAsCompleted'])->name('complete');
            Route::post('/{id}/undo-complete', [WarehouseReleaseController::class, 'undoCompleted'])->name('undoComplete');
            Route::post('/{id}/revert', [WarehouseReleaseController::class, 'revertToPreparing'])->name('revert');
            Route::delete('/{id}', [WarehouseReleaseController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/print', [WarehouseReleaseController::class, 'print'])->name('print');
        });

        // Điều chuyển kho
        Route::prefix('transfers')->name('transfers.')->group(function () {
            Route::get('/', [InventoryTransferController::class, 'index'])->name('index');
            Route::post('/', [InventoryTransferController::class, 'transfer'])->name('store');
            Route::get('/history', [InventoryTransferController::class, 'history'])->name('history');
            Route::get('/export', [InventoryTransferController::class, 'exportHistory'])->name('export');
        });
    }); 

    //  TỒN KHO — XEM: tất cả roles; SỬA: chỉ Admin(1), NV Kho(2), Trưởng kho(5)
    Route::prefix('batches')->name('batches.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
    });

    Route::middleware('role:1,2,5')->group(function () {
        Route::prefix('batches')->name('batches.')->group(function () {
            Route::put('/update-status', [InventoryController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/adjust', [InventoryController::class, 'adjustStock'])->name('adjust');
            Route::post('/isolate', [InventoryController::class, 'isolate'])->name('isolate');
        });
    });

    //  ĐƠN HÀNG — Admin(1), NV Kho(2), NV Bán hàng(3), Trưởng kho(5)
    Route::middleware('role:1,2,3,5')->group(function () {
        Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/export', [OrderController::class, 'export'])->name('export');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [OrderController::class, 'approve'])->name('approve');
            Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
            Route::post('/{id}/export-note', [OrderController::class, 'createExportNote'])->name('exportNote');
        });
    });

    //  TRẢ HÀNG — Admin(1), Trưởng kho(5), NV BH(3) full;
    //             NV Kho(2), Kế toán(4) xem

    Route::middleware('role:1,2,3,4,5')->group(function () {
        Route::prefix('admin/returns')->name('admin.returns.')->group(function () {
            Route::get('/', [CustomerReturnsController::class, 'index'])->name('index');
            Route::get('/{id}', [CustomerReturnsController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [CustomerReturnsController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [CustomerReturnsController::class, 'reject'])->name('reject');
            Route::post('/{id}/undo-approve', [CustomerReturnsController::class, 'undoApprove'])->name('undoApprove');
            Route::post('/{id}/refund', [CustomerReturnsController::class, 'processRefund'])->name('refund');
        });
    });

    //  DANH MỤC THUỐC — Admin(1), Trưởng kho(5) CRUD; NV Kho(2) xem
    Route::middleware('role:1,2,5')->group(function () {
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [MedicineController::class, 'index'])->name('index');
            Route::post('/', [MedicineController::class, 'store'])->name('store');
            Route::put('/{id}', [MedicineController::class, 'update'])->name('update');
            Route::delete('/{id}', [MedicineController::class, 'destroy'])->name('destroy');
            Route::post('/import', [MedicineController::class, 'import'])->name('import');

            // Quản lý Nhóm
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

            // Quản lý Đơn vị
            Route::post('/units', [UnitController::class, 'store'])->name('units.store');
            Route::put('/units/{id}', [UnitController::class, 'update'])->name('units.update');
            Route::delete('/units/{id}', [UnitController::class, 'destroy'])->name('units.destroy');
        });
    });

    //  NHÀ CUNG CẤP — Admin(1), Trưởng kho(5) CRUD; Kế toán(4) xem
    Route::middleware('role:1,4,5')->group(function () {
        Route::prefix('suppliers')->name('suppliers.')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('index');
            Route::post('/', [SupplierController::class, 'store'])->name('store');
            Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
            Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
        });
    });

    //  KHÁCH HÀNG — Admin(1), NV BH(3) full; Trưởng kho(5), Kế toán(4) xem

    Route::middleware('role:1,3,4,5')->group(function () {
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
            Route::put('/{id}/status', [CustomerController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
        });
    });

    // ═══════════════════════════════════════════════════════════
    //  THANH TOÁN — Admin(1), Kế toán(4)
    // ═══════════════════════════════════════════════════════════

    Route::middleware('role:1,4')->group(function () {
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentsController::class, 'index'])->name('index');
            Route::post('/', [PaymentsController::class, 'store'])->name('store');
            Route::get('/history', [PaymentsController::class, 'history'])->name('history');
            Route::get('/history/export', [PaymentsController::class, 'exportHistory'])->name('history.export');
            Route::get('/export/suppliers', [PaymentsController::class, 'exportSuppliers'])->name('export.suppliers');
            Route::get('/export/customers', [PaymentsController::class, 'exportCustomers'])->name('export.customers');
            Route::get('/export/returns', [PaymentsController::class, 'exportReturnRefunds'])->name('export.returns');
            Route::get('/{id}', [PaymentsController::class, 'show'])->name('show');
        });
    });

    // ═══════════════════════════════════════════════════════════
    //  BÁO CÁO — Khác nhau theo loại
    //  BC Tồn kho + Biến động: Admin(1), Trưởng kho(5), NV Kho(2), Kế toán(4) xem
    //  BC Công nợ: Admin(1), NV BH(3), Kế toán(4)
    // ═══════════════════════════════════════════════════════════

    Route::prefix('admin/reports')->name('reports.')->group(function () {
        // BC Tồn kho — Admin(1), Trưởng kho(5), NV Kho(2), Kế toán(4)
        Route::middleware('role:1,2,4,5')->group(function () {
            Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
            Route::get('/stock/export', [ReportController::class, 'exportStock'])->name('stock.export');
        });

        // BC Biến động / Lịch sử XNK — Admin(1), Trưởng kho(5), NV Kho(2)
        Route::middleware('role:1,2,5')->group(function () {
            Route::get('/movements', [ReportController::class, 'movements'])->name('movements');
            Route::get('/movements/export', [ReportController::class, 'exportMovements'])->name('movements.export');
        });

        // BC Công nợ — Admin(1), NV BH(3), Kế toán(4)
        Route::middleware('role:1,3,4')->group(function () {
            Route::get('/debts', [ReportController::class, 'debts'])->name('debts');
            Route::get('/debts/export', [ReportController::class, 'exportDebts'])->name('debts.export');
        });
    });

    // ═══════════════════════════════════════════════════════════
    //  QUẢN LÝ NGƯỜI DÙNG — Chỉ Admin(1)
    // ═══════════════════════════════════════════════════════════

    Route::middleware('role:1')->group(function () {
        Route::prefix('admin/users')->name('admin.users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
        });
    });
});

// ╔══════════════════════════════════════════════════════════════╗
// ║  CUSTOMER AUTH ROUTES                                       ║
// ╚══════════════════════════════════════════════════════════════╝

Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register']);
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

// ╔══════════════════════════════════════════════════════════════╗
// ║  WHOLESALE ROUTES (Guard: customer)                         ║
// ╚══════════════════════════════════════════════════════════════╝

Route::prefix('wholesale')->name('wholesale.')->middleware('auth:customer')->group(function () {
    Route::get('/catalog', [WholesaleController::class, 'catalog'])->name('catalog');
    Route::post('/cart/add', [WholesaleController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [WholesaleController::class, 'cart'])->name('cart');
    Route::post('/cart/update', [WholesaleController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [WholesaleController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/order', [WholesaleController::class, 'placeOrder'])->name('order.place');
    Route::get('/orders', [WholesaleController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{id}', [WholesaleController::class, 'orderDetail'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [WholesaleController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/{id}/edit', [WholesaleController::class, 'editOrder'])->name('orders.edit');
    Route::post('/orders/{id}/complete', [WholesaleController::class, 'completeOrder'])->name('orders.complete');
    Route::post('/orders/{id}/return', [WholesaleController::class, 'submitReturn'])->name('orders.return');

    // Thông tin cá nhân
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/product/{id}', [WholesaleController::class, 'product'])->name('product');
});