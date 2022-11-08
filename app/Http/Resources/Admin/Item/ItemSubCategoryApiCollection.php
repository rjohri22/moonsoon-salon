<?php

namespace App\Http\Resources\Admin\Item;

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
            'image' => $this->media->getUrl() ?? "",

            'parent_category' => $this->parentCategory->name ?? '',
            'sub_category_name' => $this->name ?? "",
            'sub_category_description' => $this->description ?? "",
        ];
    }
}
