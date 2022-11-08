<?php

namespace App\Http\Resources\Admin\PaymentMethod;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodApiCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? "",
            'name' => $this->name ?? "",
            'status' => $this->status ?? "",
        ];
    }
}
