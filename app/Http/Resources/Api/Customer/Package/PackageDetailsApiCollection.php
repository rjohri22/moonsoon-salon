<?php

namespace App\Http\Resources\Api\Customer\Package;

use App\Traits\HelperTrait;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Service\ServiceApiCollection;
use App\Http\Resources\Admin\Item\ItemApiCollection;
class PackageDetailsApiCollection extends JsonResource
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
        if($this->table_type=="service")
        {
            $items = new ServiceApiCollection($this->service);
        }else
        {
            $items = new ItemApiCollection($this->item);
        }

        return [
            'id'                                => $this->id,
            'package_id'                              => $this->package_id,
            // Amount
            'table_id'                   => $this->table_id,
            'table_type'                   => $this->table_type,
            'items'                   => $items,
            'status'                     => $this->status,
        ];
    }
}
