<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('purchases')->name('purchases.')->group(function () {
    Route::get('/', function () {
        return view('admin.inventory.purchases.index');
    })->name('index');

    Route::get('/create', function () {
        return view('admin.inventory.purchases.create');
    })->name('create');
});

use App\Http\Controllers\PhieuXuatController;

Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [PhieuXuatController::class, 'index'])->name('index');
    Route::get('/create', [PhieuXuatController::class, 'create'])->name('create');
    Route::post('/', [PhieuXuatController::class, 'store'])->name('store');
    Route::get('/{id}', [PhieuXuatController::class, 'show'])->name('show');
    Route::post('/{id}/confirm', [PhieuXuatController::class, 'confirm'])->name('confirm');
    Route::post('/{id}/shipping', [PhieuXuatController::class, 'markAsShipping'])->name('shipping');
    Route::post('/{id}/complete', [PhieuXuatController::class, 'markAsCompleted'])->name('complete');
    Route::post('/{id}/revert', [PhieuXuatController::class, 'revertToPreparing'])->name('revert');
    Route::delete('/{id}', [PhieuXuatController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/print', [PhieuXuatController::class, 'print'])->name('print');
});

use App\Http\Controllers\TonKhoController;
use App\Http\Controllers\PhieuNhapController;

Route::prefix('batches')->name('batches.')->group(function () {
    Route::get('/', [TonKhoController::class, 'index'])->name('index');
    Route::put('/update-status', [TonKhoController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/adjust', [TonKhoController::class, 'adjustStock'])->name('adjust');
});

Route::prefix('imports')->name('imports.')->group(function () {
    Route::get('/', [PhieuNhapController::class, 'index'])->name('index');
    Route::get('/create', [PhieuNhapController::class, 'create'])->name('create');
    Route::post('/', [PhieuNhapController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PhieuNhapController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PhieuNhapController::class, 'update'])->name('update');
    Route::get('/{id}/inspect', [PhieuNhapController::class, 'show'])->name('inspect');
    Route::post('/{id}/inspect', [PhieuNhapController::class, 'saveDraft'])->name('saveDraft');
    Route::post('/{id}/arrived', [PhieuNhapController::class, 'markArrived'])->name('markArrived');
    Route::delete('/{id}', [PhieuNhapController::class, 'destroy'])->name('destroy');
});

use App\Http\Controllers\ThuocController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ThuocController::class, 'index'])->name('index');
    Route::post('/', [ThuocController::class, 'store'])->name('store');
    Route::put('/{id}', [ThuocController::class, 'update'])->name('update');
    Route::delete('/{id}', [ThuocController::class, 'destroy'])->name('destroy');

    // Quản lý Nhóm & Đơn vị
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::put('/units/{id}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{id}', [UnitController::class, 'destroy'])->name('units.destroy');
});

use App\Http\Controllers\NhaCungCapController;

Route::prefix('suppliers')->name('suppliers.')->group(function () {
    Route::get('/', [NhaCungCapController::class, 'index'])->name('index');
    Route::post('/', [NhaCungCapController::class, 'store'])->name('store');
    Route::put('/{id}', [NhaCungCapController::class, 'update'])->name('update');
    Route::delete('/{id}', [NhaCungCapController::class, 'destroy'])->name('destroy');
});

use App\Http\Controllers\KhachHangController;

Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [KhachHangController::class, 'index'])->name('index');
    Route::put('/{id}', [KhachHangController::class, 'update'])->name('update');
    Route::put('/{id}/status', [KhachHangController::class, 'updateStatus'])->name('updateStatus');
    Route::delete('/{id}', [KhachHangController::class, 'destroy'])->name('destroy');
});

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/stock', function () {
        return view('admin.inventory.reports.stock');
    })->name('stock');

    Route::get('/movements', function () {
        return view('admin.inventory.reports.movements');
    })->name('movements');

    Route::get('/debts', function () {
        return view('admin.inventory.reports.debts');
    })->name('debts');
});

Route::prefix('account')->name('account.')->group(function () {
    Route::get('/profile', function () {
        return view('admin.account.profile');
    })->name('profile');

    Route::get('/password', function () {
        return view('admin.account.password');
    })->name('password');
});

use App\Http\Controllers\ThanhToanController;

Route::prefix('payments')->name('payments.')->group(function () {
    Route::get('/', [ThanhToanController::class, 'index'])->name('index');
    Route::post('/', [ThanhToanController::class, 'store'])->name('store');
    Route::get('/history', [ThanhToanController::class, 'history'])->name('history');
    Route::get('/export/suppliers', [ThanhToanController::class, 'exportSuppliers'])->name('export.suppliers');
    Route::get('/export/customers', [ThanhToanController::class, 'exportCustomers'])->name('export.customers');
    Route::get('/{id}', [ThanhToanController::class, 'show'])->name('show');
});

use App\Http\Controllers\Auth\CustomerAuthController;

Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);

Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register']);

Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\WholesaleController;
use App\Http\Controllers\DonHangController;


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

    // Thông tin cá nhân
    Route::get('/profile', [KhachHangController::class, 'profile'])->name('profile');
    Route::put('/profile', [KhachHangController::class, 'updateProfile'])->name('profile.update');



    Route::get('/product/{id}', [WholesaleController::class, 'product'])->name('product');


});




Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
    Route::get('/', [DonHangController::class, 'index'])->name('index');
    Route::get('/export', [DonHangController::class, 'export'])->name('export');
    Route::get('/{id}', [DonHangController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [DonHangController::class, 'approve'])->name('approve');
    Route::post('/{id}/cancel', [DonHangController::class, 'cancel'])->name('cancel');
    Route::post('/{id}/export-note', [DonHangController::class, 'createExportNote'])->name('exportNote');
});

use App\Http\Controllers\ReportController;

Route::prefix('admin/reports')->name('reports.')->group(function () {
    Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
    Route::get('/stock/export', [ReportController::class, 'exportStock'])->name('stock.export');
    Route::get('/movements', [ReportController::class, 'movements'])->name('movements');
    Route::get('/movements/export', [ReportController::class, 'exportMovements'])->name('movements.export');
    Route::get('/debts', [ReportController::class, 'debts'])->name('debts');
    Route::get('/debts/export', [ReportController::class, 'exportDebts'])->name('debts.export');
});
