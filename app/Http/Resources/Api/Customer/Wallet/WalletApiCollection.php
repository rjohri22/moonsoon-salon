<?php

namespace App\Http\Resources\Api\Customer\Wallet;

use App\Traits\HelperTrait;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletApiCollection extends JsonResource
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
            'id'                                => $this->id,
            'status'                                => $this->status,
            'user_id'                                => $this->user_id,
            'user_name'                                => $this->user ? $this->user->first_name :"",
            'amount'                                => $this->amount,
            'amount_display'                                => Helper::getDisplayAmount($this->amount),
        
        ];
    }
}
