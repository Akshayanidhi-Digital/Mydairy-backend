<?php

use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\v1\AppController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\Buyer\BuyerApiController;
use App\Http\Controllers\Api\v1\CostumerController;
use App\Http\Controllers\Api\v1\Farmer\FarmerApiController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\MasterController;
use App\Http\Controllers\Api\v1\MilkBuyController;
use App\Http\Controllers\Api\v1\MilkRateChartController;
use App\Http\Controllers\Api\v1\MilkRecordsController;
use App\Http\Controllers\Api\v1\MilkSaleController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\PlanController;
use App\Http\Controllers\Api\v1\ProductsController;
use App\Http\Controllers\Api\v1\RoutesController;
use App\Http\Controllers\Api\v1\ShoppingController;
use App\Http\Controllers\Api\v1\TranspoterController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\SendNotificationTest;
use Illuminate\Http\Middleware\TrustHosts;
use Illuminate\Support\Facades\Route;




// Route::group(function(){
// });
Route::match(['get', 'post'], 'countries', [CommonController::class, 'countries']);
Route::match(['get', 'post'], 'states', [CommonController::class, 'states']);
Route::match(['get', 'post'], 'cities', [CommonController::class, 'cities']);
Route::post('/razorpay-key', [CommonController::class, 'razorpayKey']);

