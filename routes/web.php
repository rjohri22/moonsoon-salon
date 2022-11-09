<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Brand\BrandApiController;
use App\Http\Controllers\Admin\DeliveryRegion\ZipCodeController;
use App\Http\Controllers\Admin\Item\ItemCategoryApiController;
use App\Http\Controllers\Admin\Item\ItemSubCategoryApiController;
use App\Http\Controllers\Admin\Item\ProductCategoryApiController;
use App\Http\Controllers\Admin\Item\ItemApiController;
use App\Http\Controllers\Admin\Service\ServiceApiController;
use App\Http\Controllers\Admin\Offer\OfferApiController;
use App\Http\Controllers\Admin\Order\OrderApiController;
use App\Http\Controllers\Admin\ServiceOrder\ServiceOrderApiController;
use App\Http\Controllers\Admin\PaymentMethod\PaymentMethodApiController;
use App\Http\Controllers\Admin\ShopSetting\ShopSettingController;
use App\Http\Controllers\Admin\Branch\BranchApiController;
use App\Http\Controllers\Admin\Package\PackageApiController;
use App\Http\Resources\Admin\Offer\OfferApiCollection;
use App\Http\Controllers\Admin\ItemVideosController;
use Illuminate\Support\Facades\Hash;

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
    return view('auth/login');
});

/* Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard'); */

Route::get('clear-all', function () {
    $exitcode = Artisan::call('config:clear');
    $exitcode = Artisan::call('cache:clear');
    $exitcode = Artisan::call('route:clear');
    $exitcode = Artisan::call('view:clear');
    $exitcode = Artisan::call('config:cache');
    echo "Done";
});

Route::get("db-refresh", function () {
    $exitcode = Artisan::call('migrate:refresh');
    $exitcode = Artisan::call('db:seed');
    return redirect('/');
});

Route::get("db-migrate", function () {
    $exitcode = Artisan::call('migrate'); 
    return dd('migration done');
});




