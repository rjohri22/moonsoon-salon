<?php

namespace App\Http\Resources\Api\Customer\Review;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewApiCollection extends JsonResource
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
            'id' => $this->id ?? "",
            'order_id'     => $this->order_id ?? "",
            'user_id'  => $this->user_id ?? NULL,
            'item_id'     => $this->item_id ?? NULL,
            'rating'     => $this->rating ?? NULL,
            'comment'   => !empty($this->comment) ? \Helper::humanStringFormat($this->comment) : "",
        ];
    }
}
