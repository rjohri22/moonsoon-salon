<?php

namespace App\Repositories;

use App\Models\ShopPaymentSetting;
use App\Repositories\BaseRepository;

/**
 * Class ShopPaymentSettingRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ShopPaymentSettingRepository findWithoutFail($id, $columns = ['*'])
 * @method ShopPaymentSettingRepository find($id, $columns = ['*'])
 * @method ShopPaymentSettingRepository first($columns = ['*'])
 */
class ShopPaymentSettingApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'shop_id',
        'paytm_no',
        'paytm_qrcode',
        'phonepe_no',
        'phonepe_qrcode',
        'googlepay_no',
        'googlepay_qrcode',
        'whatsapp_no',
        'whatsapp_qrcode',
        'account_no',
        'ifsc_code',
        'bank_name',
        'account_holder',
        'account_type',
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
        return ShopPaymentSetting::class;
    }

    /**
     * Create a  ShopPaymentSetting
     *
     * @param Request $request
     *
     * @return ShopPaymentSetting
     */
    public function createShopPaymentSetting($request)
    {
        $input = collect($request->all());
        $shopPaymentSetting = ShopPaymentSetting::create($input->only($request->fillable('ShopPaymentSettings'))->all());
        return $shopPaymentSetting;
    }

    /**
     * Update the ShopPaymentSetting
     *
     * @param Request $request
     *
     * @return ShopPaymentSetting
     */

    public function updateShopPaymentSetting($id, $request)
    {

        $input = collect($request->all());
        $shopPaymentSetting = ShopPaymentSetting::findOrFail($id);
        $shopPaymentSetting->update($input->only($request->fillable('ShopPaymentSettings'))->all());

        return $shopPaymentSetting;
    }
}
