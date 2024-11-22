<?php

use App\Http\Controllers\SendNotificationTest;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestDisTance;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\BuyerController;
use App\Http\Controllers\v1\CustomerController;
use App\Http\Controllers\v1\DashboardController;
use App\Http\Controllers\v1\FarmerController;
use App\Http\Controllers\v1\HomeController;
use App\Http\Controllers\v1\MasterController;
use App\Http\Controllers\v1\MilkBuyController;
use App\Http\Controllers\v1\MilkSaleController;
use App\Http\Controllers\v1\PlansController;
use App\Http\Controllers\v1\ProductsController;
use App\Http\Controllers\v1\RateChartController;
use App\Http\Controllers\v1\RecordsController;
use App\Http\Controllers\v1\RoutesController;
use App\Http\Controllers\v1\ShoppingController;
use App\Http\Controllers\v1\TranspoterController;
use App\Http\Controllers\v1\UserProfileController;
use Illuminate\Support\Facades\Route;




Route::get('/test-dis', [TestDisTance::class, 'calculateRouteDistance']);
Route::get('/test-data', [TestController::class, 'getCountryCity']);
// Route::get('/test-audio', [TestController::class, 'index']);
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/google-callback', [HomeController::class, 'googleLoginCallback'])->name('google.callback');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/offline', function () {
    return view('vendor.laravelpwa.offline');
});

