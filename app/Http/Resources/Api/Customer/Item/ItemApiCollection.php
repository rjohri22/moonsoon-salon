<?php

namespace App\Http\Resources\Api\Customer\Item;

use App\Traits\HelperTrait;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemApiCollection extends JsonResource
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
            'brand_id'                          => $this->brand_id,
            'brand_name'                        => !empty($this->brand) ? $this->brand->name : "",
            'item_category_id'                  => $this->item_category_id,
            'item_category_name'                =>  !empty($this->itemCategory) ?  $this->itemCategory->name : "",
            'item_sub_category_id'              => $this->item_sub_category_id,
            'product_category_id'                  => $this->product_category_id,
            'name'                              => $this->name,
            'unit_id'                           => $this->unit_id,
            'unit_name_display'                 => !empty($this->unit) ? $this->unit->name : "",
            'unit_value'                        => $this->unit_value ?? 0,
            // Amount
            'discount_amount'                   => $this->discount_amount,
            'discount_type'                     => $this->discount_type,
            'discount_type_text'                => !empty($this->discountType) ? $this->discountType->discount_type :"",
            'price'                             => $this->price,
            'price_display'                     => Helper::getDisplayAmount($this->price),
            'qty'                               => $this->qty,
            'sale_price'                        => Helper::getSalePrice($this->price, $this->discount_amount, $this->discount_type),
            'sale_price_display'                => Helper::getSalePriceDisplay(Helper::getSalePrice($this->price, $this->discount_amount, $this->discount_type), $this->discount_type),
            'item_images'                       => count($this->medias) ? Helper::mediaListOnlyUrl($this->medias) : [] ,
            //Extra Info
            'description'                       => $this->description ?? '',
            'how_to_use'                        => $this->how_to_use ?? '',
            'benefits'                          => $this->benefits ?? '',
            'status'                            => $this->status,
            'cart_qty'                          => (int)$this->getCartQty($userId, $this->id),
            'is_wishlist'                       => (int)$this->getWishlistQty($userId, $this->id) ? 1 : 0,
            "rating"                            => !empty($this->reviews) ? (!empty($this->reviews) ? Helper::getRatingFormate($this->reviews->avg('rating')) ?? 1 : 1) : 1,
            "review_comment"                    => !empty($this->reviews) ? array_unique($this->reviews->pluck('comment')->toArray()) : 0,
        ];
    }
}
