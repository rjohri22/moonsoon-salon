<?php

namespace App\Repositories;

use App\Models\Saloon;
use App\Repositories\BaseRepository;

/**
 * Class SaloonRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method SaloonRepository findWithoutFail($id, $columns = ['*'])
 * @method SaloonRepository find($id, $columns = ['*'])
 * @method SaloonRepository first($columns = ['*'])
 */
class SaloonRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        // 'user_id',
        'name',
        // 'street',
        // 'landmark',
        // 'city',
        // 'state',
        // 'zipcode',
        // 'country',
        // 'contact',
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
        return Saloon::class;
    }

    /**
     * Create a  Saloon
     *
     * @param Request $request
     *
     * @return Saloon
     */
    public function createSaloon($request)
    {
        $input = collect($request->all());
        $saloon = Saloon::create($input->only($request->fillable('saloon'))->all());
        return $saloon;
    }

    /**
     * Update the Saloon
     *
     * @param Request $request
     *
     * @return Saloon
     */
    
    public function updateSaloon($id, $request)
    {
       
        $input = collect($request->all());
        $saloon = Saloon::findOrFail($id);
        $saloon->update($input->only($request->fillable('saloon'))->all());
        
        return $saloon;
    }
    
   

}
