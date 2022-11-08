<?php

namespace App\Http\Resources\Api\Customer\Item;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemSubCategoryApiCollection extends JsonResource
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
            'id' => $this->id,
            'parent_category' => $this->parentCategory->name ?? '',
            'sub_category_name' => $this->name ?? "",
            'image' =>
            !empty($this->media) ? $this->media->getUrl() : "",


            'sub_category_description' => $this->description ?? "",
        ];
    }
}
