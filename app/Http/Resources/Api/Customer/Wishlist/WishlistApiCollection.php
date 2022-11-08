<?php

namespace App\Http\Resources\Api\Customer\Wishlist;

use App\Models\Unit;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistApiCollection extends JsonResource
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
        $userId = \Helper::getUserId();

        // return parent::toArray($request);
        $unit = null;
        $item = $this->getItem($this->item_id);

        $discounted_price_display = "";
        if (!empty($item)) {
            $unit = Unit::find($item->unit_id);
            if ($item->discount_amount) {
                $discounted_price_display = isset($item->discount_amount) ? \Helper::getSalePriceDisplay(\Helper::getSalePrice($item->price, $item->discount_amount, $item->discount_type), $item->discount_type) : \Helper::getDisplayAmount($item->price);
            }
        }

        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'item_id'   => $this->item_id,
            'item_name' => !empty($item) ? $item->name : "",
            'item_category_id' => !empty($item) ? $item->item_category_id : "",
            'description' =>  !empty($item) ? $item->description : "",
            'item_status' =>  !empty($item) ? $item->status : "",
            'image'               => !empty($this->item->medias) ? \Helper::mediaListOnlyUrl($this->item->medias) : [],
            'quantity'  => 1,
            'rate'      => \Helper::getDisplayAmount($this->rate),
            'is_discounted' => $this->discount > 0 ? true : false,
            'discounted_price' => \Helper::getDisplayAmount((\Helper::getSalePrice($this->rate, $this->discount, $this->discount_type))),
            'discount'  => strval((int)$this->discount),
            'discount_type' => $this->discount_type ?? "",
            'sub_total' => \Helper::getDisplayAmount(1 * $this->rate),
            'total'     => \Helper::getDisplayAmount(1 * \Helper::getSalePrice($this->rate, $this->discount, $this->discount_type)),
            'variant_qty'  => !empty($item->qty) ? (string) \Helper::getDecimalRounded($item->qty) : "",
            'variant_unit'  => !empty($unit) ? $unit->name : "",
            "variant_discounted_price_display" => $discounted_price_display,
            "variant_sale_price" => !empty($item->sale_price) ? (string) \Helper::getDisplayAmount($item->sale_price) : "",
            "variant_price" => !empty($item->price) ? (string) \Helper::getDisplayAmount($item->price) : "",
            // 'item_variants'     => $item_variant,
            'rating'      => !empty($this->item->reviews) ? (!empty($this->item->reviews) ? $this->item->reviews->avg('rating') ?? 1 : 1) : 1,
            'cart_qty'          => (int)$this->getCartQty($userId, $this->item_id),

            // 'item_variants' => ItemVariantCollection::collection($item_variants)
        ];
    }
}
