<?php

namespace App\Http\Resources\Api\Customer\Offer;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\HelperTrait;
use App\Utils\Helper;
use App\Http\Resources\Api\Customer\Item\ItemApiCollection;
use App\Models\Item;

class OfferApiCollection extends JsonResource
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
        $items = Item::getActive()->get();
        return [
            'id' => $this->id ?? '',
            'title' => $this->title ?? '',
            'code' => $this->code ?? '',
            'date_valid_from' => $this->date_valid_from ?? '',
            'date_valid_to' => $this->date_valid_to ?? '',
            'time_valid_from' => $this->time_valid_from ?? '',
            'time_valid_to' => $this->time_valid_to ?? '',
            'days' => !empty($this->days) ? json_decode($this->days) : '',
            'offer_images' => !empty($this->media) ? $this->media->getUrl() : '',
            'amount' => $this->amount ?? '',
            'amount_type' => $this->amount_type ?? '',
            'amount_type_text' => !empty($this->discountType) ? $this->discountType->discount_type : "",
            'description' => $this->description ?? '',
            'items' => ItemApiCollection::collection($items)
        ];
    }
}
