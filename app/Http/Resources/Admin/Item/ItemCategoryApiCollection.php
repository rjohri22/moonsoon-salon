<?php

namespace App\Http\Resources\Admin\Item;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemCategoryApiCollection extends JsonResource
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
            
            'id' => $this->id,
            'name'  => $this->name ?? '',
            'image' => $this->media->getUrl() ?? "",

            'description'     => $this->description ?? '',
        ];
    }
}
