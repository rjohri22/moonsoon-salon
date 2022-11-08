<?php

namespace App\Http\Resources\Api\Customer\Brand;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandApiCollection extends JsonResource
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
            'name' => $this->name ?? "",
            'description'  => $this->description ?? "",
            'image' => !empty($this->media) ? $this->media->getUrl() : "",

            //'banner' => ($this->brand_banner == null || $this->brand_banner == '') ? '' : 'https://app.monsoonsalon.com/web_assets/images/brands/' . $this->brand_banner,
        ];
    }
}
