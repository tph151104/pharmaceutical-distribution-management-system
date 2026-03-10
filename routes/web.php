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

Route::prefix('batches')->name('batches.')->group(function () {
    Route::get('/', function () {
        return view('inventory.batches.index');
    })->name('index');
});

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', function () {
        return view('inventory.products.index');
    })->name('index');
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
