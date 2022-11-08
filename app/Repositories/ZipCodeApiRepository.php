<?php

namespace App\Repositories;

use App\Models\ZipCode;
use App\Repositories\BaseRepository;


/**
 * Class ZipCodeRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ZipCodeRepository findWithoutFail($id, $columns = ['*'])
 * @method ZipCodeRepository find($id, $columns = ['*'])
 * @method ZipCodeRepository first($columns = ['*'])
 */
class ZipCodeApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'zipcode',
        'city',
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
        return ZipCode::class;
    }

    /**
     * Create a  ZipCode
     *
     * @param Request $request
     *
     * @return ZipCode
     */
    public function createZipCode($request)
    {
        $input = collect($request->all());
        $zipCode = ZipCode::create($input->only($request->fillable('ZipCodes'))->all());
        return $zipCode;
    }

    /**
     * Update the ZipCode
     *
     * @param Request $request
     *
     * @return ZipCode
     */

    public function updateZipCode($id, $request)
    {

        $input = collect($request->all());
        $zipCode = ZipCode::findOrFail($id);
        $zipCode->update($input->only($request->fillable('ZipCodes'))->all());
        return $zipCode;
    }


}
