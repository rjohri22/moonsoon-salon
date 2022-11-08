<?php

namespace App\Http\Controllers\Api\Customer\Wishlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ServiceWishlistApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Wishlist\ServiceWishlistApiCollection;
use App\Http\Requests\Api\Customer\Wishlist\UpdateServiceWishlistApiRequest;
use App\Http\Requests\Api\Customer\Wishlist\CreateServiceWishlistApiRequest;
use App\Repositories\ServiceApiRepository;
use App\Traits\HelperTrait;
use App\Utils\Helper;
class ServiceWishlistApiController extends AppBaseController
{
    use HelperTrait;
    protected $servicewishlistApiRepository;
    protected $serviceApiRepository;

    public function __construct(ServiceWishlistApiRepository $servicewishlist, ServiceApiRepository $itemApi)
    {
        $this->servicewishlistApiRepository = $servicewishlist;
        $this->serviceApiRepository = $itemApi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     /**
     *   @OA\get(
     *     path="/api/service-wishlists",
     *      tags={"get service Wish list"},
     *       
     *      
     *       
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       )
     * )
     */
    public function index(Request $request)
    {
        $userId = Helper::getUserId();
        // return $userId;

        $items = Helper::viewServiceWishList($userId);
        // return $items;
        $total = Helper::getServiceWishlistTotal($userId);

        if (count($items) == 0) {
            return response()->json(['status' => 200, 'message' => "Your Service Wishlist is empty.", "service wishlist total" => $total], 200);
        }

        $servicewishlistData = Helper::getServiceWishlistTotalDetails($userId);
        $discount = $servicewishlistData['discount'];
        $subTotal = $servicewishlistData['subTotal'];

        return $this->sendResponse([
            'items'           => ServiceWishlistApiCollection::collection($items),
            //   'Servicewishlist_item_with_module' => ModuleServiceWishlistApiCollection::collection($modules),
            'service_wish_list'      => Helper::twoDecimalPoint($total),
            'service_wish_list_show' => Helper::getDisplayAmount($total),
            'subTotal'        => Helper::getDisplayAmount($subTotal),
            'cgst'            => Helper::getDisplayAmount(0),
            'sgst'            => Helper::getDisplayAmount(0),
            'igst'            => Helper::getDisplayAmount(0),
            'total_discount'  => Helper::getDisplayAmount($discount),
            'item_count'      => count($items)
        ], $this->getLangMessages('Service wishlist data is retrieved successfully.', 'Servicewishlist'));
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
     /**
     *   @OA\Post(
     *     path="/api/service-wishlists",
     *      tags={"Store service Wish list"},
     *       @OA\Parameter(
     *           name="service_id",
     *           in="query",
     *           required=true,
     *           description="service_id =>1",
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      
     *       
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       )
     * )
     */
    public function store(CreateServiceWishlistApiRequest $request)
    {
        /*  $servicewishlist =  $this->servicewishlistApiRepository->createServiceWishlist($request);

        return $this->sendResponse(new ServiceWishlistApiCollection($servicewishlist), 'ServiceWishlist created successfully'); */
        $userId = Helper::getUserId();
        // $input = collect($request->all());
        $item = $this->serviceApiRepository->where('id', $request->service_id)->first();
        if ((empty($item)) || ($item->status != "active")) {
            return $this->sendError($this->getLangMessages('This item is not available right now.', 'Servicewishlist'), 200);
        }
        $data = $this->servicewishlistApiRepository->addToServiceWishlist($request);
        $items = Helper::viewServiceWishList($userId);
        $total =  Helper::getServiceWishListTotal($userId);
        $servicewishlistData =  Helper::getServiceWishListTotalDetails($userId);
        $discount = $servicewishlistData['discount'];

        return $this->sendResponse(
            [
                'items' => ServiceWishlistApiCollection::collection($items),
                'service_wishlist' => $total,
                'service_wishlist_show' => Helper::getDisplayAmount($total),
                'item_count' => count($items),
                'total_discount' => $discount,
                'delivery_charge' => '0.00'
            ],
            $this->getLangMessages('Item added to favorite successfully.', 'Item')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $servicewishlist = $this->servicewishlistApiRepository->findWithoutFail($id);
        if (empty($servicewishlist)) {
            return $this->sendError($this->getLangMessages('Sorry! ServiceWishlist not found', 'ServiceWishlist'));
        }
        return $this->sendResponse(new ServiceWishlistApiCollection($servicewishlist), 'ServiceWishlist retrived successfully');
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
    public function updateServiceWishlist(UpdateServiceWishlistApiRequest $request)
    {
        $userId = Helper::getUserId();
        $item = null;


        $data = $this->servicewishlistApiRepository->where([
            'user_id' => $userId,
            'service_id' => $request->service_id,
        ])->first();


        $item = $this->serviceApiRepository->where('id', $request->service_id)->first();


        if ((empty($item)) || ($item->status != "active")) {
            return $this->sendError($this->getLangMessages('This item is not available right now.', 'Servicewishlist'), 200);
        }

        if (empty($data)) {
            return $this->sendError($this->getLangMessages('Item does not found', 'Item'), 200);
        }


        // return $request->all();

        $data = $this->servicewishlistApiRepository->removeServiceWishlist($request);
        // return $data;
        $servicewishlistData =  Helper::getServiceWishlistTotalDetails($userId);
        $total =  Helper::getServiceWishlistTotal($userId);
        $discount = $servicewishlistData['discount'];
        $items = Helper::viewServiceWishlist($userId);

        return $this->sendResponse([
            'items' => ServiceWishlistApiCollection::collection($items),
            'wish_list_total' => $total,
            'with_list_total_show' => Helper::getDisplayAmount($total),
            'item_count' => count($items),
            'total_discount' => $discount,
            'delivery_charge' => '0.00'
        ], $this->getLangMessages('Item updated to Favorite list successfully.', 'Item'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $servicewishlist = $this->servicewishlistApiRepository->findWithoutFail($id);
        if (empty($servicewishlist)) {
            return $this->sendError($this->getLangMessages('Sorry! ServiceWishlist not found', 'ServiceWishlist'));
        }

        $servicewishlist->delete();
        return $this->sendResponse([], 'ServiceWishlist deleted successfully');
    }
}
