<?php

namespace App\Http\Controllers\Api\Customer\Shop;

use Illuminate\Http\Request;
use App\Repositories\ShopPaymentSettingApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Shop\ShopPaymentSettingApiCollection;
use App\Http\Requests\Api\Customer\Shop\UpdateShopPaymentSettingApiRequest;
use App\Http\Requests\Api\Customer\Shop\CreateShopPaymentSettingApiRequest;

class ShopPaymentSettingApiController extends AppBaseController
{
    protected $shopPaymentSettingApiRepository;

    public function __construct(ShopPaymentSettingApiRepository $shopPaymentSetting)
    {
        $this->shopPaymentSettingApiRepository = $shopPaymentSetting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->shopPaymentSettingApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->shopPaymentSettingApiRepository->paginate($request->limit);

        return $this->sendResponse(['item' => ShopPaymentSettingApiCollection::collection($items), 'total' => $items->total()], '');
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
    public function store(CreateShopPaymentSettingApiRequest $request)
    {
        $shopPaymentSetting =  $this->shopPaymentSettingApiRepository->createShopPaymentSetting($request);

        return $this->sendResponse(new ShopPaymentSettingApiCollection($shopPaymentSetting), 'Shop Payment Setting created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $shopPaymentSetting = $this->shopPaymentSettingApiRepository->findWithoutFail($id);
        if (empty($shopPaymentSetting)) {
            return $this->sendError($this->getLangMessages('Sorry! Shop Payment Setting not found', 'ShopPaymentSetting'));
        }
        return $this->sendResponse(new ShopPaymentSettingApiCollection($shopPaymentSetting), 'Shop Payment Setting retrived successfully');
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
    public function update($id, UpdateShopPaymentSettingApiRequest $request)
    {
        // return $id;
        $shopPaymentSetting = $this->shopPaymentSettingApiRepository->findWithoutFail($id);
        if (empty($shopPaymentSetting)) {
            return $this->sendError($this->getLangMessages('Sorry! Shop Payment Setting not found', 'ShopPaymentSetting'));
        }
        $shopPaymentSetting = $this->shopPaymentSettingApiRepository->updateShopPaymentSetting($id, $request);

        return $this->sendResponse(new ShopPaymentSettingApiCollection($shopPaymentSetting), 'Shop Payment Setting updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $shopPaymentSetting = $this->shopPaymentSettingApiRepository->findWithoutFail($id);
        if (empty($shopPaymentSetting)) {
            return $this->sendError($this->getLangMessages('Sorry! Shop Payment Setting not found', 'ShopPaymentSetting'));
        }

        $shopPaymentSetting->delete();
        return $this->sendResponse([], 'Shop Payment Setting deleted successfully');
    }
}
