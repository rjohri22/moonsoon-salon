<?php

namespace App\Http\Controllers\Api\Customer\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ServiceCartApiRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\Customer\Cart\CartApiRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Cart\ServiceCartApiCollection;
use App\Http\Requests\Api\Customer\Cart\UpdateServiceCartApiRequest;
use App\Http\Requests\Api\Customer\Cart\CreateServiceCartApiRequest;
use App\Repositories\ServiceApiRepository;
use App\Traits\HelperTrait;
use App\Utils\Helper;
class ServiceCartApiController extends AppBaseController
{
    use HelperTrait;
    protected $serviceCartApiRepository;
    protected $serviceApiRepository;

    public function __construct(ServiceCartApiRepository $cartApi, ServiceApiRepository $serviceApi)
    {
        $this->serviceCartApiRepository = $cartApi;
        $this->serviceApiRepository = $serviceApi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     *   @OA\get(
     *     path="/api/service-carts",
     *      tags={"View all service Cart Item"},
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
        $userId = Helper::getUserId();
        // return $userId;

        // \Helper::getNonAvailableItems($userId);

        $items = Helper::viewServiceCart($userId);
        $total = Helper::getServiceTotal($userId);

        if (count($items) == 0) {
            return $this->sendError("Your cart is empty.");
            // return response()->json(['success' => false, 'message' => "Your cart is empty.", "cart_total" => $total], 200);
        }

        $cartData = \Helper::getServiceCartTotalDetails($userId);
        $discount = $cartData['discount'];
        $subTotal = $cartData['subTotal'];
        return $this->sendResponse([
            'items'           => ServiceCartApiCollection::collection($items),
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
     /**
     *   @OA\Post(
     *     path="/api/service-carts",
     *      tags={"Store service carts"},
     *       @OA\Parameter(
     *           name="service_id",
     *           in="query",
     *           required=true,
     *           description="service_id =>1",
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="service_date",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="date"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="service_time",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="time"
     *           )
     *       ),
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
    public function store(CreateServiceCartApiRequest $request)
    { 
            // return $request->all();
            $userId = \Helper::getUserId();
            // $cartReplaced = $request->cart_replaced ?? 0;

            $item = null;

            $item = $this->serviceApiRepository->where('id', $request->service_id)->first();
            
            // return $item;
            if ((empty($item)) || ($item->status != "active")) {
                return $this->sendError($this->getLangMessages('This Service is not available right now.', 'Cart'), 200);
            }

            $data = $this->serviceCartApiRepository->addToServiceCart($request);
            $items = Helper::viewServiceCart($userId);
            $total =  Helper::getServiceTotal($userId);
            $cartData =  Helper::getServiceCartTotalDetails($userId);
            $discount = $cartData['discount'];

            return $this->sendResponse([
                'items' => ServiceCartApiCollection::collection($items),
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
        $cart = $this->serviceCartApiRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError($this->getLangMessages('Sorry! Cart not found', 'Cart'));
        }
        return $this->sendResponse(new ServiceCartApiCollection($cart), 'Cart retrived successfully');
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
    
    public function update($id, UpdateServiceCartApiRequest $request)
    {
        // return $id;
        $cart = $this->serviceCartApiRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError($this->getLangMessages('Sorry! Cart not found', 'Cart'));
        }
        $cart = $this->serviceCartApiRepository->updateCart($id, $request);
        // return $cart;
        return $this->sendResponse(new ServiceCartApiCollection($cart), 'Cart updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
     *   @OA\DELETE(
     *     path="/api/service-carts/{id}",
     *      tags={"Delete service Cart Item"},
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
        $cart = $this->serviceCartApiRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError($this->getLangMessages('Sorry! Cart not found', 'Servcie'));
        }

        $cart->delete();
        return $this->sendResponse([], 'Cart deleted successfully');
    }
}
