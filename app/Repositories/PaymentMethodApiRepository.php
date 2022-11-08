<?php

namespace App\Repositories;

use App\Models\PaymentMethod;
use App\Repositories\BaseRepository;

/**
 * Class PaymentMethodRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method PaymentMethodRepository findWithoutFail($id, $columns = ['*'])
 * @method PaymentMethodRepository find($id, $columns = ['*'])
 * @method PaymentMethodRepository first($columns = ['*'])
 */
class PaymentMethodApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
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
        return PaymentMethod::class;
    }

    /**
     * Create a  PaymentMethod
     *
     * @param Request $request
     *
     * @return PaymentMethod
     */
    public function createPaymentMethod($request)
    {
        $input = collect($request->all());
        $paymentMethod = PaymentMethod::create($input->only($request->fillable('paymentMethods'))->all());
        return $paymentMethod;
    }

    /**
     * Update the PaymentMethod
     *
     * @param Request $request
     *
     * @return PaymentMethod
     */

    public function updatePaymentMethod($id, $request)
    {

        $input = collect($request->all());
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update($input->only($request->fillable('paymentMethods'))->all());
        return $paymentMethod;
    }
}
