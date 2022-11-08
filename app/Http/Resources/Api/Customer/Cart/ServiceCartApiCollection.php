<?php

namespace App\Http\Resources\Api\Customer\Cart;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCartApiCollection extends JsonResource
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
        $service = $this->getService($this->service_id);

        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'service_id'   => $this->service_id,
            'service_date'   => $this->service_date,
            'service_time'   => $this->service_time,
            'item_name'  => !empty($service) ? $service->name : "",
            'item_category_id'  => !empty($service) ? $service->item_category_id : "",
            'description'       =>  !empty($service) ? $service->description ?? "" : "",
            'item_status'       =>  !empty($service) ? $service->status : "",
            'images'               => !empty($this->item->medias) ? \Helper::mediaListOnlyUrl($this->item->medias) : [],
            'price'      => \Helper::getDisplayAmount($this->price),
            'is_discounted' => $this->discount > 0 ? true : false,
            'discounted_price' => \Helper::getDisplayAmount((\Helper::getSalePrice($this->price, $this->discount, $this->discount_type))),
            'discount'  => strval((int)$this->discount),
            'discount_type' => $this->discount_type ?? "",
            'sub_total' => \Helper::getDisplayAmount($this->price),
            'total'     => \Helper::getDisplayAmount(\Helper::getSalePrice($this->price, $this->discount, $this->discount_type)),
            // 'service_unit'  => !empty($unit) ? $unit->name : "",
        ];
    }
}
