<?php

namespace App\Http\Resources\Admin\Offer;

use App\Utils\Helper;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id ?? '',
            'title' => $this->title ?? '',
            'code' => $this->code ?? '',
            'date_valid_from' => $this->date_valid_from ?? '',
            'date_valid_to' => $this->date_valid_to ?? '',
            'time_valid_from' => $this->time_valid_from ?? '',
            'time_valid_to' => $this->time_valid_to ?? '',
            'table_type' => $this->table_type ?? '',
            'table_id' => $this->table_id ?? '',
            'days' => !empty($this->days) ? json_decode($this->days) : '',
            'offer_images' => !empty($this->media) ? $this->media->getUrl() : '',
            'amount' => $this->amount ?? '',
            'amount_type' => $this->amount_type ?? '',
            'description' => $this->description ?? '',
            'is_slider' => $this->is_slider ?? 0,
        ];
    }
}
