<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class UserApiRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method UserApiRepository findWithoutFail($id, $columns = ['*'])
 * @method UserApiRepository find($id, $columns = ['*'])
 * @method UserApiRepository first($columns = ['*'])
 */
class UserApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return User::class;
    }

    /**
     * Create a  User
     *
     * @param Request $request
     *
     * @return User
     */
    public function createUser($request)
    {
        $input = collect($request->all());
       // $input['user_id'] = \Helper::getUserId();
        $User = User::create($input->only($request->fillable('user'))->all());
        return $User;
    }

    /**
     * Update the User
     *
     * @param Request $request
     *
     * @return User
     */

    public function updateUser($id, $request)
    {

        $input = collect($request->all());
        $input['user_id'] = \Helper::getUserId();
        $User = User::findOrFail($id);
        $User->update($input->only($request->fillable('user'))->all());

        return $User;
    }
}
