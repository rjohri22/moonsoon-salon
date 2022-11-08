<?php

namespace App\Http\Resources\Api\Customer\CategoryGroup;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Customer\CategoryGroup\ItemGroupSubCategoryApiCollection;
class ItemCategoryGroupApiCollection extends JsonResource
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
            /* 'module_id'     => $this->module_id ?? "",
            'user_id'     => $this->user_id ?? NULL,
            'shop_id' => $this->shop_id ?? "", */
            'id' => $this->id,
            'name'  => $this->name ?? '',
            'sub_categories'  => ItemGroupSubCategoryApiCollection::collection($this->getSubCategory),
            'items' => ItemGroupApiCollection::collection($this->getItems),

            'description'     => $this->description ?? '',
        ];
    }
}
