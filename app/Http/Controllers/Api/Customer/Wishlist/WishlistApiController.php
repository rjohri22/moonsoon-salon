<?php

namespace App\Http\Controllers\Api\Customer\Wishlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\WishlistApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Wishlist\WishlistApiCollection;
use App\Http\Requests\Api\Customer\Wishlist\UpdateWishlistApiRequest;
use App\Http\Requests\Api\Customer\Wishlist\CreateWishlistApiRequest;
use App\Repositories\ItemApiRepository;
use App\Traits\HelperTrait;

class WishlistApiController extends AppBaseController
{
    use HelperTrait;
    protected $wishlistApiRepository;
    protected $itemApiRepository;

    public function __construct(WishlistApiRepository $wishlist, ItemApiRepository $itemApi)
    {
        $this->wishlistApiRepository = $wishlist;
        $this->itemApiRepository = $itemApi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = \Helper::getUserId();
        // return $userId;

        \Helper::getNonAvailableItems($userId);

        $items = \Helper::viewWishList($userId);
        $total = \Helper::getWishlistTotal($userId);

        if (count($items) == 0) {
            return response()->json(['status' => 200, 'message' => "Your Wishlist is empty.", "wishlist_total" => $total], 200);
        }

        $wishlistData = \Helper::getWishlistTotalDetails($userId);
        $discount = $wishlistData['discount'];
        $subTotal = $wishlistData['subTotal'];

        return $this->sendResponse([
            'items'           => WishlistApiCollection::collection($items),
            //   'wishlist_item_with_module' => ModuleWishlistApiCollection::collection($modules),
            'wishlist'      => \Helper::twoDecimalPoint($total),
            'wishlist_show' => \Helper::getDisplayAmount($total),
            'subTotal'        => \Helper::getDisplayAmount($subTotal),
            'cgst'            => \Helper::getDisplayAmount(0),
            'sgst'            => \Helper::getDisplayAmount(0),
            'igst'            => \Helper::getDisplayAmount(0),
            'delivery_charge' => \Helper::getDisplayAmount(0),
            'total_discount'  => \Helper::getDisplayAmount($discount),
            'item_count'      => count($items)
        ], $this->getLangMessages('wishlist data is retrieved successfully.', 'wishlist'));
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
    public function store(CreateWishlistApiRequest $request)
    {
        /*  $wishlist =  $this->wishlistApiRepository->createWishlist($request);

        return $this->sendResponse(new WishlistApiCollection($wishlist), 'Wishlist created successfully'); */
        $userId = \Helper::getUserId();
        // $input = collect($request->all());
        $item = $this->itemApiRepository->where('id', $request->item_id)->first();
        if ((empty($item)) || ($item->status != "active")) {
            return $this->sendError($this->getLangMessages('This item is not available right now.', 'wishlist'), 200);
        }
        $data = $this->wishlistApiRepository->addToWishlist($request);
        $items = \Helper::viewWishList($userId);
        $total =  \Helper::getWishListTotal($userId);
        $wishlistData =  \Helper::getWishListTotalDetails($userId);
        $discount = $wishlistData['discount'];

        return $this->sendResponse(
            [
                'items' => WishlistApiCollection::collection($items),
                'wishlist' => $total,
                'wishlist_show' => \Helper::getDisplayAmount($total),
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
        $wishlist = $this->wishlistApiRepository->findWithoutFail($id);
        if (empty($wishlist)) {
            return $this->sendError($this->getLangMessages('Sorry! Wishlist not found', 'Wishlist'));
        }
        return $this->sendResponse(new WishlistApiCollection($wishlist), 'Wishlist retrived successfully');
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
    public function updateWishlist(UpdateWishlistApiRequest $request)
    {
        $userId = \Helper::getUserId();
        $item = null;


        $data = $this->wishlistApiRepository->where([
            'user_id' => $userId,
            'item_id' => $request->item_id,
        ])->first();


        $item = $this->itemApiRepository->where('id', $request->item_id)->first();


        if ((empty($item)) || ($item->status != "active")) {
            return $this->sendError($this->getLangMessages('This item is not available right now.', 'wishlist'), 200);
        }

        if (empty($data)) {
            return $this->sendError($this->getLangMessages('Item does not found', 'Item'), 200);
        }


        // return $request->all();

        $data = $this->wishlistApiRepository->removeWishlist($request);
        // return $data;
        $wishlistData =  \Helper::getWishlistTotalDetails($userId);
        $total =  \Helper::getWishlistTotal($userId);
        $discount = $wishlistData['discount'];
        $items = \Helper::viewWishlist($userId);

        return $this->sendResponse([
            'items' => WishlistApiCollection::collection($items),
            'wish_list_total' => $total,
            'with_list_total_show' => \Helper::getDisplayAmount($total),
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
        $wishlist = $this->wishlistApiRepository->findWithoutFail($id);
        if (empty($wishlist)) {
            return $this->sendError($this->getLangMessages('Sorry! Wishlist not found', 'Wishlist'));
        }

        $wishlist->delete();
        return $this->sendResponse([], 'Wishlist deleted successfully');
    }
}
