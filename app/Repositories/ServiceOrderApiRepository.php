<?php

namespace App\Repositories;

use App\Models\ServiceOrder;
use App\Repositories\BaseRepository;
use App\Models\Address;
use App\Models\Cart;
use App\Models\ServiceOrderItem;
use App\Models\Service;
use App\Models\ServiceCart;
use App\Utils\Helper;
use Illuminate\Support\Facades\DB;

/**
 * Class ServiceOrderRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ServiceOrderRepository findWithoutFail($id, $columns = ['*'])
 * @method ServiceOrderRepository find($id, $columns = ['*'])
 * @method ServiceOrderRepository first($columns = ['*'])
 */
class ServiceOrderApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'service_order_no',
        'tax',
        'txn_id',
        'txn_status',
        'payment_mode',
        'sub_total',
        'discount_amount',
        'sgst_amount',
        'igst_amount',
        'total_amount',
        'payout_date',
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
        return ServiceOrder::class;
    }

    /**
     * Create a  ServiceOrder
     *
     * @param Request $request
     *
     * @return ServiceOrder
     */
    public function createServiceOrder($request)
    {
        $input = collect($request->all());
        $serviceOrder = ServiceOrder::create($input->only($request->fillable('serviceOrders'))->all());
        return $serviceOrder;
    }

    /**
     * Update the ServiceOrder
     *
     * @param Request $request
     *
     * @return ServiceOrder
     */

    public function updateServiceOrder($id, $request)
    {

        $input = collect($request->all());
        $serviceOrder = ServiceOrder::findOrFail($id);
        $serviceOrder->update($input->only($request->fillable('serviceOrders'))->all());

        return $serviceOrder;
    }
       /**
     * Create a  ServiceOrder.
     *
     * @param Request $request
     *
     * @return ServiceOrder
     */
    public function placeServiceOrder($request)
    {
        $userId = $request->user_id ?? Helper::getUserId();
        $payment_mode = $request->payment_mode;
        $is_self_pickup = 1;
        $address = null;
        if(!empty($request->delivery_address_id)){
            $address = Address::find($request->delivery_address_id);
            if(!empty($address)){
                $is_self_pickup = 0;
            }
        }
        $serviceOrders = [];
        // return $moduleIds;
        $deliveryCharge = 0;

            // try{
                // DB::beginTransaction();
                $carts = Helper::viewServiceCart($userId);
                // return $carts;
                $cartData = Helper::getServiceCartTotalDetails($userId);
                $total   = $cartData['total'];
                $subTotal  = $cartData['subTotal'];
                $discount = $cartData['discount'];
                $service = null;
		        // $delivery_code = $this->genepriceDeliveryCode();

                $serviceOrderCreateData = [
                    'user_id' => $userId,
                    'txn_id' => $request->txn_id ?? "",
                    'txn_status' => $request->txn_status ?? "pending",
                    'payment_mode' => $payment_mode,
                    'status' => $request->txn_id ? "Booked" : "pending",
                    'sub_total'    => $subTotal,
                    'discount_amount'=> $discount,
                    'total_amount' => $total,
                    'cgst_amount'  => Helper::twoDecimalPoint(0),
                    'sgst_amount'  => Helper::twoDecimalPoint(0),
                    'igst_amount'  => Helper::twoDecimalPoint(0),
                ];
                // if($serviceOrderCreateData['status'] == 'delivered'){
                //     $serviceOrderCreateData['delivery_date'] = Helper::currentDateTime();
                // }
                $serviceOrder = ServiceOrder::create($serviceOrderCreateData);

                $deliveryCharge = 0;
                $total_amount = $serviceOrder->total_amount;


                $serviceOrder->update(['service_order_no' => $this->getServiceOrderNo($serviceOrder->id), 'total_amount' => $total_amount]);
                $serviceOrders[] = $serviceOrder;
                // return $serviceOrder;
                foreach ($carts as $key => $value) {
                    // echo "in";
                   $service = Service::find($value->service_id) ;
                    if(!empty($service)){
                        $discountedPrice = Helper::getSalePrice($value->price, $value->discount, $value->discount_type);
                        $serviceOrderItemDiscount = Helper::twoDecimalPoint(0);
                        $serviceOrderItemTotal = 1 * $discountedPrice;
                        $service_price = Helper::twoDecimalPoint($value->price);

                        if(!empty($value->discount_type)){
                          $serviceOrderItemDiscount = Helper::twoDecimalPoint((1 * $value->price) - $serviceOrderItemTotal);
                        }

                        ServiceOrderItem::create([
                            'user_id' => $userId,
                            'service_order_id' => $serviceOrder->id,
                            'service_id' => $value->service_id,
                            'service_name'=> $service->name,
                            'service_date'=> $value->service_date,
                            'service_time'=> $value->service_time,
                            'description'=> $service->description,
                            'status' => 'active',
                            'service_price' => $service_price,
                            'rate'    => $value->price,
                            'discount' => $serviceOrderItemDiscount,
                            'discount_type' => $value->discount_type,
                            'total'  =>  $serviceOrderItemTotal,
                        ]);
                    }

                    ServiceCart::where('id', $value->id)->delete();


                }
                // DB::commit();
            // }catch(\Exception $e){
            //     DB::rollBack();
            //     throw new $e;
            // }

        return $serviceOrders;
    }

}
