<?php

namespace App\Http\Resources\Api\Customer\Shop;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopApiCollection extends JsonResource
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
            'module_id'     => $this->module_id ?? "",
            'user_id'     => $this->user_id ?? NULL,
            'name' => $this->name ?? "",
            'whatsapp_no'  => $this->whatsapp_no ?? NULL,
            'address'     => $this->address ?? NULL,
            'city'   => $this->city ?? NULL,
            'state'     => $this->state ?? NULL,
            'country'     => $this->country ?? NULL,
            'zip'     => $this->zip ?? NULL,
            'description'     => $this->description ?? NULL,
            'free_delivery_amount'     => $this->free_delivery_amount ?? NULL,
            'minimum_delivery_time'     => $this->minimum_delivery_time ?? NULL,
            'total_rating'     => $this->total_rating ?? NULL,
            'is_free_delivery'     => $this->is_free_delivery ?? NULL,
            'lat'     => $this->lat ?? NULL,
            'lng'     => $this->lng ?? NULL,
            'owners'     => $this->owners ?? NULL,
        ];
    }
}
