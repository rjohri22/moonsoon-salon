<?php

namespace App\Repositories;

use App\Models\Address;
use App\Repositories\BaseRepository;

/**
 * Class AddressRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method AddressRepository findWithoutFail($id, $columns = ['*'])
 * @method AddressRepository find($id, $columns = ['*'])
 * @method AddressRepository first($columns = ['*'])
 */
class AddressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'name',
        'street',
        'landmark',
        'city',
        'state',
        'zipcode',
        'country',
        'contact',
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
        return Address::class;
    }

    /**
     * Create a  Address
     *
     * @param Request $request
     *
     * @return Address
     */
    public function createAddress($request)
    {
        $input = collect($request->all());
        $address = Address::create($input->only($request->fillable('addresses'))->all());
        return $address;
    }

    /**
     * Update the Address
     *
     * @param Request $request
     *
     * @return Address
     */
    
    public function updateAddress($id, $request)
    {
       
        $input = collect($request->all());
        $address = Address::findOrFail($id);
        $address->update($input->only($request->fillable('addresses'))->all());
        
        return $address;
    }
    
   

}
