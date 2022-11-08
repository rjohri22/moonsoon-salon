<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Repositories\BaseRepository;

/**
 * Class ShopRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ShopRepository findWithoutFail($id, $columns = ['*'])
 * @method ShopRepository find($id, $columns = ['*'])
 * @method ShopRepository first($columns = ['*'])
 */
class ShopApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'module_id',
        'user_id',
        'name',
        'whatsapp_no',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'description',
        'free_delivery_amount',
        'minimum_delivery_time',
        'total_rating',
        'is_free_delivery',
        'lat',
        'lng',
        'owners',
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
        return Shop::class;
    }

    /**
     * Create a  Shop
     *
     * @param Request $request
     *
     * @return Shop
     */
    public function createShop($request)
    {
        $input = collect($request->all());
        $shop = Shop::create($input->only($request->fillable('shops'))->all());
        return $shop;
    }

    /**
     * Update the Shop
     *
     * @param Request $request
     *
     * @return Shop
     */

    public function updateShop($id, $request)
    {

        $input = collect($request->all());
        $shop = Shop::findOrFail($id);
        $shop->update($input->only($request->fillable('shops'))->all());

        return $shop;
    }
}
