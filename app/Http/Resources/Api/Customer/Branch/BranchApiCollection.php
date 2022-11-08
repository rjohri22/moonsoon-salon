<?php

namespace App\Http\Resources\Api\Customer\Branch;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Customer\Service\ServiceApiCollection;
class BranchApiCollection extends JsonResource
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
            'id'      => $this->id ?? "",
            'name'      => $this->name ?? "",
            'city'      => $this->city ?? "",
            'state'     => $this->state ?? "",
            'pincode'   => $this->pincode ?? "",
            'lat'       => $this->lat ?? "",
            'lng'       => $this->lng ?? "",
            'address'   => $this->address ?? "",
            'image'     => $this->media->getUrl() ?? "",
            'description'  => $this->description ?? "",
            'services'  => $this->services ? ServiceApiCollection::collection($this->services) :[],
            //'banner' => ($this->branch_banner == null || $this->branch_banner == '') ? '' : 'https://app.monsoonsalon.com/web_assets/images/Branchs/' . $this->branch_banner,
        ];
    }
}
