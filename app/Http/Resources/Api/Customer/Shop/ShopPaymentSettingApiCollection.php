<?php

namespace App\Http\Resources\Api\Customer\Shop;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopPaymentSettingApiCollection extends JsonResource
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
            'shop_id' => $this->shop_id ?? "",
            'paytm_no'  => $this->paytm_no ?? NULL,
            'paytm_qrcode'     => $this->paytm_qrcode ?? NULL,
            'phonepe_no'     => $this->phonepe_no ?? NULL,
            'phonepe_qrcode'   => $this->phonepe_qrcode ?? NULL,
            'googlepay_no'     => $this->googlepay_no ?? NULL,
            'googlepay_qrcode'     => $this->googlepay_qrcode ?? NULL,
            'whatsapp_no'     => $this->whatsapp_no ?? NULL,
            'whatsapp_qrcode'     => $this->whatsapp_qrcode ?? NULL,
            'account_no'     => $this->account_no ?? NULL,
            'ifsc_code'     => $this->ifsc_code ?? NULL,
            'bank_name'     => $this->bank_name ?? NULL,
            'account_holder'     => $this->account_holder ?? NULL,
            'account_type'     => $this->account_type ?? NULL,
        ];
    }
}