Route::group(['prefix' => 'dairy'], function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('verify', [AuthController::class, 'verify'])->name('verify');
    Route::post('verify/otp', [AuthController::class, 'verifyOtp'])->name('verify.post');
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'registerPost'])->name('register.post');
    Route::post('login', [AuthController::class, 'loginPost'])->name('login.post');
    Route::get('get-qr', [AuthController::class, 'qrCode'])->name('qrCode');
    Route::post('qr-check', [AuthController::class, 'qrCheck'])->name('qrlogin');
    Route::match(['get', 'post'], 'onboard', [AuthController::class, 'onboard'])->name('user.onboard');
    Route::group(['middleware' => ['isBlocked', 'auth', 'Isprofile'], 'as' => 'user.'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('/', [UserProfileController::class, 'index'])->name('index');
            Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');
            Route::get('/upgrade', [UserProfileController::class, 'upgrade'])->name('upgrade');
            Route::post('/update', [UserProfileController::class, 'update'])->name('update');
            Route::post('/password-update', [UserProfileController::class, 'updatePassword'])->name('password.update');
        });
        Route::prefix('notification')->as('notification.')->group(function () {
            Route::get('/', [DashboardController::class, 'notification'])->name('list');
            // Route::post('/accept',[DashboardController::class,'notificationAccept'])->name('delete');
            Route::post('/milk-data', [DashboardController::class, 'notificationMilkData'])->name('milkdata');
            Route::post('/milk-action', [DashboardController::class, 'notificationMilkAction'])->name('milkAction');
            Route::post('/delete', [DashboardController::class, 'notificationDelete'])->name('delete');
        });

        Route::post('/lang', [UserProfileController::class, 'lang'])->name('lang');
        Route::get('/settings', [UserProfileController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [UserProfileController::class, 'settingsUpdate'])->name('settings.update');
        Route::group(['middleware' => 'IsvalidPlan'], function () {
            // Route::group(['middleware' => 'isSubuserPermission'], function () {
            // milk buy
            Route::group(['prefix' => 'milk-buy', 'as' => 'Milkbuy.'], function () {
                Route::get('/', [MilkBuyController::class, 'index'])->name('index');
                Route::post('/supplier-list', [MilkBuyController::class, 'supplierList'])->name('supplierlist.info');
                Route::post('/supplier-info', [MilkBuyController::class, 'supplierInfo'])->name('supplier.info');
                Route::post('/farmer-info', [MilkBuyController::class, 'farmerGetinfo'])->name('farmer.info');
                Route::post('/calculateAmount', [MilkBuyController::class, 'calculateAmount'])->name('amount.calculate');
                Route::post('/store', [MilkBuyController::class, 'store'])->name('store');
                Route::get('{id}/print', [MilkBuyController::class, 'print'])->name('print');
                Route::get('{date}/{shift}/print-all', [MilkBuyController::class, 'printAll'])->name('print.all');
                Route::post('/destroy', [MilkBuyController::class, 'destroy'])->name('destroy');
                Route::post('/delete', [MilkBuyController::class, 'delete'])->name('delete');
                Route::post('/restore', [MilkBuyController::class, 'restore'])->name('restore');
            });
            // milk sell
            Route::group(['prefix' => 'milk-sell', 'as' => 'MilkSell.'], function () {
                Route::get('/', [MilkSaleController::class, 'index'])->name('index');
                Route::post('/buyer-list', [MilkSaleController::class, 'buyerList'])->name('buyerList.info');
                Route::post('/buyer-info', [MilkSaleController::class, 'buyerGetinfo'])->name('buyer.info');
                Route::post('/calculateAmount', [MilkSaleController::class, 'calculateAmount'])->name('amount.calculate');
                Route::post('/store', [MilkSaleController::class, 'store'])->name('store');
                Route::get('{id}/print', [MilkSaleController::class, 'print'])->name('print');
                Route::get('{date}/print-all', [MilkSaleController::class, 'printAll'])->name('print.all');
                Route::post('/destroy', [MilkSaleController::class, 'destroy'])->name('destroy');
                Route::post('/delete', [MilkSaleController::class, 'delete'])->name('delete');
                Route::post('/restore', [MilkSaleController::class, 'restore'])->name('restore');
            });
            // Customer Management
            // Route::group(['prefix' => 'customers', 'as' => 'Costumers.'], function () {
            //     Route::get('/', [CustomerController::class, 'index'])->name('list');
            // });
            // farmers manage
            Route::group(['prefix' => 'farmers', 'as' => 'farmers.'], function () {
                Route::get('/', [FarmerController::class, 'index'])->name('list');
                Route::get('/add', [FarmerController::class, 'add'])->name('create');
                Route::get('{farmer_id}/edit', [FarmerController::class, 'edit'])->name('edit');
                Route::get('{farmer_id}/view', [FarmerController::class, 'view'])->name('view');
                Route::get('{farmer_id}/print/{start_date}/{end_date}', [FarmerController::class, 'printRecords'])->name('print');
                Route::post('/store', [FarmerController::class, 'store'])->name('store');
                Route::post('{farmer_id}/update', [FarmerController::class, 'update'])->name('update');
                Route::post('/delete', [FarmerController::class, 'delete'])->name('delete');
                Route::post('/restore', [FarmerController::class, 'restore'])->name('restore');
                Route::get('/delete-all', [FarmerController::class, 'deleteAll'])->name('deleteAll');
            });

            Route::group(['prefix' => 'buyers', 'as' => 'buyers.'], function () {
                Route::get('/', [BuyerController::class, 'index'])->name('list');
                Route::get('/add', [BuyerController::class, 'add'])->name('create');
                Route::get('{buyer_id}/edit', [BuyerController::class, 'edit'])->name('edit');
                Route::get('{buyer_id}/view', [BuyerController::class, 'view'])->name('view');
                Route::get('{buyer_id}/print/{start_date}/{end_date}', [BuyerController::class, 'printRecords'])->name('print');
                Route::post('/store', [BuyerController::class, 'store'])->name('store');
                Route::post('{buyer_id}/update', [BuyerController::class, 'update'])->name('update');
                Route::post('/delete', [BuyerController::class, 'delete'])->name('delete');
                Route::post('/restore', [BuyerController::class, 'restore'])->name('restore');
                Route::get('/delete-all', [BuyerController::class, 'deleteAll'])->name('deleteAll');
            });

            Route::group(['prefix' => 'sub-dairy', 'as' => 'childUser.'], function () {
                Route::get('/{role_type}', [MasterController::class, 'childUser'])->name('list');
                Route::get('{role_type}/add', [MasterController::class, 'childUserAdd'])->name('add');
                Route::get('{dairy_id}/edit', [MasterController::class, 'childUserEdit'])->name('edit');
                Route::get('{dairy_id}/view', [MasterController::class, 'childUserView'])->name('view');
                Route::post('{role_type}/store', [MasterController::class, 'childUserStore'])->name('store');
                Route::post('{dairy_id}/update', [MasterController::class, 'childUserUpdate'])->name('update');
                Route::post('/status-update', [MasterController::class, 'childUserStatusUpdate'])->name('status');
            });
            // rate chart
            Route::group(['prefix' => 'rate-chart', 'as' => 'rateCharts.'], function () {
                Route::get('/', [RateChartController::class, 'index'])->name('list');
                Route::get('{type}/rate-chart/{name}', [RateChartController::class, 'rateChart'])->name('view');
                Route::get('/sample-download', [RateChartController::class, 'sampleDownload'])->name('sampleDownload');
                Route::get('/upload', [RateChartController::class, 'upload'])->name('upload');
                Route::post('chart/upload', [RateChartController::class, 'uploadChart'])->name('chart.upload');
            });
            //  shopping
            Route::group(['prefix' => 'shopping', 'as' => 'shopping.'], function () {
                Route::get('/', [ShoppingController::class, 'index'])->name('list');
                Route::get('/cart', [ShoppingController::class, 'cart'])->name('cart');
                Route::get('/check-out', [ShoppingController::class, 'checkout'])->name('checkout');
                Route::get('order', [ShoppingController::class, 'order'])->name('order');
                Route::get('order/{order_id}/view', [ShoppingController::class, 'orderView'])->name('order.view');
                Route::get('order/{order_id}/print', [ShoppingController::class, 'orderPrint'])->name('order.print');
                Route::post('/cart/remove', [ShoppingController::class, 'removeCart'])->name('cart.remove');
                Route::post('/cart/update', [ShoppingController::class, 'updateCart'])->name('cart.update');
                Route::post('/add-to-cart', [ShoppingController::class, 'addTocart'])->name('cart.add');
            });
            // products
            Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
                Route::get('/', [ProductsController::class, 'index'])->name('list');
                Route::match(['get', 'post'], '/add', [ProductsController::class, 'add'])->name('add');
                Route::match(['get', 'post'], '{id}/edit', [ProductsController::class, 'edit'])->name('edit');
                Route::post('delete', [ProductsController::class, 'delete'])->name('delete');


                Route::group(['prefix' => 'groups', 'as' => 'groups.'], function () {
                    Route::get('/', [ProductsController::class, 'groups'])->name('list');
                    Route::post('/add', [ProductsController::class, 'groupsAdd'])->name('add');
                    Route::post('/update', [ProductsController::class, 'groupsUpdate'])->name('update');
                    Route::post('/status', [ProductsController::class, 'groupstatus'])->name('status');
                });


                Route::group(['prefix' => 'brands', 'as' => 'brands.'], function () {
                    Route::match(['get', 'post'], '/', [ProductsController::class, 'brands'])->name('list');
                    Route::post('/add', [ProductsController::class, 'brandsAdd'])->name('add');
                    Route::post('/update', [ProductsController::class, 'brandsUpdate'])->name('update');
                    Route::post('/status', [ProductsController::class, 'brandsStatus'])->name('status');
                });
            });
            // orders
            Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
                Route::get('/', [RateChartController::class, 'index'])->name('list');
            });
            // records
            Route::group(['prefix' => 'reports', 'as' => 'records.'], function () {
                Route::get('/milk-requests', [RecordsController::class, 'milkRequests'])->name('milk.request');
                Route::match(['get', 'post'], '/milk-requests/{record_id}/view', [RecordsController::class, 'milkRequestsView'])->name('milk.request.view');
                Route::get('/milk-buy', [RecordsController::class, 'milkBuy'])->name('milk.buy');
                Route::get('/milk-buy/print', [RecordsController::class, 'milkBuyPrint'])->name('milk.buy.print');
                Route::get('/milk-buy/trash', [RecordsController::class, 'milkBuyTrash'])->name('milk.buy.trash');
                Route::get('/milk-sell', [RecordsController::class, 'milkSell'])->name('milk.sell');
                Route::get('/milk-sell/print', [RecordsController::class, 'milkSellPrint'])->name('milk.sell.print');
                Route::get('/milk-sell/trash', [RecordsController::class, 'milkSellTrash'])->name('milk.sell.trash');
            });
        });
        Route::group(['as' => 'masters.', 'middleware' => 'ismultilevel'], function () {
            Route::get('/', [MasterController::class, 'index'])->name('list');
            Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
                Route::get('/', [MasterController::class, 'roleList'])->name('list');
                Route::get('{role_id}/view', [MasterController::class, 'rolePermissonView'])->name('view');
                Route::post('{role_id}/update', [MasterController::class, 'rolePermissonUpdate'])->name('update');
            });
            Route::group(['prefix' => 'permission', 'as' => 'permission.'], function () {
                Route::get('/', [MasterController::class, 'rolePermission'])->name('list');
            });
            Route::group(['prefix' => 'routes', 'as' => 'routes.'], function () {
                Route::get('/', [RoutesController::class, 'index'])->name('list');
                Route::get('/add', [RoutesController::class, 'add'])->name('add');
                Route::post('/store', [RoutesController::class, 'store'])->name('store');
                Route::get('{route_id}/edit', [RoutesController::class, 'edit'])->name('edit');
                Route::post('{route_id}/update', [RoutesController::class, 'update'])->name('update');
            });
            Route::group(['prefix' => 'transport', 'as' => 'transport.'], function () {
                Route::get('/', [TranspoterController::class, 'index'])->name('list');
                Route::get('/add', [TranspoterController::class, 'add'])->name('add');
                Route::post('/store', [TranspoterController::class, 'store'])->name('store');
                Route::get('{transporter_id}/edit', [TranspoterController::class, 'edit'])->name('edit');
                Route::post('{transporter_id}/update', [TranspoterController::class, 'update'])->name('update');
                Route::post('/status-update', [TranspoterController::class, 'statusUpdate'])->name('status');
            });
        });
        // });
        Route::group(['prefix' => 'plans', 'as' => 'plans.'], function () {
            Route::get('/', [PlansController::class, 'index'])->name('list');
            Route::get('/create', [PlansController::class, 'create'])->name('create');
            Route::get('{id}/add', [PlansController::class, 'add'])->name('add');
            Route::get('{id}/print', [PlansController::class, 'print'])->name('print');
            Route::get('/{id}/pay', [PlansController::class, 'pay'])->name('pay');
            Route::get('/{id}/activate', [PlansController::class, 'activate'])->name('activate');
            Route::get('/payment-status/{id}', [PlansController::class, 'paymentStatus'])->name('pay.status');
        });
    });
});
