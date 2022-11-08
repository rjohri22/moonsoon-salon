<?php

namespace App\Http\Resources\Admin\Item;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCategoryApiCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id ?? '',
            'image' => $this->media->getUrl() ?? "",
            'sub-category' => $this->getSubCategory->name ?? '',
            'product_category' => $this->name ?? '',
        ];
    }
}
