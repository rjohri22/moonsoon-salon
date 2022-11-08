<?php

namespace App\Http\Resources\Api\Customer\ServiceOrder;

use App\Traits\HelperTrait;
use App\Utils\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceOrderApiCollection extends JsonResource
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
        $serviceOrderItems = $this->serviceOrderItems;
        \DNS2D::getBarcodePNGPath($this->id, 'PDF417');
        $barcode =  url('storage/barcodes/'.$this->id.'pdf417.png');
        $barcodeInvoice =  url('storage/barcodes/'.$this->id.'pdf417.png');
       
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'barcode'      => $barcode,
            'barcode_invoice'      => $barcodeInvoice,
    //   $feedback =  $this->getFeedback($this->id) ;
            'service_order_no' => !empty($this->service_order_no) ? '# '.$this->service_order_no : '',
            'service_order_id_no' => !empty($this->service_order_no) ? $this->service_order_no : '',
            'user_name'    => $this->user->first_name ?? "",
            'contact_no'    =>!empty($this->user) ? (string) $this->user->mobile :  "",
            'user_email'    =>!empty($this->user) ? (string) $this->user->email :  "",
            'booking_order_date'   => \Helper::formatDateTime($this->created_at, 13),
            'booking_order_date_time_display'   => \Helper::formatDateTime($this->created_at, 13),
            'service_order_items'=> !empty($serviceOrderItems) ? ServiceOrderItemApiCollection::collection($serviceOrderItems) : (object)[],
            'savings'=>!empty($serviceOrderItems) ?  \Helper::twoDecimalPoint($this->getSavingsAmount($serviceOrderItems)) : [],
            'txn_id'=> $this->txn_id ?? NULL,
            'txn_status'=> $this->txn_status ?? "",
            'payment_mode' => $this->payment_mode,
            
            'payment_mode_display' => !empty($this->payment_mode) ? \Helper::humanStringFormat($this->payment_mode) :"",
            'status_display' => !empty($this->status) ? \Helper::humanStringFormat($this->status) :"",
            'is_completed' => ($this->status == "delivered") ? true : false,
            'is_feedback' => !empty($feedback) ? true : false,
            'rating' => !empty($feedback) ? $feedback->rating : "",
            'feedback' => !empty($feedback) ? $feedback->feedback : "",
            'total_amount' => Helper::getDisplayAmount($this->total_amount + ((float) $this->discount_amount)),
            'sub_total' => Helper::getDisplayAmount($this->sub_total),
            'discount_amount' => Helper::getDisplayAmount($this->discount_amount),
            'cgst_amount' => Helper::getDisplayAmount($this->cgst_amount),
            'sgst_amount' => Helper::getDisplayAmount($this->sgst_amount),
            'igst_amount' => Helper::getDisplayAmount($this->igst_amount),
            'cgst_amount_invoice' => \Helper::twoDecimalPoint($this->cgst_amount),
            'sgst_amount_invoice' => \Helper::twoDecimalPoint($this->sgst_amount),
            'igst_amount_invoice' => \Helper::twoDecimalPoint($this->igst_amount),
            'service_ordered_quantity'=> !empty($serviceOrderItems) ? count($serviceOrderItems) : 0,
            'discount_amount_invoice' =>$this->discount_amount,
            'sub_total_invoice' => $this->sub_total,
            'total_amount_invoice' => $this->total_amount,

        ];
    }
}
