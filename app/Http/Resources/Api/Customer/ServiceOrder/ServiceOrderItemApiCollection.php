<?php

namespace App\Http\Resources\Api\Customer\ServiceOrder;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Utils\Helper;

class ServiceOrderItemApiCollection extends JsonResource
{
    use HelperTrait;
    
    /**@keyword manager-areas-resource
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
      //$discountedPrice = \Helper::getDiscountedPrice($this->rate, $this->discount, $this->discount_type);

       return [
            'id'        => $this->id,
            'service_id'   => $this->service_id,
            'service_name' => $this->service_name ?? "",
            'description' =>  $this->description ?? "",
            'service_status' => $this->status ?? "",
            // 'image'     => $this->getserviceImage($this->food_id),
            'service_date'  => $this->service_date,
            'service_date_display'  => !empty($this->service_date) ? Helper::formatDateTime($this->service_date,1) : "" ,
            'service_time'  => $this->service_time,
            'service_time_display'  => !empty($this->service_time) ? Helper::formatDateTime($this->service_time,8) : "" ,
            'rate'      => Helper::getDisplayAmount($this->rate),
            'sale_price'=> Helper::getDisplayAmount($this->service_price),
            'rate_invoice'      => $this->rate,
            'is_discounted' => $this->discount > 0 ? true : false,
            'discount'  => !empty($this->discount) ? $this->discount : 0,
            'discount_type' => $this->discount_type ?? "",
            'sub_total' => Helper::getDisplayAmount($this->quantity * $this->rate),
            'sub_total_invoice' => $this->quantity * $this->rate,
            'total'     => Helper::getDisplayAmount($this->total),
            'total_display'     => Helper::getDisplayAmount($this->total),
            'image'               => !empty($this->service->media) ? $this->service->media->getUrl() : "",
            'file_list'                 => !empty($this->service->media) ? ["0"=>['name'=>$this->service->media->file_name,'url'=>$this->service->media->getUrl()]] : [],

        ];
       
    }
   
}
