<?php

use App\Http\Controllers\UserLoginApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\Customer\Address\AddressApiController;
use App\Http\Controllers\Api\Customer\Saloon\SaloonApiController;
use App\Http\Controllers\Api\Customer\Review\ReviewApiController;
use App\Http\Controllers\Api\Customer\Cart\CartApiController;
use App\Http\Controllers\Api\Customer\Cart\ServiceCartApiController;
use App\Http\Controllers\Api\Customer\Wishlist\WishlistApiController;
use App\Http\Controllers\Api\Customer\Wishlist\ServiceWishlistApiController;
use App\Http\Controllers\Api\Customer\Brand\BrandApiController;
use App\Http\Controllers\Api\Customer\DashboardApiController;
use App\Http\Controllers\Api\Customer\DeliveryRegion\ZipCodeApiController;
use App\Http\Controllers\Api\Customer\Discount\DiscountApiController;
use App\Http\Controllers\Api\Customer\Item\ItemApiController;
use App\Http\Controllers\Api\Customer\Service\ServiceApiController;
use App\Http\Controllers\Api\Customer\Wallet\WalletApiController;
use App\Http\Controllers\Api\Customer\Item\ItemCategoryApiController;
use App\Http\Controllers\Api\Customer\Item\ItemSubCategoryApiController;
use App\Http\Controllers\Api\Customer\Item\ProductCategoryApiController;
use App\Http\Controllers\Api\Customer\Offer\OfferApiController;
use App\Http\Controllers\Api\Customer\Order\OrderApiController;
use App\Http\Controllers\Api\Customer\ServiceOrder\ServiceOrderApiController;
use App\Http\Controllers\Api\Customer\Profile\UserDetaiApiController;
use App\Http\Controllers\Api\Customer\Shop\ShopApiController;
use App\Http\Controllers\Api\Customer\Package\PackageApiController;
use App\Http\Controllers\Api\Customer\Shop\ShopPaymentSettingApiController;
use App\Http\Controllers\Api\Customer\Branch\BranchApiController;
use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
//routes/api.php
Route::post('user-register', [UserLoginApiController::class, 'register']);
Route::post('login', [UserLoginApiController::class, 'login']);
Route::post('otp-login', [UserLoginApiController::class, 'otpLogin']);

Route::get('genders', function () {
    $gender = Gender::select('id', 'gender')->get();
    return response(['data' => $gender, 'message' => 'fetched', 'status' => 200], 200);
});
Route::resource('coupans', OfferApiController::class);
Route::resource('offers', OfferApiController::class);
Route::post('/social-login', [UserLoginApiController::class, 'socialLogin']);
//add this middleware to ensure that every request is authenticated
Route::middleware('auth:api')->group(function () {
    Route::get('users', [UserLoginApiController::class, 'userDetails']);
    Route::resource('user-profile', UserDetaiApiController::class);
    Route::resource('addresses', AddressApiController::class);
    Route::resource('saloons', SaloonApiController::class);
    Route::get('get-time-slot', [SaloonApiController::class, 'getTimeSlot']);
    Route::resource('reviews', ReviewApiController::class);
    Route::resource('carts', CartApiController::class);
    Route::resource('service-carts', ServiceCartApiController::class);
    Route::resource('service-wishlists', ServiceWishlistApiController::class);
    Route::resource('wishlists', WishlistApiController::class);
    Route::get('order/invoice', [OrderApiController::class, 'genrateOrderInvoice']);
    Route::get('validate-coupan', [OfferApiController::class, 'validateCoupan']);
    Route::resource('/branches', BranchApiController::class)->names([
        'index' => 'api-branch.index',
        'store' => 'api-branch.store',
        'create' => 'api-branch.create',
        'update' => 'api-branch.update',
        'show' => 'api-branch.show',
        'edit' => 'api-branch.edit',
        'destroy' => 'api-branch.destroy',
    ]);
    Route::resource('/packages', PackageApiController::class)->names([
        'index' => 'app-packages.index',
        'store' => 'app-packages.store',
        'create' => 'app-packages.create',
        'update' => 'app-packages.update',
        'show' => 'app-packages.show',
        'edit' => 'app-packages.edit',
        'destroy' => 'app-packages.destroy',
    ]);
    Route::resource('orders', OrderApiController::class);
    Route::resource('wallets', WalletApiController::class);
    Route::resource('service-orders', ServiceOrderApiController::class);
    Route::get('service-orders/invoice', [ServiceOrderApiController::class, 'genrateServiceOrderInvoice']);
    Route::resource('items', ItemApiController::class);
    Route::resource('offer-items', ItemApiController::class);
    Route::resource('services', ServiceApiController::class);
    Route::get('product-search-by/{section?}/{id?}', [ItemApiController::class, 'searchBy']);
    Route::resource('discounts', DiscountApiController::class);
    Route::get('items-categories-group', [ItemCategoryApiController::class, 'itemsCategoriesGroup']);
    Route::get('dashboard', [DashboardApiController::class, 'dashboard']);
    Route::get('check-delivery-region/{pin?}', [ZipCodeApiController::class, 'checkDelivery']);
    Route::resource('item-categories', ItemCategoryApiController::class);
    Route::resource('item-sub-categories', ItemSubCategoryApiController::class);
    Route::resource('product-categories', ProductCategoryApiController::class);
    Route::resource('shops', ShopApiController::class);
    Route::post('/contact-us', [UserLoginApiController::class, 'saveContactUs']);

    Route::resource('shop-payment-settings', ShopPaymentSettingApiController::class);
   
    Route::get('sliders', [OfferApiController::class, 'getSliders']);
    Route::resource('brands', BrandApiController::class)->names([
        'index' => 'app-brand.index',
        'store' => 'app-brand.store',
        'create' => 'app-brand.create',
        'update' => 'app-brand.update',
        'show' => 'app-brand.show',
        'edit' => 'app-brand.edit',
        'destroy' => 'app-brand.destroy',
    ]);
});


