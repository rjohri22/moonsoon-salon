<?php

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserApiCollection extends JsonResource
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
        \DNS2D::getBarcodePNGPath("" . $this->id, 'QRCODE');
        $qrcode =  url('public/storage/barcodes/' . $this->id . 'qrcode.png');
        $profilePhotoUrl = "https://ui-avatars.com/api/?name=" . $this->first_name . "+" . $this->last_name . "&color=7F9CF5&background=EBF4FF";
        return [
            "name" => ($this->first_name . ' ' . $this->last_name) ?? "",
            "qrcode" => $qrcode,
            "email" => $this->email ?? "",
            "profile_photo" => !empty($this->media) ? $this->media->getUrl() : $profilePhotoUrl,
            "mobile" => $this->mobile ?? "",
            'access_token'          => $this->access_token ?? "",

        ];
    }
}
