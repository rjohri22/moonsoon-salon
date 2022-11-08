<?php

namespace App\Http\Resources\Api\Customer\Address;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollection extends JsonResource
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
        //return parent::toArray($request);
        return [
            'id' => $this->id ?? "",
            'user_id'  => $this->user_id ?? "",
            'type'     => $this->type ?? "",
            'other_place_tag_name' => $this->other_place_tag_name ?? "",
            'first_name'     => $this->first_name ?? "",
            'last_name'     => $this->last_name ?? "",
            'type_display'     => !empty($this->type) ? \Helper::humanStringFormat($this->type) : "",
            'street'   => $this->street ?? "",
            'landmark' => $this->landmark ?? "",
            'city'     => $this->city ?? "",
            'state'    => $this->state ?? "",
            'zipcode'  => $this->zipcode ?? "",
            'contact'  => $this->contact ?? "",
        ];
    }
}
