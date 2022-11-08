<?php

namespace App\Http\Resources\Admin\ShopSettings;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopSettingWebCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "delivery_charge" => $this->delivery_charge ?? '',
            "service_charge" => $this->service_charge ?? "",
            "avail_return_days" => $this->avail_return_days ?? "",
            "min_order_value" => $this->min_order_value ?? "",
        ];
    }
}
