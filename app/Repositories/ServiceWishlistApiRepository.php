<?php

namespace App\Repositories;

use App\Models\Service;
use App\Models\ServiceWishlist;
use App\Repositories\BaseRepository;

/**
 * Class ServiceWishlistRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ServiceWishlistRepository findWithoutFail($id, $columns = ['*'])
 * @method ServiceWishlistRepository find($id, $columns = ['*'])
 * @method ServiceWishlistRepository first($columns = ['*'])
 */
class ServiceWishlistApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
       
        'service_id',
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
        return ServiceWishlist::class;
    }

    /**
     * Create a  ServiceWishlist
     *
     * @param Request $request
     *
     * @return ServiceWishlist
     */
    public function createServiceWishlist($request)
    {
        $input = collect($request->all());
        $serviceWishlist = ServiceWishlist::create($input->only($request->fillable('ServiceWishlists'))->all());
        return $serviceWishlist;
    }

    public function addToServiceWishlist($request)
    {
        $userId = $request->user_id ?? \Helper::getUserId();
        $service = Service::find($request->service_id);
        $data = ServiceWishlist::where([
            'user_id' => $userId,
            'service_id' => $request->service_id
        ])->first();

        if (empty($data)) {
            $data = ServiceWishlist::create([
                'user_id' => $userId,
                'service_id' => $request->service_id,
                'discount' => $service->discount,
                'discount_type' => $service->discount_type,
                'price' => $service->price,
            ]);
        }
        return $data;
    }

    /**
     * Remove the ServiceWishlist
     *
     * @param Request $request
     *
     * @return ServiceWishlist
     */

    public function removeServiceWishlist($request)
    {
        $userId = $request->user_id ?? \Helper::getUserId();
        $updatedQnty = 0;
        $type = $request->type;
        $service = null;
        $service = Service::find($request->service_id);

        // return $ServiceVariant[0]->qty;
        $data   = ServiceWishlist::where([
            'user_id' => $userId,
            'service_id' => $request->service_id,
            // 'shop_id' => $request->shop_id
        ])->first();

        $data->delete();


        return $data;
    }
}
