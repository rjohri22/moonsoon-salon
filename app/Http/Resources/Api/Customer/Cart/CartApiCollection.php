<?php

namespace App\Http\Resources\Api\Customer\Cart;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class CartApiCollection extends JsonResource
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
        $item = $this->getItem($this->item_id);

        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'item_id'   => $this->item_id,
            'item_name'  => !empty($item) ? $item->name : "",
            'item_category_id'  => !empty($item) ? $item->item_category_id : "",
            'description'       =>  !empty($item) ? $item->description ?? "" : "",
            'item_status'       =>  !empty($item) ? $item->status : "",
            'images'               => !empty($this->item->medias) ? \Helper::mediaListOnlyUrl($this->item->medias) : [],
            'quantity'  => $this->quantity,
            'price'      => \Helper::getDisplayAmount($this->price),
            'is_discounted' => $this->discount > 0 ? true : false,
            'discounted_price' => \Helper::getDisplayAmount((\Helper::getSalePrice($this->price, $this->discount, $this->discount_type))),
            'discount'  => strval((int)$this->discount),
            'discount_type' => $this->discount_type ?? "",
            'sub_total' => \Helper::getDisplayAmount($this->quantity * $this->price),
            'total'     => \Helper::getDisplayAmount($this->quantity * \Helper::getSalePrice($this->price, $this->discount, $this->discount_type)),

            'item_qty'  => !empty($item->qty) ? (string) \Helper::getDecimalRounded($item->qty) : "",
            'item_unit'  => !empty($unit) ? $unit->name : "",
        ];
    }
}
