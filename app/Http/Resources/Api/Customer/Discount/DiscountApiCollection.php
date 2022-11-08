<?php

namespace App\Http\Resources\Api\Customer\Discount;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountApiCollection extends JsonResource
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
            'shop_id' => $this->shop_id ?? "",
            'name'  => $this->name ?? NULL,
            'type'     => $this->type ?? NULL,
            'amount'     => $this->amount ?? NULL,
        ];
    }
}
