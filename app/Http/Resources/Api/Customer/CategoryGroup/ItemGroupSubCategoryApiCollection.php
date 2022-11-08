<?php

namespace App\Http\Resources\Api\Customer\CategoryGroup;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Api\Customer\CategoryGroup\ProductGroupCategoryApiCollection;
class ItemGroupSubCategoryApiCollection extends JsonResource
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
            'sub_category_description' => $this->description ?? "",
            'product_categories' => ProductGroupCategoryApiCollection::collection($this->getProductCategory),
            'items' => ItemGroupApiCollection::collection($this->getItems),

        ];
    }
}
