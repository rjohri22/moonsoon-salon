<?php

namespace App\Http\Resources\Admin\Package;

use App\Traits\HelperTrait;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Package\PackageDetailsApiCollection;
class PackageApiCollection extends JsonResource
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
        
        $userId =  \Helper::getUserId();

        return [
            'id'                                => $this->id,
            'name'                              => $this->name,
            // Amount
            'discount'                   => $this->discount,
            'discount_type'                     => $this->discount_type,
            'packages_type'                     => $this->packages_type,
            // 'discount_type_text'                => !empty$this->discountType->discount_type,
            'price'                             => $this->price,
            'price_display'                     => Helper::getDisplayAmount($this->price),
            'package_price'                        => Helper::getSalePrice($this->price, $this->discount_amount, $this->discount_type),
            'package_price_display'                => Helper::getSalePriceDisplay(Helper::getSalePrice($this->price, $this->discount_amount, $this->discount_type), $this->discount_type),
            'package_image'                       => !empty($this->media) ? $this->media->getUrl() : "" ,
            //Extra Info
            'description'                       => $this->description ?? '',
           
            'package_detail'                   => PackageDetailsApiCollection::collection($this->packageDetail),
        ];
    }
}
