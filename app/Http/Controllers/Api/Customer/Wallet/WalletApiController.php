<?php

namespace App\Http\Controllers\Api\Customer\Wallet;

use Illuminate\Http\Request;
use App\Repositories\WalletApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Wallet\WalletApiCollection;
use App\Http\Requests\Api\Customer\Wallet\UpdateWalletApiRequest;
use App\Http\Requests\Api\Customer\Wallet\CreateWalletApiRequest;
use App\Models\Brand;
use App\Traits\ActivityLogTrait;
use App\Traits\UploaderTrait;
use App\Http\Criteria\Customer\Wallet\WalletCriteria;
use App\Utils\Helper;
class WalletApiController extends AppBaseController
{
    protected $walletApiRepository;

    use UploaderTrait, ActivityLogTrait;
    public function __construct(WalletApiRepository $wallet)
    {
        $this->walletApiRepository = $wallet;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     *   @OA\Get(
     *     path="/api/wallets",
     *      tags={"Salon App: wallet Listing"},
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *  )
     */




    public function index(Request $request)
    {
        $this->walletApiRepository->pushCriteria(new RequestCriteria($request));

        $wallets = Helper::getWallet();
        // $wallets = $this->walletApiRepository->where('user_id',\Auth::user()->id)->where('status', 'active')->get();

        return $this->sendResponse(new WalletApiCollection($wallets), '');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateWalletApiRequest $request)
    {
        // return $request->all();
        $wallet =  $this->walletApiRepository->createWallet($request);
        // return $wallet;
        $this->walletApiRepository->uploadFile($request, $wallet);
        $this->saveActivity($request, "Wallet", "list", $wallet);
        return $this->sendResponse(new WalletApiCollection($wallet), 'Wallet created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $wallet = $this->walletApiRepository->findWithoutFail($id);
        if (empty($wallet)) {
            return $this->sendError($this->getLangMessages('Sorry! Wallet not found', 'Wallet'));
        }
        $this->saveActivity($request, "Wallet", "show", $wallet);
        $related_Wallets = $this->walletApiRepository->where('product_category_id', $wallet->product_category_id)->where('id','!=',$wallet->id)->get();
        return $this->sendResponse(['Wallet_detail' => new WalletApiCollection($wallet), 'related_Wallets' => WalletApiCollection::collection($related_Wallets)], 'Wallet retrived successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateWalletApiRequest $request)
    {
        // return $id;
        $wallet = $this->walletApiRepository->findWithoutFail($id);
        if (empty($wallet)) {
            return $this->sendError($this->getLangMessages('Sorry! Wallet not found', 'Wallet'));
        }
        $wallet = $this->walletApiRepository->updateWallet($id, $request);
       
        $this->saveActivity($request, "Wallet", "update", $wallet);
        $this->walletApiRepository->uploadFile($request, $wallet);
        return $this->sendResponse(new WalletApiCollection($wallet), 'Wallet updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $wallet = $this->walletApiRepository->findWithoutFail($id);
        if (empty($wallet)) {
            return $this->sendError($this->getLangMessages('Sorry! Wallet not found', 'Wallet'));
        }
        $wallet->delete();
        $this->saveActivity($request, "Wallet", "delete", $wallet);
        return $this->sendResponse([], 'Wallet deleted successfully');
    }

    // public function searchBy($section = null, $id = null)
    // {
    //     $section = $section === null ? "" : $section;
    //     $id = $id === null ? "" : $id;
    //     $data = null;
    //     /*if($section == 'brand'){
    //         $data=$this->WalletApiRepository->findWithoutFail()
    //     }*/
    //     return $section . $id;
    // }
}
