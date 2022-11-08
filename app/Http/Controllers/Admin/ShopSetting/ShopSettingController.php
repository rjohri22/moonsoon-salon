<?php

namespace App\Http\Controllers\Admin\ShopSetting;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShopSettings\ShopSettingWebRequest;
use App\Http\Resources\Admin\ShopSettings\ShopSettingWebCollection;
use App\Http\Resources\Api\Customer\ShopSettings\ShopSettingApiCollection;
use App\Repositories\ShopSettingApiRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;

class ShopSettingController extends AppBaseController
{
    protected $shopSettingRepository;
    public function __construct(ShopSettingApiRepository $shopSetting)
    {
        $this->shopSettingRepository = $shopSetting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->shopSettingRepository->pushCriteria(new RequestCriteria($request));
        $shopSettings = $this->shopSettingRepository->paginate($request->limit);
        $datas = ShopSettingWebCollection::collection($shopSettings);
        return view('admin.shopSetting.shop_setting', compact('datas'));
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
    public function store(ShopSettingWebRequest $request)
    {
        if ($request->id) {
            $this->shopSettingRepository->updateShopSetting($request->id, $request);
            $message = "Shop-Setting Updated Successfully..";
        } else {
            $this->shopSettingRepository->createShopSetting($request);
            $message = "Shop-Setting Added Successfully..";
        }
        return redirect('admin/shop-settings')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $shopSetting = $this->shopSettingRepository->findWithoutFail($id);
        if (empty($shopSetting)) {
            return $this->sendError($this->getLangMessages('Sorry! Shop-Setting not found', 'Shop-Setting'));
        }
        //$this->saveActivity($request, "shop-setting", "add", $shopSetting);

        return $this->sendResponse(new ShopSettingWebCollection($shopSetting), 'Shop-Setting retrived successfully');
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shopSetting = $this->shopSettingRepository->findWithoutFail($id);
        if (empty($shopSetting)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Sub Category not found', 'Shop-Setting'));
        }

        $shopSetting->delete();
        $this->sendResponse([], 'Shop-Setting deleted successfully');
        //return ShopSettingWebCollection::collection($shopSetting);
        return redirect('admin/shop-settings');
    }
}
