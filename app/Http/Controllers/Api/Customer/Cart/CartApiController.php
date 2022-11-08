<?php

namespace App\Http\Controllers\Api\Customer\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\CartApiRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\Customer\Cart\CartApiRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Cart\CartApiCollection;
use App\Http\Requests\Api\Customer\Cart\UpdateCartApiRequest;
use App\Http\Requests\Api\Customer\Cart\CreateCartApiRequest;
use App\Repositories\ItemApiRepository;
use App\Traits\HelperTrait;

class CartApiController extends AppBaseController
{
    use HelperTrait;
    protected $cartApiRepository;
    protected $itemApiRepository;

    public function __construct(CartApiRepository $cartApi, ItemApiRepository $itemApi)
    {
        $this->cartApiRepository = $cartApi;
        $this->itemApiRepository = $itemApi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     *   @OA\get(
     *     path="/api/carts",
     *      tags={"View all Cart Item"},
     *   
     *      
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *
     * )
     */
    public function index(CartApiRequest $request)
    {
        $userId = \Helper::getUserId();
        // return $userId;

        \Helper::getNonAvailableItems($userId);

        $items = \Helper::viewCart($userId);
        $total = \Helper::getTotal($userId);

        if (count($items) == 0) {
            return $this->sendError("Your cart is empty.");
            // return response()->json(['success' => false, 'message' => "Your cart is empty.", "cart_total" => $total], 200);
        }

        $cartData = \Helper::getCartTotalDetails($userId);
        $discount = $cartData['discount'];
        $subTotal = $cartData['subTotal'];
        return $this->sendResponse([
            'items'           => CartApiCollection::collection($items),
            /* 'cart_item_with_module' => ModuleCartCollection::collection($modules), */
            'cart_total'      => \Helper::twoDecimalPoint($total),
            'cart_total_show' => \Helper::getDisplayAmount($total),
            'subTotal'        => \Helper::getDisplayAmount($subTotal),
            'cgst'            => \Helper::getDisplayAmount(0),
            'sgst'            => \Helper::getDisplayAmount(0),
            'igst'            => \Helper::getDisplayAmount(0),
            'delivery_charge' => \Helper::getDisplayAmount(0),
            'total_discount'  => \Helper::getDisplayAmount($discount),
            'item_count'      => count($items)
        ], $this->getLangMessages('Cart data is retrieved successfully.', 'Cart'));
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
    public function store(CreateCartApiRequest $request)
    { 
            // return $request->all();
            $userId = \Helper::getUserId();
            // $cartReplaced = $request->cart_replaced ?? 0;

            $item = null;

            $item = $this->itemApiRepository->where('id', $request->item_id)->first();
            
            // return $item;
            if ((empty($item)) || ($item->status != "active") || ($item->qty == 0)) {
                return $this->sendError($this->getLangMessages('This item is not available right now.', 'Cart'), 200);
            }

            $data = $this->cartApiRepository->addToCart($request);
            $items = \Helper::viewCart($userId);
            $total =  \Helper::getTotal($userId);
            $cartData =  \Helper::getCartTotalDetails($userId);
            $discount = $cartData['discount'];

            return $this->sendResponse([
                'items' => CartApiCollection::collection($items),
                'cart_total' => $total,
                'cart_total_show' => \Helper::getDisplayAmount($total),
                'item_count' => count($items),
                'total_discount' => $discount,
                'delivery_charge' => '0.00'
            ], $this->getLangMessages('Item added to cart successfully.', 'Item'));
        

        //return $this->sendResponse(new CartApiCollection($cart), 'Cart created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $cart = $this->cartApiRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError($this->getLangMessages('Sorry! Cart not found', 'Cart'));
        }
        return $this->sendResponse(new CartApiCollection($cart), 'Cart retrived successfully');
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
     /**
     *   @OA\Put(
     *     path="/api/carts/{id}",
     *      tags={"Update Cart Item"},
     *   
     *      @OA\Parameter(
     *           name="operation",
     *           in="query",
     *           required=true,
     *           description="minus/plus",
     *           @OA\Schema(
     *               type="sting"
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *
     * )
     */
    public function update($id, UpdateCartApiRequest $request)
    {
        // return $id;
        $cart = $this->cartApiRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError($this->getLangMessages('Sorry! Cart not found', 'Cart'));
        }
        $cart = $this->cartApiRepository->updateCart($id, $request);
        // return $cart;
        return $this->sendResponse(new CartApiCollection($cart), 'Cart updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
     *   @OA\DELETE(
     *     path="/api/carts/{id}",
     *      tags={"Delete Cart Item"},
     *     
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *
     * )
     */
    public function destroy($id, Request $request)
    {
        $cart = $this->cartApiRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError($this->getLangMessages('Sorry! Cart not found', 'Cart'));
        }

        $cart->delete();
        return $this->sendResponse([], 'Cart deleted successfully');
    }
}
