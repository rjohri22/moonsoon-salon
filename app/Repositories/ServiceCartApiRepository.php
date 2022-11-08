<?php

namespace App\Repositories;

use App\Models\ServiceCart;
use App\Models\Item;
use App\Models\Service;
use App\Repositories\BaseRepository;

/**
 * Class ServiceCartRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ServiceCartRepository findWithoutFail($id, $columns = ['*'])
 * @method ServiceCartRepository find($id, $columns = ['*'])
 * @method ServiceCartRepository first($columns = ['*'])
 */
class ServiceCartApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        /* 'shop_id',
        'module_id', */
        'service_id',
        'user_id',
        'quantity',
        'discount',
        'discount_type',
        'rate',
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
        return ServiceCart::class;
    }

    /**
     * Create a  ServiceCart
     *
     * @param Request $request
     *
     * @return ServiceCart
     */
    public function addToServiceCart($request)
    {
        $userId = $request->user_id ?? \Helper::getUserId();
        $item = null;
        if(is_string($request->service_ids))
        {
            $serviceIds = explode(",",$request->service_ids);
        }else
        {
            $serviceIds = $request->service_ids;
        }
        foreach($serviceIds as $key => $value)
        {
            $item = Service::find($value);
            $data = ServiceCart::where([
                'user_id' => $userId,
                'service_id' => $value,
            ])->first();
            $data = ServiceCart::create([
                'user_id' => $userId,
                'service_id' => $value,
                // 'quantity' => 1,
                'service_date' => $request->service_date,
                'service_time' => $request->service_time,
                'discount' => $item->discount_amount,
                'discount_type' => $item->discount_type,
                'price' => $item->price,
            ]);
        }
        return $data;
    }

    /**
     * Update the ServiceCart
     *
     * @param Request $request
     *
     * @return ServiceCart
     */

    public function updateServiceCart($id, $request)
    {

        $updatedQnty = 0;
        $operation = $request->operation;
        $item = null;
        // $itemVariant= $item->itemVariants[0];

        $data   = ServiceCart::where([
            'id' => $id
        ])->first();

        $item= Service::find($data->service_id);
        // return $item;
        if ($operation == 'plus') {
            $updatedQnty = $data->quantity + 1;
            // $item->update(['qty' => $item->qty - 1]);
        } else {
            $updatedQnty = $data->quantity - 1;
            // $item->update(['qty' => $item->qty + 1]);
        }
        if ($updatedQnty > 0) {
            $data->update(['quantity' => $updatedQnty]);
            // $data['ServiceCart_total'] = \Helper::getTotal($userId);
        } else {
            $data->delete();
            // $data['ServiceCart_total'] = \Helper::getTotal($userId);
        }
        return $data;
    }
}
