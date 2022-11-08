<?php

namespace App\Http\Controllers\Api\Customer\Address;

use Illuminate\Http\Request;
use App\Repositories\AddressRepository;
//use App\Repositories\ZipCodeRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Address\AddressCollection;
use App\Http\Requests\Api\Customer\Address\UpdateAddressApiRequest;
use App\Http\Requests\Api\Customer\Address\CreateAddressApiRequest;

class AddressApiController extends AppBaseController
{

    protected $addressRepository;
    protected $zipRepository;

    // public function __construct(AddressRepository $address, ZipCodeRepository $zipcode)
    public function __construct(AddressRepository $address)
    {
        $this->addressRepository = $address;
        //$this->zipRepository = $zipcode;
    }

    /**
     *   @OA\Get(
     *     path="/api/app/customer/addresses",
     *      tags={"Salon App: All Addresses"},
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     * )
     */
    public function index(Request $request)
    {
        $this->addressRepository->pushCriteria(new RequestCriteria($request));
        /*  $this->addressRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->addressRepository->pushCriteria(new AddressCriteria($request)); */
        $items = $this->addressRepository->where('user_id',\Auth::user()->id)->get();

        return $this->sendResponse(['lists' => AddressCollection::collection($items)], '');
    }


    // Store Address

    /**
     *   @OA\Post(
     *     path="/api/app/customer/addresses",
     *      tags={"Salon App: Store Address"},
     *       @OA\Parameter(
     *           name="type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="street",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="landmark",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="city",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="state",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="zipcode",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="country",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="contact",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     * )
     */
    public function store(CreateAddressApiRequest $request)
    {
        /*$data = $this->zipRepository->checkZipcodeStatus($request);
        if(count($data) == 0){
            return $this->sendError($this->getLangMessages('Sorry, service not available in this zipcode.', 'Address'));
        }*/
        $address =  $this->addressRepository->createAddress($request);

        return $this->sendResponse(new AddressCollection($address), 'Address created successfully');
    }


    /**
     * @OA\Get(
     *     path="/api/app/customer/addresses/{id}",
     *      tags={"Salon App: Show Specific Address"},
     *
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Failure"
     *       )
     *
     * )
     */
    public function show($id, Request $request)
    {
        $address = $this->addressRepository->findWithoutFail($id);
        if (empty($address)) {
            return $this->sendError($this->getLangMessages('Sorry! Address not found', 'Address'));
        }

        /* $total = $subTotal = $discount = 0;
        if (!empty($request->user_id)) {
          $total = \Helper::getTotal($request->user_id);
          $cartData = \Helper::getCartTotalDetails($request->user_id);
          $discount = $cartData['discount'];
          $subTotal = $cartData['subTotal'];
        }

        return $this->sendResponse([
            'address'         => $address->toArray(),
            'cart_total'      => \Helper::twoDecimalPoint($total),
            'cart_total_show' => \Helper::getDisplayAmount($total),
            'subTotal'        => \Helper::getDisplayAmount($subTotal),
            'cgst'            => \Helper::getDisplayAmount(0),
            'sgst'            => \Helper::getDisplayAmount(0),
            'igst'            => \Helper::getDisplayAmount(0),
            'delivery_charge' => \Helper::getDisplayAmount(0),
            'total_discount'  => \Helper::getDisplayAmount($discount),
            'pay_amount'      => \Helper::twoDecimalPoint($total),
            'pay_amount_show' => \Helper::getDisplayAmount($total),

          ],
          $this->getLangMessages('admin/messages.retrieve_success', 'Address')
        );*/

        return $this->sendResponse(new AddressCollection($address), 'Address retrived successfully');
    }


    // Update Address

    /**
     *   @OA\Put(
     *     path="/api/app/customer/addresses/{id}",
     *      tags={"Salon App: Edit Address"},
     *       @OA\Parameter(
     *           name="type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="street",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="landmark",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="city",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="state",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="zipcode",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="country",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="contact",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     * )
     */
    public function update($id, UpdateAddressApiRequest $request)
    {
        $address = $this->addressRepository->findWithoutFail($id);
        if (empty($address)) {
            return $this->sendError($this->getLangMessages('Sorry! Address not found', 'Address'));
        }
        /*$data = $this->zipRepository->checkZipcodeStatus($request);
        if(count($data) == 0){
            return $this->sendError($this->getLangMessages('Sorry, service not available in this zipcode.', 'Address'));
        }*/
        $address = $this->addressRepository->updateAddress($id, $request);

        return $this->sendResponse(new AddressCollection($address), 'Address updated successfully');
    }


    /**
     * @OA\Delete(
     *     path="/api/app/customer/addresses/{id}",
     *      tags={"Salon App: Delete Address"},
     *
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Failure"
     *       )
     *
     * )
     */
    public function destroy($id, Request $request)
    {
        $address = $this->addressRepository->findWithoutFail($id);
        if (empty($address)) {
            return $this->sendError($this->getLangMessages('Sorry! Address not found', 'Address'));
        }

        $address->delete();
        return $this->sendResponse([], 'Address deleted successfully');
    }
}
