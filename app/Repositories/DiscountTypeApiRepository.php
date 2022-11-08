<?php

namespace App\Repositories;

use App\Models\DiscountType;
use App\Repositories\BaseRepository;

/**
 * Class DiscountTypeApiRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method DiscountTypeApiRepository findWithoutFail($id, $columns = ['*'])
 * @method DiscountTypeApiRepository find($id, $columns = ['*'])
 * @method DiscountTypeApiRepository first($columns = ['*'])
 */
class DiscountTypeApiRepository extends BaseRepository
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
        return DiscountType::class;
    }

    /**
     * Create a  DiscountType
     *
     * @param Request $request
     *
     * @return DiscountType
     */
    public function createDiscountType($request)
    {
        $input = collect($request->all());
        $discountType = DiscountType::create($input->only($request->fillable('DiscountTypes'))->all());
        return $discountType;
    }

    /**
     * Update the DiscountType
     *
     * @param Request $request
     *
     * @return DiscountType
     */

    public function updateDiscountType($id, $request)
    {

        $input = collect($request->all());
        $discountType = DiscountType::findOrFail($id);
        $discountType->update($input->only($request->fillable('DiscountTypes'))->all());

        return $discountType;
    }
}
