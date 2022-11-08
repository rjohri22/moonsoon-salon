<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Customer\Item\ItemApiCollection;
use App\Http\Resources\Api\Customer\Item\ProductCategoryApiCollection;
use App\Http\Resources\Api\Customer\Offer\OfferApiCollection;
use App\Http\Resources\Api\Customer\Brand\BrandApiCollection;
use App\Http\Resources\Api\Customer\Service\ServiceApiCollection;
use App\Models\Brand;
use App\Models\Item;
use App\Models\Offer;
use App\Models\Service;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Utils\Helper;
class DashboardApiController extends AppBaseController
{
    //
    /**
     *   @OA\Get(
     *     path="/api/dashboard",
     *      tags={"Salon App:dashboard"},
     *          
     *         
     *          @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *          ),
     *     )
     */
    public function dashboard()
    {

        $sliders    = Offer::getSlider()->getActive()->get();
        $offers     = Offer::getActive()->get();
        $brands     = Brand::getActive()->limit(12)->orderBy('id','desc')->get();
        $services     = Service::getActive()->limit(10)->orderBy('id','desc')->get();
        $productCategories = ProductCategory::getTrendingCategory()->getActive()->limit(6)->get();

        $offerCategories =['brands','item_categories','item_sub_categories','product_categories','items'];
        $offerCategoriesData = [];
        foreach($offerCategories as $key => $value)
        {
            $offers = Offer::getActive()->where('table_type',$value)->get();
            $offerCategoriesData[$value] = OfferApiCollection::collection($offers);
        }
        $dayBeforeYesterday = Helper::dayBeforeWeek();
        $items = Item::getActive()->limit(10)->orderBy('id','desc')->get();
        $newArrivals = Item::getActive()->limit(10)->orderBy('id','desc')->get();

        return $this->sendResponse([
            'slider'           => OfferApiCollection::collection($sliders),
            'offers'           => OfferApiCollection::collection($sliders),
            'trending_categories' => ProductCategoryApiCollection::collection($productCategories),
            'offers_categories'   => $offerCategoriesData,
            'items'   => ItemApiCollection::collection($items),
            'new_arrivals'   => ItemApiCollection::collection($newArrivals),
            'brands'   => BrandApiCollection::collection($brands),
            'services'   => ServiceApiCollection::collection($services),
        ], "");
    }
}
