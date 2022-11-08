<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactUsApiCollection extends JsonResource
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
            "id" => $this->id?? "",
            "name" => $this->name?? "",
            "email" => $this->email ?? "",
            "mobile" => $this->mobile ?? "",
            "title" => $this->title ?? "",
            "description" => $this->description ?? "",
            "status" => $this->status ?? "",

        ];
    }
}