Route::post('/notify', [SendNotificationTest::class, 'send']);
Route::group(['as' => 'api.'], function () {
    Route::post('/check-user', [HomeController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('password-update', [AuthController::class, 'passwordUpdate']);

    Route::group(['prefix' => 'otp'], function () {
        Route::post('verify', [AuthController::class, 'verifyOtp']);
        Route::post('resend', [AuthController::class, 'resendOtp']);
    });

    //  farmers api
    Route::group(['prefix' => 'farmer', 'middleware' => 'auth:farmer-api'], function () {
        Route::post('/logout', [CommonController::class, 'logout']);
        Route::post('/info', [FarmerApiController::class, 'info']);
        Route::post('/update', [FarmerApiController::class, 'profileUpdate']);
        Route::post('/logout-all', [FarmerApiController::class, 'logoutAll']);
        Route::group(['prefix' => 'milk-records'], function () {
            Route::post('/', [FarmerApiController::class, 'milkRecords']);
            Route::post('/count', [FarmerApiController::class, 'milkRecordsCountsPerYear']);
            Route::post('/detailed', [FarmerApiController::class, 'milkRecordsDetailed']);
        });
    });

    // buyers api

    Route::group(['prefix' => 'buyer', 'middleware' => 'auth:buyer-api'], function () {
        Route::post('/logout', [CommonController::class, 'logout']);
        Route::post('/info', [BuyerApiController::class, 'info']);
        Route::post('/update', [BuyerApiController::class, 'profileUpdate']);
        Route::group(['prefix' => 'milk-records'], function () {
            Route::post('/', [BuyerApiController::class, 'milkRecords']);
            Route::post('/count', [BuyerApiController::class, 'milkRecordsCountsPerYear']);
            Route::post('/detailed', [BuyerApiController::class, 'milkRecordsDetailed']);
        });
    });

    // dairy and child dairy user like bmc dcs etc. Apis

    Route::group(['middleware' => 'auth:api', 'prefix' => 'dairy'], function () {
        Route::post('/help', [AppController::class, 'help']);
        Route::post('/logout', [CommonController::class, 'logout']);
        Route::group(['prefix' => 'user'], function () {
            Route::post('/onboard', [UserController::class, 'onboard']);
            Route::post('/notification', [UserController::class, 'notification']);
            Route::post('profile', [UserController::class, 'profile']);
            Route::post('profile/update', [UserController::class, 'profileUpdate']);
            Route::post('qr-login', [UserController::class, 'qrLogin']);
            Route::group(['prefix' => 'settings'], function () {
                Route::post('/', [UserController::class, 'settings']);
                Route::post('update', [UserController::class, 'settingUpdates']);
                Route::post('lang-update', [UserController::class, 'langUpdate']);
            });
        });
        Route::group(['prefix' => 'rate-chart'], function () {
            Route::post('buy', [MilkRateChartController::class, 'buyRateChart']);
            Route::post('sell', [MilkRateChartController::class, 'sellRateChart']);
            Route::post('upload', [MilkRateChartController::class, 'rateChartUpload']);
            Route::post('/', [MilkRateChartController::class, 'index']);
            Route::post('update', [MilkRateChartController::class, 'update']);
            Route::match(['get', 'post'], 'download', [MilkRateChartController::class, 'download'])->withoutMiddleware('auth:api');
            Route::match(['get', 'post'], 'sample', [MilkRateChartController::class, 'sampleDownload'])->withoutMiddleware('auth:api');
        });

        Route::group(['prefix' => 'costumers'], function () {
            Route::post('/', [CostumerController::class, 'index']);
            Route::post('add', [CostumerController::class, 'add']);
            Route::post('update', [CostumerController::class, 'update']);
            Route::post('status', [CostumerController::class, 'status']);
            Route::post('delete', [CostumerController::class, 'delete']);
        });

        // Route::group(['prefix' => 'farmer'], function () {
        //     Route::post('/', [FarmerController::class, 'index']);
        //     Route::post('add', [FarmerController::class, 'add']);
        //     Route::post('rate-update', [FarmerController::class, 'updateRate']);
        //     Route::post('update', [FarmerController::class, 'update']);
        //     Route::post('delete', [FarmerController::class, 'delete']);
        //     Route::post('restore', [FarmerController::class, 'restore']);
        // });

        // Route::group(['prefix' => 'buyer'], function () {
        //     Route::post('/', [BuyerController::class, 'index']);
        //     Route::post('add', [BuyerController::class, 'add']);
        //     Route::post('rate-update', [BuyerController::class, 'updateRate']);
        //     Route::post('update', [BuyerController::class, 'update']);
        //     Route::post('delete', [BuyerController::class, 'delete']);
        //     Route::post('restore', [BuyerController::class, 'restore']);
        // });

        Route::group(['prefix' => 'milk'], function () {
            Route::group(['prefix' => 'buy', 'as' => 'Milkbuy.'], function () {
                Route::post('/', [MilkBuyController::class, 'index'])->name('index');
                Route::post('/add', [MilkBuyController::class, 'store']);
                Route::post('/get-price', [MilkBuyController::class, 'calculateAmount']);
                Route::post('/get-print', [MilkBuyController::class, 'print']);
                Route::post('/trash', [MilkBuyController::class, 'trash'])->name('destroy');
                Route::post('/restore', [MilkBuyController::class, 'restore']);
                Route::post('/trash-list', [MilkBuyController::class, 'trashList']);
                Route::post('/trash-empty', [MilkBuyController::class, 'trashEmpty'])->name('delete');
            });

            Route::group(['prefix' => 'sell', 'as' => 'MilkSell.'], function () {
                Route::post('/', [MilkSaleController::class, 'index'])->name('index');;
                Route::post('/add', [MilkSaleController::class, 'store']);
                Route::post('/get-price', [MilkSaleController::class, 'calculateAmount']);
                Route::post('/get-print', [MilkSaleController::class, 'print']);
                Route::post('/trash', [MilkSaleController::class, 'trash'])->name('destroy');;
                Route::post('/restore', [MilkSaleController::class, 'restore']);
                Route::post('/trash-list', [MilkSaleController::class, 'trashList']);
                Route::post('/trash-empty', [MilkSaleController::class, 'trashEmpty'])->name('delete');;
            });

            Route::post('records', [MilkRecordsController::class, 'records']);
            Route::post('records/print', [MilkRecordsController::class, 'recordsPrint']);
        });
        Route::group(['prefix' => 'plans'], function () {
            Route::post('/', [PlanController::class, 'index']);
            Route::post('/purchase', [PlanController::class, 'planPurchase']);
            Route::post('/purchase/list', [PlanController::class, 'planPurchaseList']);
        });

        Route::group(['prefix' => 'products'], function () {
            Route::post('/', [ProductsController::class, 'index']);
            Route::post('/add', [ProductsController::class, 'addProduct']);
            Route::post('/edit', [ProductsController::class, 'editProduct']);
            Route::post('/unit-types', [ProductsController::class, 'productsUnitTypes']);
            Route::group(['prefix' => 'group'], function () {
                Route::post('/', [ProductsController::class, 'listProductGroup']);
                Route::post('/add', [ProductsController::class, 'addProductGroup']);
                Route::post('/edit', [ProductsController::class, 'editProductGroup']);
                Route::post('/update', [ProductsController::class, 'updateProductGroup']);
            });
            Route::group(['prefix' => 'brand'], function () {
                Route::post('/', [ProductsController::class, 'listProductBrand']);
                Route::post('/add', [ProductsController::class, 'addProductBrand']);
                Route::post('/edit', [ProductsController::class, 'editProductBrand']);
                Route::post('/update', [ProductsController::class, 'updateProductBrand']);
                Route::post('/groups-list', [ProductsController::class, 'groupListProductBrand']);
            });
        });
        Route::group(['prefix' => 'orders'], function () {
            Route::group(['prefix' => 'myorder'], function () {
                Route::post('/', [OrderController::class, 'myorder']);
            });
            Route::group(['prefix' => 'costumers'], function () {
                Route::post('/', [OrderController::class, 'costumers']);
            });
        });
        Route::group(['prefix' => 'shopping'], function () {
            Route::group(['prefix' => 'products'], function () {
                Route::post('/', [ShoppingController::class, 'shoppingProducts']);
                Route::post('/groups', [ShoppingController::class, 'shoppingProductsGroups']);
                Route::post('/brands', [ShoppingController::class, 'shoppingProductsBrands']);
            });
            Route::post('/product', [ShoppingController::class, 'shoppingProduct']);
            Route::group(['prefix' => 'cart'], function () {
                Route::post('/', [ShoppingController::class, 'cart']);
                Route::post('/add', [ShoppingController::class, 'cartAdd']);
                Route::post('/update', [ShoppingController::class, 'cartUpdate']);
                Route::post('/remove', [ShoppingController::class, 'cartRemove']);
            });
            Route::post('/checkout', [ShoppingController::class, 'checkout']);
            Route::post('/orders', [ShoppingController::class, 'orders']);
        });
        // only dairy apis
        Route::group(['prefix' => 'master'], function () {
            Route::group(['prefix' => 'role'], function () {
                Route::post('/', [MasterController::class, 'roles']);
                Route::post('/view', [MasterController::class, 'rolesView']);
                Route::post('/update', [MasterController::class, 'rolesUpdate']);
            });
            Route::group(['prefix' => 'child-dairy'], function () {
                Route::post('/', [MasterController::class, 'childDairy']);
                Route::post('/add', [MasterController::class, 'childDairyAdd']);
                Route::post('/update', [MasterController::class, 'childDairyUpdate']);
                Route::post('/status-update', [MasterController::class, 'childDairyBlockUnblock']);
            });
            Route::group(['prefix' => 'routes'], function () {
                Route::post('/', [RoutesController::class, 'index']);
                Route::post('/add', [RoutesController::class, 'add']);
                Route::post('/dairy-list', [RoutesController::class, 'dairy_list']);
                Route::post('/update', [RoutesController::class, 'update']);
                Route::post('/status-update', [RoutesController::class, 'statusUpdate']);
                Route::post('/delete', [RoutesController::class, 'delete']);
            });
            Route::group(['prefix' => 'transporter'], function () {
                Route::post('/', [TranspoterController::class, 'index']);
                Route::post('/add', [TranspoterController::class, 'add']);
                Route::post('/status-update', [TranspoterController::class, 'statusUpdate']);
            });
        });
    });
});
