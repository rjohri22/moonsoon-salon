<?php

namespace App\Http\Resources\Api\Customer\CategoryGroup;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Api\Customer\CategoryGroup\ItemGroupApiCollection;
class ProductGroupCategoryApiCollection extends JsonResource
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
            'sub-category' => $this->getSubCategory->name ?? '',
            'product_category' => $this->name ?? '',
            'items' => ItemGroupApiCollection::collection($this->getItems),
        ];
    }
}
