<?php

namespace App\Http\Resources\Api\Customer\Profile;

use App\Models\User;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetaiApiCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        $user = User::find(\Helper::getUserId());
        $this->profile_photo_url = "https://ui-avatars.com/api/?name=" . $user->first_name . "+" . $user->last_name . "&color=7F9CF5&background=EBF4FF";
        return [
            'first_name' => $user->first_name ?? '',
            'last_name' => $user->last_name ?? '',
            'mobile' => $user->mobile ?? '',
            'email' => $user->email ?? '',
            'gender' =>Helper::humanStringFormat(\Helper::getGender($user->gender)) ?? '',
            'marital_status' => $this->marital_status ,
            'marital_status_display' => $this->maritalStatus ? $this->maritalStatus->marital_status :'',
            'dob' => $this->dob ?? '',
            'anniversary' => $this->anniversary ?? '',
            'hair_length' => $this->hair_length ?? '',
            'hair_length_display' => !empty($this->hairLenght) ? $this->hairLenght->hair_length : '',
            'hair_type' => $this->hair_type ?? '',
            'hair_type_display' => $this->hairType ?  $this->hairType->marital_status :'',
            'skin_type' => $this->skin_type ?? '',
            'skin_type_display' => $this->skinType ? $this->skinType->skin_type :'',
            'allergies' => $this->allergies ?? '',
            "profile_photo" =>  !empty($user->media) ? $user->media->getUrl() : $this->profile_photo_url, //$user->profile_photo ?? $this->profile_photo_url,
        ];
    }
}
