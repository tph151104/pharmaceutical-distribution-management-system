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

Route::prefix('suppliers')->name('suppliers.')->group(function () {
    Route::get('/', function () {
        return view('inventory.suppliers.index');
    })->name('index');
});

Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', function () {
        return view('inventory.customers.index');
    })->name('index');
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

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::prefix('wholesale')->name('wholesale.')->group(function () {
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
