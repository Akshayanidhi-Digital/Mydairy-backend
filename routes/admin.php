
<?php

use App\Http\Controllers\v1\Admin\AppHelpController;
use App\Http\Controllers\v1\Admin\CategoryController;
use App\Http\Controllers\v1\Admin\DashboardController;
use App\Http\Controllers\v1\Admin\PaymentsController;
use App\Http\Controllers\v1\Admin\PlansController;
use App\Http\Controllers\v1\Admin\ProductsController;
use App\Http\Controllers\v1\Admin\UserController;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth', 'isadminUser'], 'as' => 'admin.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'plan', 'as' => 'plans.'], function () {
        Route::get('/', [PlansController::class, 'index'])->name('list');
        Route::get('create', [PlansController::class, 'create'])->name('create');
        Route::get('{id}/edit', [PlansController::class, 'edit'])->name('edit');
        Route::post('store', [PlansController::class, 'store'])->name('store');
        Route::post('{id}/update', [PlansController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'groups', 'as' => 'groups.'], function () {
        Route::get('/', [CategoryController::class, 'groups'])->name('list');
        Route::post('/add', [CategoryController::class, 'groupsAdd'])->name('add');
        Route::post('/update', [CategoryController::class, 'groupsUpdate'])->name('update');
        Route::post('/status', [CategoryController::class, 'groupstatus'])->name('status');
    });
    Route::group(['prefix' => 'brands', 'as' => 'brands.'], function () {
        Route::match(['get', 'post'], '/', [CategoryController::class, 'brands'])->name('list');
        Route::post('/add', [CategoryController::class, 'brandsAdd'])->name('add');
        Route::post('/update', [CategoryController::class, 'brandsUpdate'])->name('update');
        Route::post('/status', [CategoryController::class, 'brandsStatus'])->name('status');
    });

    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('/', [ProductsController::class, 'index'])->name('list');
        Route::match(['get', 'post'], '/add', [ProductsController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '{product_id}/edit', [ProductsController::class, 'edit'])->name('edit');
        Route::post('/delete', [ProductsController::class, 'delete'])->name('delete');
        Route::post('/status', [ProductsController::class, 'status'])->name('status');
    });

    Route::group(['prefix' => 'app-help', 'as' => 'apphelp.'], function () {
        Route::get('/', [AppHelpController::class, 'index'])->name('list');
        Route::get('create', [AppHelpController::class, 'create'])->name('create');
        Route::get('{id}/edit', [AppHelpController::class, 'edit'])->name('edit');
        Route::post('store', [AppHelpController::class, 'store'])->name('store');
        Route::post('status/update', [AppHelpController::class, 'status'])->name('status');
        Route::post('delete', [AppHelpController::class, 'delete'])->name('delete');
        Route::post('{id}/update', [AppHelpController::class, 'update'])->name('update');
    });
    Route::group(['prefix' => 'app-payments', 'as' => 'payments.'], function () {
        Route::get('/', [PaymentsController::class, 'index'])->name('list');
        Route::get('{pay_id}/', [PaymentsController::class, 'print'])->name('print');
    });
    Route::group(['prefix' => 'app-user', 'as' => 'user.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::post('/update-status', [UserController::class, 'status'])->name('status');
    });
});
// 'isadminUser' true for admin only
