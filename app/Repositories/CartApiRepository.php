<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Item;
use App\Repositories\BaseRepository;

/**
 * Class CartRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method CartRepository findWithoutFail($id, $columns = ['*'])
 * @method CartRepository find($id, $columns = ['*'])
 * @method CartRepository first($columns = ['*'])
 */
class CartApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        /* 'shop_id',
        'module_id', */
        'item_id',
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
        return Cart::class;
    }

    /**
     * Create a  Cart
     *
     * @param Request $request
     *
     * @return Cart
     */
    public function addToCart($request)
    {
        $userId = $request->user_id ?? \Helper::getUserId();
        $item = null;


        $item = Item::find($request->item_id);
        $item->update(['qty' => $item->qty - 1]);

        $data = Cart::where([
            'user_id' => $userId,
            'item_id' => $request->item_id,
        ])->first();

        if (!empty($data)) {
            $updatedQnty = $data->quantity + 1;
            $data->update(['quantity' => $updatedQnty]);
        } else {
            $data = Cart::create([
                'user_id' => $userId,
                'item_id' => $request->item_id,
                'quantity' => 1,
                'discount' => $item->discount_amount,
                'discount_type' => $item->discount_type,
                'price' => $item->price,
            ]);
        }
        return $data;
    }

    /**
     * Update the Cart
     *
     * @param Request $request
     *
     * @return Cart
     */

    public function updateCart($id, $request)
    {

        $updatedQnty = 0;
        $operation = $request->operation;
        $item = null;
        // $itemVariant= $item->itemVariants[0];

        $data   = Cart::where([
            'id' => $id
        ])->first();

        $item= Item::find($data->item_id);
        // return $item;
        if ($operation == 'plus') {
            $updatedQnty = $data->quantity + 1;
            $item->update(['qty' => $item->qty - 1]);
        } else {
            $updatedQnty = $data->quantity - 1;
            $item->update(['qty' => $item->qty + 1]);
        }
        if ($updatedQnty > 0) {
            $data->update(['quantity' => $updatedQnty]);
            // $data['cart_total'] = \Helper::getTotal($userId);
        } else {
            $data->delete();
            // $data['cart_total'] = \Helper::getTotal($userId);
        }
        return $data;
    }
}
