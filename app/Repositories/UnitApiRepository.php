<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Repositories\BaseRepository;

/**
 * Class UnitApiRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method UnitApiRepository findWithoutFail($id, $columns = ['*'])
 * @method UnitApiRepository find($id, $columns = ['*'])
 * @method UnitApiRepository first($columns = ['*'])
 */
class UnitApiRepository extends BaseRepository
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
        return Unit::class;
    }

    /**
     * Create a  Unit
     *
     * @param Request $request
     *
     * @return Unit
     */
    public function createUnit($request)
    {
        $input = collect($request->all());
        $unit = Unit::create($input->only($request->fillable('Units'))->all());
        return $unit;
    }

    /**
     * Update the Unit
     *
     * @param Request $request
     *
     * @return Unit
     */

    public function updateUnit($id, $request)
    {

        $input = collect($request->all());
        $unit = Unit::findOrFail($id);
        $unit->update($input->only($request->fillable('Units'))->all());

        return $unit;
    }
}
