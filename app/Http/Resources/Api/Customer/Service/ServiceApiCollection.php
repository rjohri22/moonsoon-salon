<?php

namespace App\Http\Resources\Api\Customer\Service;

use App\Traits\HelperTrait;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceApiCollection extends JsonResource
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
        
        $userId =  \Helper::getUserId();

        return [
            'id'                                => $this->id,
            'item_category_id'               => $this->item_category_id,
            'branch_id'               => $this->branch_id,
            'service_category_name'             =>  !empty($this->serviceCategory) ?  $this->serviceCategory->name : "",
            'item_sub_category_id'           => $this->item_sub_category_id,
            'name'                              => $this->name,
            // Amount
            'discount_amount'                   => $this->discount_amount,
            'discount_type'                     => $this->discount_type,
            // 'discount_type_text'                => !empty$this->discountType->discount_type,
            'price'                             => $this->price,
            'price_display'                     => Helper::getDisplayAmount($this->price),
            'service_price'                        => Helper::getSalePrice($this->price, $this->discount_amount, $this->discount_type),
            'service_price_display'                => Helper::getSalePriceDisplay(Helper::getSalePrice($this->price, $this->discount_amount, $this->discount_type), $this->discount_type),
            'service_images'                       => count($this->medias) ? Helper::mediaListOnlyUrl($this->medias) : [] ,
            //Extra Info
            'description'                       => $this->description ?? '',
            'how_to_use'                        => $this->how_to_use ?? '',
            'benefits'                          => $this->benefits ?? '',
            'status'                            => $this->status,
            'cart_qty'                          => (int)$this->getServiceCartQty($userId, $this->id),
            'is_wishlist'                       => (int)$this->getServiceWishlistQty($userId, $this->id) ? 1 : 0,
            "rating"                            => !empty($this->reviews) ? (!empty($this->reviews) ? Helper::getRatingFormate($this->reviews->avg('rating')) ?? 1 : 1) : 1,
            "review_comment"                    => !empty($this->reviews) ? array_unique($this->reviews->pluck('comment')->toArray()) : 0,
        ];
    }
}
