<?php

namespace App\Http\Resources\Admin\Order;

use App\Traits\HelperTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Utils\Helper;

class OrderItemApiCollection extends JsonResource
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
            'item_id'   => $this->item_id,
            'item_name' => $this->item_name ?? "",
            'description' =>  $this->description ?? "",
            'item_status' => $this->status ?? "",
            // 'image'     => $this->getItemImage($this->food_id),
            'quantity'  => $this->quantity,
            'rate'      => Helper::getDisplayAmount($this->rate),
            'sale_price'=> Helper::getDisplayAmount($this->item_price),
            'rate_invoice'      => $this->rate,
            'is_discounted' => $this->discount > 0 ? true : false,
            'discount'  => !empty($this->discount) ? $this->discount : 0,
            'discount_type' => $this->discount_type ?? "",
            'sub_total' => Helper::getDisplayAmount($this->quantity * $this->rate),
            'sub_total_invoice' => $this->quantity * $this->rate,
            'total'     => Helper::getDisplayAmount($this->total),
            'image'               => !empty($this->item->media) ? $this->item->media->getUrl() : "",
            'file_list'                 => !empty($this->item->media) ? ["0"=>['name'=>$this->item->media->file_name,'url'=>$this->item->media->getUrl()]] : [],

        ];
       
    }
   
}
