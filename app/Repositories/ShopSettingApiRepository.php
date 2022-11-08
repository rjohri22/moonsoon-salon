<?php

namespace App\Repositories;

use App\Models\ShopSetting;
use App\Repositories\BaseRepository;
use App\Utils\Helper;

/**
 * Class ShopSettingRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ShopSettingRepository findWithoutFail($id, $columns = ['*'])
 * @method ShopSettingRepository find($id, $columns = ['*'])
 * @method ShopSettingRepository first($columns = ['*'])
 */
class ShopSettingApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

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
        return ShopSetting::class;
    }

    /**
     * Create a  ShopSetting
     *
     * @param Request $request
     *
     * @return ShopSetting
     */
    public function createShopSetting($request)
    {
        $input = collect($request->all());
        $input['updated_by'] = Helper::getUserId();
        $shopSetting = ShopSetting::create($input->only($request->fillable('ShopSettings'))->all());
        return $shopSetting;
    }

    /**
     * Update the ShopSetting
     *
     * @param Request $request
     *
     * @return ShopSetting
     */

    public function updateShopSetting($id, $request)
    {
        $input = collect($request->all());
        $input['updated_by'] = Helper::getUserId();
        $shopSetting = ShopSetting::findOrFail($id);
        $shopSetting->update($input->only($request->fillable('ShopSettings'))->all());
        return $shopSetting;
    }
}
