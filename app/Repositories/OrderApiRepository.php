<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\BaseRepository;
use App\Models\Address;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Item;
use App\Utils\Helper;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method OrderRepository findWithoutFail($id, $columns = ['*'])
 * @method OrderRepository find($id, $columns = ['*'])
 * @method OrderRepository first($columns = ['*'])
 */
class OrderApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'order_no',
        'tax',
        'txn_id',
        'txn_status',
        'payment_mode',
        'delivery_address',
        'delivery_notes',
        'sub_total',
        'discount_amount',
        'delivery_charge',
        'sgst_amount',
        'igst_amount',
        'total_amount',
        'payout_date',
        'delivery_date',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }

    /**
     * Create a  Order
     *
     * @param Request $request
     *
     * @return Order
     */
    public function createOrder($request)
    {
        $input = collect($request->all());
        $order = Order::create($input->only($request->fillable('orders'))->all());
        return $order;
    }

    /**
     * Update the Order
     *
     * @param Request $request
     *
     * @return Order
     */

    public function updateOrder($id, $request)
    {

        $input = collect($request->all());
        $order = Order::findOrFail($id);
        $order->update($input->only($request->fillable('orders'))->all());

        return $order;
    }
       /**
     * Create a  Order.
     *
     * @param Request $request
     *
     * @return Order
     */
    public function placeOrder($request)
    {
        $userId = $request->user_id ?? Helper::getUserId();
        $payment_mode = $request->payment_mode;
        $coupanCode = $request->coupan_code;
        $is_self_pickup = 1;
        $address = null;
        if(!empty($request->delivery_address_id)){
            $address = Address::find($request->delivery_address_id);
            if(!empty($address)){
                $is_self_pickup = 0;
            }
        }
        $orders = [];
        // return $moduleIds;
        $deliveryCharge = 0;

            try{
                DB::beginTransaction();
                $carts = Helper::viewCart($userId);
                $cartData = Helper::getCartTotalDetails($userId);
                $total   = $cartData['total'];
                $subTotal  = $cartData['subTotal'];
                $discount = $cartData['discount'];
                $item = null;
		        // $delivery_code = $this->genepriceDeliveryCode();
                $discountData = Helper::getDiscountDetails($subTotal,$coupanCode);

                $orderCreateData = [
                    'user_id' => $userId,
                    'txn_id' => $request->txn_id ?? "",
                    'txn_status' => $request->txn_status ?? "pending",
                    'payment_mode' => $payment_mode,
                    'delivery_address' => !empty($address) ? json_encode($address) : null,
                    'status' =>  "pending",
                    'delivery_notes' => $request->delivery_notes,
                    'sub_total'    => $subTotal,
                    'discount_amount'=> $discountData['discount_amount'],
                    'delivery_charge' => $deliveryCharge,
                    'total_amount' => $total,
                    'cgst_amount'  => Helper::twoDecimalPoint(0),
                    'sgst_amount'  => Helper::twoDecimalPoint(0),
                    'igst_amount'  => Helper::twoDecimalPoint(0),
                ];
                // if($orderCreateData['status'] == 'delivered'){
                //     $orderCreateData['delivery_date'] = Helper::currentDateTime();
                // }
                $order = Order::create($orderCreateData);

                $deliveryCharge = 0;
                $total_amount = $order->total_amount;


                $order->update(['order_no' => $this->getOrderNo($order->id), 'total_amount' => $total_amount, 'delivery_charge' => $deliveryCharge]);
                $orders[] = $order;
                // return $order;



                foreach ($carts as $key => $value) {
                   $item = Item::find($value->item_id) ;
                    if(!empty($item)){
                        $discountedPrice = Helper::getSalePrice($value->price, $value->discount, $value->discount_type);
                        $orderItemDiscount = Helper::twoDecimalPoint(0);
                        $orderItemTotal = $value->quantity * $discountedPrice;
                        $item_price = Helper::twoDecimalPoint($value->price);

                        if(!empty($value->discount_type)){
                          $orderItemDiscount = Helper::twoDecimalPoint(($value->quantity * $value->price) - $orderItemTotal);
                        }

                        OrderItem::create([
                            'user_id' => $userId,
                            'order_id' => $order->id,
                            'item_id' => $value->item_id,
                            'item_name'=> $item->name,
                            'description'=> $item->description,
                            'status' => 'active',
                            'quantity' => $value->quantity,
                            'item_price' => $item_price,
                            'rate'    => $value->price,
                            'discount' => $orderItemDiscount,
                            'discount_type' => $value->discount_type,
                            'total'  =>  $orderItemTotal,
                        ]);
                    }

                    Cart::where('id', $value->id)->delete();


                }
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                //throw new $e;
            }

        return $orders;
    }

}