Route::prefix('admin')->middleware(['auth:sanctum', 'verified'])->group(function () {
    // Route::prefix('admin')->middleware('auth')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin/dashboard');
    // })->name('dashboard');

    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/profile', [AdminController::class, 'profileView']);
    Route::post('/profile', [AdminController::class, 'updateProfile']);
    Route::get('/password-update', [AdminController::class, 'changePasswordView']);
    Route::post('/password-update', [AdminController::class, 'updatePassword']);

    Route::resource('/brands', BrandApiController::class)->names([
        'index' => 'admin-brand.index',
        'store' => 'admin-brand.store',
        'create' => 'admin-brand.create',
        'update' => 'admin-brand.update',
        'show' => 'admin-brand.show',
        'edit' => 'admin-brand.edit',
        'destroy' => 'admin-brand.destroy',
    ]);
    Route::resource('/branches', BranchApiController::class)->names([
        'index' => 'admin-branch.index',
        'store' => 'admin-branch.store',
        'create' => 'admin-branch.create',
        'update' => 'admin-branch.update',
        'show' => 'admin-branch.show',
        'edit' => 'admin-branch.edit',
        'destroy' => 'admin-branch.destroy',
    ]);
    // Route::post('/brands-update/{id}', [BrandApiController::class, 'update']);

    Route::resource('/categories', ItemCategoryApiController::class)->names([
        'index' => 'admin-categories.index',
        'store' => 'admin-categories.store',
        'create' => 'admin-categories.create',
        'update' => 'admin-categories.update',
        'show' => 'admin-categories.show',
        'edit' => 'admin-categories.edit',
        'destroy' => 'admin-categories.destroy',
    ]);;
    //Route::post('/categories-update/{id}', [ItemCategoryApiController::class, 'update']);

    Route::resource('/sub-categories', ItemSubCategoryApiController::class)->names([
        'index' => 'admin-sub-categories.index',
        'store' => 'admin-sub-categories.store',
        'create' => 'admin-sub-categories.create',
        'update' => 'admin-sub-categories.update',
        'show' => 'admin-sub-categories.show',
        'edit' => 'admin-sub-categories.edit',
        'destroy' => 'admin-sub-categories.destroy',
    ]);;
    //Route::post('/sub-categories-update/{id}', [ItemSubCategoryApiController::class, 'update']);

    Route::resource('/product-categories', ProductCategoryApiController::class)->names([
        'index' => 'admin-product-categories.index',
        'store' => 'admin-product-categories.store',
        'create' => 'admin-product-categories.create',
        'update' => 'admin-product-categories.update',
        'show' => 'admin-product-categories.show',
        'edit' => 'admin-product-categories.edit',
        'destroy' => 'admin-product-categories.destroy',
    ]);
    //Route::post('/product-categories-update/{id}', [ProductCategoryApiController::class, 'update']);

    Route::resource('/items', ItemApiController::class)->names([
        'index' => 'admin-items.index',
        'store' => 'admin-items.store',
        'create' => 'admin-items.create',
        'update' => 'admin-items.update',
        'show' => 'admin-items.show',
        'edit' => 'admin-items.edit',
        'destroy' => 'admin-items.destroy',
    ]);
    Route::resource('/services', ServiceApiController::class)->names([
        'index' => 'admin-services.index',
        'store' => 'admin-services.store',
        'create' => 'admin-services.create',
        'update' => 'admin-services.update',
        'show' => 'admin-services.show',
        'edit' => 'admin-services.edit',
        'destroy' => 'admin-services.destroy',
    ]);
    Route::resource('/packages', PackageApiController::class)->names([
        'index' => 'admin-packages.index',
        'store' => 'admin-packages.store',
        'create' => 'admin-packages.create',
        'update' => 'admin-packages.update',
        'show' => 'admin-packages.show',
        'edit' => 'admin-packages.edit',
        'destroy' => 'admin-packages.destroy',
    ]);
    Route::post('/items-update/{id}', [ItemApiController::class, 'update']);
    Route::resource('/offers', OfferApiController::class)->names([
        'index' => 'admin-offers.index',
        'store' => 'admin-offers.store',
        'create' => 'admin-offers.create',
        'update' => 'admin-offers.update',
        'show' => 'admin-offers.show',
        'edit' => 'admin-offers.edit',
        'destroy' => 'admin-offers.destroy',
    ]);
    Route::resource('/payment-methods', PaymentMethodApiController::class)->names([
        'index' => 'admin-payment-methods.index',
        'store' => 'admin-payment-methods.store',
        'create' => 'admin-payment-methods.create',
        'update' => 'admin-payment-methods.update',
        'show' => 'admin-payment-methods.show',
        'edit' => 'admin-payment-methods.edit',
        'destroy' => 'admin-payment-methods.destroy',
    ]);
    Route::get('subcategories-dd/{id?}', [
        ItemApiController::class, 'getSubCategoryDD'
    ])->name('subcategory_dropdown');
    Route::get('productcategories-dd/{id?}', [ItemApiController::class, 'getProductCategoryDD'])->name('productcategory_dropdown');
    Route::resource('/shop-settings', ShopSettingController::class)->names([
        'index' => 'admin-shop-setting.index',
        'store' => 'admin-shop-setting.store',
        'create' => 'admin-shop-setting.create',
        'update' => 'admin-shop-setting.update',
        'show' => 'admin-shop-setting.show',
        'edit' => 'admin-shop-setting.edit',
        'destroy' => 'admin-shop-setting.destroy',
    ]);
    Route::get('add-to-slider/{id?}', [OfferApiController::class, 'addToSlider'])->name('add-to-slider');
    /* Route::get('delivery-regions', function () {
        return "Hello World";
    }); */
    Route::resource('delivery-regions', ZipCodeController::class)->names([
        'index' => 'admin-delivery-regions.index',
        'store' => 'admin-delivery-regions.store',
        'create' => 'admin-delivery-regions.create',
        'update' => 'admin-delivery-regions.update',
        'show' => 'admin-delivery-regions.show',
        'edit' => 'admin-delivery-regions.edit',
        'destroy' => 'admin-delivery-regions.destroy',
    ]);
    Route::post('import/delivery-regions', [ZipCodeController::class, 'importFile'])->name('import-file');

    Route::resource('orders', OrderApiController::class)->names([
        'index' => 'admin-orders.index',
        'store' => 'admin-orders.store',
        'create' => 'admin-orders.create',
        'update' => 'admin-orders.update',
        'show' => 'admin-orders.show',
        'edit' => 'admin-orders.edit',
        'destroy' => 'admin-orders.destroy',
    ]);

    Route::get('genrate-order-invoice', [OrderApiController::class,'genrateOrderInvoice']);
    Route::get('genrate-service-order-invoice', [ServiceOrderApiController::class,'genrateServiceOrderInvoice']);
    Route::resource('service-orders', ServiceOrderApiController::class)->names([
        'index' => 'admin-service-orders.index',
        'store' => 'admin-service-orders.store',
        'create' => 'admin-service-orders.create',
        'update' => 'admin-service-orders.update',
        'show' => 'admin-service-orders.show',
        'edit' => 'admin-service-orders.edit',
        'destroy' => 'admin-service-orders.destroy',
    ]);
 

    Route::get('offer-prefrence/{id?}',[OfferApiController::class,'offerPrefrence'])->name('offer-prefrence');

    Route::get("item-videos",[ItemVideosController::class,'itemVideos']);
    Route::post("item-video-addupdate",[ItemVideosController::class,'addUpdateItemVideo']);

    Route::delete("item-video-delete/{deleteid}",[ItemVideosController::class,'deleteItemVideo']);
    Route::get("item-video-detail/{videoid}",[ItemVideosController::class,'getItemVideoDetail']);
    
    Route::post("item-videos",[ItemVideosController::class,'itemVideos']);


});
