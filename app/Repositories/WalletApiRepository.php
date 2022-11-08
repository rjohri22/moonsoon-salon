<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Models\WalletVariant;
use App\Models\Media;
use App\Repositories\BaseRepository;

/**
 * Class WalletRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method WalletRepository findWithoutFail($id, $columns = ['*'])
 * @method WalletRepository find($id, $columns = ['*'])
 * @method WalletRepository first($columns = ['*'])
 */
class WalletApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'amount',
        'status',
       
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
        return Wallet::class;
    }

    /**
     * Create a  Wallet
     *
     * @param Request $request
     *
     * @return Wallet
     */
    public function createWallet($request)
    {
        $input = collect($request->all());
        $wallet = Wallet::create($input->only($request->fillable('Wallets'))->all());
        
        return $wallet;
    }

    /**
     * Update the Wallet
     *
     * @param Request $request
     *
     * @return Wallet
     */

    public function updateWallet($id, $request)
    {

        $input = collect($request->all());
        $wallet = Wallet::findOrFail($id);
        $wallet->update($input->only($request->fillable('Wallets'))->all());
        
        return $wallet;
    }

}
