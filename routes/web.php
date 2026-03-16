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
        return view('inventory.purchases.index');
    })->name('index');

    Route::get('/create', function () {
        return view('inventory.purchases.create');
    })->name('create');
});

Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('/', function () {
        return view('inventory.sales.index');
    })->name('index');

    Route::get('/create', function () {
        return view('inventory.sales.create');
    })->name('create');
});

use App\Http\Controllers\TonKhoController;
use App\Http\Controllers\PhieuNhapController;

Route::prefix('batches')->name('batches.')->group(function () {
    Route::get('/', [TonKhoController::class, 'index'])->name('index');
    Route::put('/update-status', [TonKhoController::class, 'updateStatus'])->name('updateStatus');
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

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ThuocController::class, 'index'])->name('index');
    Route::post('/', [ThuocController::class, 'store'])->name('store');
    Route::put('/{id}', [ThuocController::class, 'update'])->name('update');
    Route::delete('/{id}', [ThuocController::class, 'destroy'])->name('destroy');
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
        return view('reports.stock');
    })->name('stock');

    Route::get('/expiry', function () {
        return view('reports.expiry');
    })->name('expiry');

    Route::get('/movements', function () {
        return view('reports.movements');
    })->name('movements');

    Route::get('/debts', function () {
        return view('reports.debts');
    })->name('debts');
});

Route::prefix('account')->name('account.')->group(function () {
    Route::get('/profile', function () {
        return view('account.profile');
    })->name('profile');

    Route::get('/password', function () {
        return view('account.password');
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

Route::prefix('wholesale')->name('wholesale.')->middleware('auth:customer')->group(function () {
    Route::get('/catalog', function () {
        return view('wholesale.catalog');
    })->name('catalog');

    Route::get('/product/{id?}', function ($id = null) {
        return view('wholesale.product', compact('id'));
    })->name('product');

    Route::get('/cart', function () {
        return view('wholesale.cart');
    })->name('cart');

    Route::get('/orders', function () {
        return view('wholesale.orders');
    })->name('orders.index');
});


// });
