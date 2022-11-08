<?php

namespace App\Http\Resources\Api\Customer\CategoryGroup;

use App\Traits\HelperTrait;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemGroupApiCollection extends JsonResource
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
            'discount_type_text'                => $this->discountType->discount_type,
            'price'                             => $this->price,
            'price_display'                     => Helper::getDisplayAmount($this->price),
            'qty'                               => $this->qty,
            'sale_price'                        => Helper::getSalePrice($this->price, $this->discount_amount, $this->discount_type),
            'sale_price_display'                => Helper::getSalePriceDisplay(Helper::getSalePrice($this->price, $this->discount_amount, $this->discount_type), $this->discount_type),
            'item_images'                            => count($this->medias) ? Helper::mediaListOnlyUrl($this->medias) : [] ,
            //Extra Info
            'description'                       => $this->description ?? '',
            'how_to_use'                        => $this->how_to_use ?? '',
            'benefits'                          => $this->benefits ?? '',
            'status'                            => $this->status,
        ];
    }
}
