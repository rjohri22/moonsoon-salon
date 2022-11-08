<?php

namespace App\Http\Resources\Api\Customer\Wishlist;

use App\Models\Unit;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Utils\Helper;

class ServiceWishlistApiCollection extends JsonResource
{
    use HelperTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userId = Helper::getUserId();

        // return parent::toArray($request);
        $unit = null;
        $service = $this->getService($this->service_id);

        $discounted_price_display = "";
        if (!empty($service)) {
            $unit = Unit::find($service->unit_id);
            if ($service->discount_amount) {
                $discounted_price_display = isset($service->discount_amount) ? \Helper::getSalePriceDisplay(\Helper::getSalePrice($service->price, $service->discount_amount, $service->discount_type), $service->discount_type) : \Helper::getDisplayAmount($service->price);
            }
        }

        return [
        //     'id'        => $this->id,
        //     'user_id'   => $this->user_id,
        //     'service_id'   => $this->service_id,
        //     'service_name' => !empty($service) ? $service->name : "",
        //     'service_category_id' => !empty($service) ? $service->service_category_id : "",
        //     'description' =>  !empty($service) ? $service->description : "",
        //     'service_status' =>  !empty($service) ? $service->status : "",
        //     'image'               => !empty($this->service->medias) ? \Helper::mediaListOnlyUrl($this->service->medias) : [],
        //     'quantity'  => 1,
        //     'rate'      => \Helper::getDisplayAmount($this->price),
        //     'is_discounted' => $this->discount > 0 ? true : false,
        //     'discounted_price' => \Helper::getDisplayAmount((\Helper::getSalePrice($this->price, $this->discount, $this->discount_type))),
        //     'discount'  => strval((int)$this->discount),
        //     'discount_type' => $this->discount_type ?? "",
        //     'sub_total' => \Helper::getDisplayAmount(1 * $this->price),
        //     'total'     => \Helper::getDisplayAmount(1 * \Helper::getSalePrice($this->price, $this->discount, $this->discount_type)),
        //     "discounted_price_display" => $discounted_price_display,
        //     'rating'      => !empty($this->service->reviews) ? (!empty($this->service->reviews) ? $this->service->reviews->avg('rating') ?? 1 : 1) : 1,
        //     // 'cart_qty'          => (int)$this->getServiceCartQty($userId, $this->service_id),
        //     //Extra Info
        //     'description'                       => !empty($service) ? $service->description : "",
        //     'how_to_use'                        => !empty($service) ? $service->how_to_use : "",
        //     'benefits'                          => !empty($service) ? $service->benefits : "",
        // ];
        'id'                                => $this->id,
        'service_id'                        => $this->service_id,
        'item_category_id'                  =>!empty($service) ? $service->item_category_id : "",
        'service_category_name'             => !empty($service) ? (!empty($this->serviceCategory) ?  $this->serviceCategory->name : ""):"",
        'item_sub_category_id'              =>  !empty($service) ? $service->item_sub_category_id : "",
        'name'                              => !empty($service) ? $service->name : "",
        // Amount
        'discount_amount'                   => !empty($service) ? $service->discount_amount : "",
        'discount_type'                     => !empty($service) ? $service->discount_type : "",
        "discounted_price_display"          => $discounted_price_display,

        // 'discount_type_text'                => !empty$this->discountType->discount_type,
        'price'                             => !empty($service) ? $service->price : "",
        'price_display'                     => !empty($service) ? Helper::getDisplayAmount($service->price) : "",
        'service_price'                        =>!empty($service) ? Helper::getSalePrice($service->price, $service->discount_amount, $service->discount_type) : "",
        'service_price_display'                =>!empty($service) ? Helper::getSalePriceDisplay(Helper::getSalePrice($service->price, $service->discount_amount, $service->discount_type), $service->discount_type): "",
        'service_images'                       => !empty($service) ? (count($service->medias) ? Helper::mediaListOnlyUrl($service->medias) : []) :[] ,
        //Extra Info
        'description'                       => !empty($service) ? $service->description : "",
        'how_to_use'                        =>!empty($service) ? $service->how_to_use : "",
        'benefits'                          =>!empty($service) ? $service->benefits : "" ,
        'status'                            =>!empty($service) ? $service->status : "",
        'cart_qty'                          => !empty($service) ? (int)$this->getServiceCartQty($userId, $service->id) :0,
        'is_wishlist'                          => !empty($service) ? (int)$this->getServiceWishlistQty($userId, $service->id) :0,
        "rating"                            => !empty($service) ? (!empty($service->reviews) ? (!empty($service->reviews) ? Helper::getRatingFormate($service->reviews->avg('rating')) ?? 1 : 1) : 1):1,
        "review_comment"                            => !empty($service) ? (!empty($service->reviews) ?  array_unique($service->reviews->pluck('comment')->toArray())  : 1):1,
        ];
    }
}
