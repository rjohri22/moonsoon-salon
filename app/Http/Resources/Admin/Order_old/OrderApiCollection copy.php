<?php

namespace App\Http\Resources\Api\Customer\Order;

use App\Traits\HelperTrait;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderApiCollection extends JsonResource
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
        $orderItems = $this->orderItems;
        \DNS2D::getBarcodePNGPath($this->id, 'PDF417');
        $barcode =  url('storage/barcodes/'.$this->id.'pdf417.png');
        $barcodeInvoice =  url('storage/barcodes/'.$this->id.'pdf417.png');
       
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'barcode'      => $barcode,
            'barcode_invoice'      => $barcodeInvoice,
    //   $feedback =  $this->getFeedback($this->id) ;
            'order_no' => !empty($this->order_no) ? '# '.$this->order_no : '',
            'order_id_no' => !empty($this->order_no) ? $this->order_no : '',
            'user_name'    => $this->user->name ?? "",
            'contact_no'    =>!empty($this->user) ? (string) $this->user->mobile :  "",
            'user_email'    =>!empty($this->user) ? (string) $this->user->email :  "",
            'order_date'   => \Helper::formatDateTime($this->created_at, 13),
            'order_date_time_display'   => \Helper::formatDateTime($this->created_at, 13),
            // 'delivery_address' => !empty($this->delivery_address) ? $this->delivery_address : [],
            'delivery_address' => !empty($this->delivery_address) ? json_decode($this->delivery_address) : (object)[],
            // 'delivery_boy_id' => $this->delivery_boy_id ?? NULL,
            // 'delivery_boy_name' => $this->deliveryBoy ? $this->deliveryBoy->name : "",
            // 'delivery_date' => !empty($this->delivery_date) ? \Helper::formatDateTime($this->delivery_date) : "",
            // 'delivery_time' => !empty($this->delivery_time) ? $this->delivery_time : "",
            'order_items'=> !empty($orderItems) ? OrderItemApiCollection::collection($orderItems) : (object)[],
            'savings'=>!empty($orderItems) ?  \Helper::twoDecimalPoint($this->getSavingsAmount($orderItems)) : [],
            'txn_id'=> $this->txn_id ?? NULL,
            'txn_status'=> $this->txn_status ?? "",
            'payment_mode' => $this->payment_mode,
         
            'payment_mode_display' => !empty($this->payment_mode) ? \Helper::humanStringFormat($this->payment_mode) :"",
            'status_display' => $this->status,
            'is_completed' => ($this->status == "delivered") ? true : false,
            'is_feedback' => !empty($feedback) ? true : false,
            'rating' => !empty($feedback) ? $feedback->rating : "",
            'feedback' => !empty($feedback) ? $feedback->feedback : "",
            'total_amount' => Helper::getDisplayAmount($this->total_amount + ((float) $this->discount_amount)),
            'sub_total' => Helper::getDisplayAmount($this->sub_total),
            'discount_amount' => Helper::getDisplayAmount($this->discount_amount),
            'delivery_charge' => Helper::getDisplayAmount($this->delivery_charge),
            'cgst_amount' => Helper::getDisplayAmount($this->cgst_amount),
            'sgst_amount' => Helper::getDisplayAmount($this->sgst_amount),
            'igst_amount' => Helper::getDisplayAmount($this->igst_amount),
            'cgst_amount_invoice' => \Helper::twoDecimalPoint($this->cgst_amount),
            'sgst_amount_invoice' => \Helper::twoDecimalPoint($this->sgst_amount),
            'igst_amount_invoice' => \Helper::twoDecimalPoint($this->igst_amount),
            'ordered_quantity'=> !empty($orderItems) ? count($orderItems) : 0,
            'delivery_charge_invoice' => empty($this->delivery_charge) ? (string) $this->delivery_charge : "0",
            'discount_amount_invoice' =>$this->discount_amount,
            'sub_total_invoice' => $this->sub_total,
            'total_amount_invoice' => $this->total_amount,

        ];
    }
}
