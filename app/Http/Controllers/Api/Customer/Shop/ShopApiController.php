<?php

namespace App\Http\Controllers\Api\Customer\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ShopApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Shop\ShopApiCollection;
use App\Http\Requests\Api\Customer\Shop\UpdateShopApiRequest;
use App\Http\Requests\Api\Customer\Shop\CreateShopApiRequest;

class ShopApiController extends AppBaseController
{
    protected $shopApiRepository;

    public function __construct(ShopApiRepository $shop)
    {
        $this->shopApiRepository = $shop;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->shopApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->shopApiRepository->paginate($request->limit);

        return $this->sendResponse(['item' => ShopApiCollection::collection($items), 'total' => $items->total()], '');
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
    public function store(CreateShopApiRequest $request)
    {
        $shop =  $this->shopApiRepository->createShop($request);

        return $this->sendResponse(new ShopApiCollection($shop), 'Shop created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $shop = $this->shopApiRepository->findWithoutFail($id);
        if (empty($shop)) {
            return $this->sendError($this->getLangMessages('Sorry! Shop not found', 'Shop'));
        }
        return $this->sendResponse(new ShopApiCollection($shop), 'Shop retrived successfully');
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
    public function update($id, UpdateShopApiRequest $request)
    {
        // return $id;
        $shop = $this->shopApiRepository->findWithoutFail($id);
        if (empty($shop)) {
            return $this->sendError($this->getLangMessages('Sorry! Shop not found', 'Shop'));
        }
        $shop = $this->shopApiRepository->updateShop($id, $request);

        return $this->sendResponse(new ShopApiCollection($shop), 'Shop updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $shop = $this->shopApiRepository->findWithoutFail($id);
        if (empty($shop)) {
            return $this->sendError($this->getLangMessages('Sorry! Shop not found', 'Shop'));
        }

        $shop->delete();
        return $this->sendResponse([], 'Shop deleted successfully');
    }
}
