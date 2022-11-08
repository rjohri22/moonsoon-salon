<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\Wishlist;
use App\Repositories\BaseRepository;

/**
 * Class WishlistRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method WishlistRepository findWithoutFail($id, $columns = ['*'])
 * @method WishlistRepository find($id, $columns = ['*'])
 * @method WishlistRepository first($columns = ['*'])
 */
class WishlistApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
       
        'item_id',
        'user_id',
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
        return Wishlist::class;
    }

    /**
     * Create a  Wishlist
     *
     * @param Request $request
     *
     * @return Wishlist
     */
    public function createWishlist($request)
    {
        $input = collect($request->all());
        $wishlist = Wishlist::create($input->only($request->fillable('wishlists'))->all());
        return $wishlist;
    }

    public function addToWishlist($request)
    {
        $userId = $request->user_id ?? \Helper::getUserId();
        $item = Item::find($request->item_id);
        $data = Wishlist::where([
            'user_id' => $userId,
            'item_id' => $request->item_id
        ])->first();

        if (empty($data)) {
            $data = Wishlist::create([
                'user_id' => $userId,
                'item_id' => $request->item_id,
                'discount' => $item->discount,
                'discount_type' => $item->discount_type,
                'price' => $item->price,
            ]);
        }
        return $data;
    }

    /**
     * Remove the Wishlist
     *
     * @param Request $request
     *
     * @return Wishlist
     */

    public function removeWishlist($request)
    {
        $userId = $request->user_id ?? \Helper::getUserId();
        $updatedQnty = 0;
        $type = $request->type;
        $item = null;
        $item = Item::find($request->item_id);

        // return $itemVariant[0]->qty;
        $data   = Wishlist::where([
            'user_id' => $userId,
            'item_id' => $request->item_id,
            // 'shop_id' => $request->shop_id
        ])->first();

        $data->delete();


        return $data;
    }
}
