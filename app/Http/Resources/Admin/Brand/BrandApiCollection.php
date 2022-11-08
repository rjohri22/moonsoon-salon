<?php

namespace App\Http\Resources\Admin\Brand;

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
            'name' => $this->name ?? "",
            'image' => $this->media->getUrl() ?? "",
            'description'  => $this->description ?? "",
            //'banner' => ($this->brand_banner == null || $this->brand_banner == '') ? '' : 'https://app.monsoonsalon.com/web_assets/images/brands/' . $this->brand_banner,
        ];
    }
}
