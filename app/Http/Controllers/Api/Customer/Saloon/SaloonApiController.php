<?php

namespace App\Http\Controllers\Api\Customer\Saloon;

// use App\Helpers\Helper;
use App\Utils\Helper;
use Illuminate\Http\Request;
use App\Repositories\SaloonRepository;
use App\Repositories\UserApiRepository;
//use App\Repositories\ZipCodeRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Saloon\SaloonCollection;
use App\Http\Requests\Api\Customer\Saloon\UpdateSaloonApiRequest;
use App\Http\Requests\Api\Customer\Saloon\CreateSaloonApiRequest;

class SaloonApiController extends AppBaseController
{

    protected $saloonRepository;
    protected $userRepository;

    // public function __construct(SaloonRepository $saloon, ZipCodeRepository $zipcode)
    public function __construct(SaloonRepository $saloon,UserApiRepository $user)
    {
        $this->saloonRepository = $saloon;
        $this->userRepository = $user;
        //$this->zipRepository = $zipcode;
    }

    /**
     *   @OA\Get(
     *     path="/api/saloons",
     *      tags={"Salon App: All Saloones"},
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
        $this->saloonRepository->pushCriteria(new RequestCriteria($request));
        /*  $this->saloonRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->saloonRepository->pushCriteria(new SaloonCriteria($request)); */
        $items = $this->saloonRepository->where('status','active')->get();

        return $this->sendResponse(SaloonCollection::collection($items), 'fetched successfully');
    }


    // Store Saloon

    /**
     *   @OA\Post(
     *     path="/api/saloons",
     *      tags={"Salon App: Store Saloon"},
     *       @OA\Parameter(
     *           name="shop_name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="whatsapp_no",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="mobile",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="address",
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
     *           name="description",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="lat",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="lng",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="first_name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="last_name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="email",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     * )
     */
    public function store(CreateSaloonApiRequest $request)
    {
        $data = $this->userRepository->createUser($request);
        if(empty($data)){
            return $this->sendError($this->getLangMessages('Something Went Wrong Please Try again', 'Saloon'));
        }
        $saloon =  $this->saloonRepository->createSaloon($request);

        return $this->sendResponse(new SaloonCollection($saloon), 'Saloon created successfully');
    }


    /**
     * @OA\Get(
     *     path="/api/saloons/{id}",
     *      tags={"Salon App: Show Specific Saloon"},
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
        $saloon = $this->saloonRepository->findWithoutFail($id);
        if (empty($saloon)) {
            return $this->sendError($this->getLangMessages('Sorry! Saloon not found', 'Saloon'));
        }

        /* $total = $subTotal = $discount = 0;
        if (!empty($request->user_id)) {
          $total = \Helper::getTotal($request->user_id);
          $cartData = \Helper::getCartTotalDetails($request->user_id);
          $discount = $cartData['discount'];
          $subTotal = $cartData['subTotal'];
        }

        return $this->sendResponse([
            'Saloon'         => $saloon->toArray(),
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
          $this->getLangMessages('admin/messages.retrieve_success', 'Saloon')
        );*/

        return $this->sendResponse(new SaloonCollection($saloon), 'Saloon retrived successfully');
    }


    // Update Saloon

    /**
     *   @OA\Put(
     *     path="/api/saloons/{id}",
     *      tags={"Salon App: Edit Saloon"},
     *        @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="whatsapp_no",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="mobile",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="address",
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
     *           name="description",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="lat",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="lng",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="owner_name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="email",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="user_name",
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
    public function update($id, UpdateSaloonApiRequest $request)
    {
        $saloon = $this->saloonRepository->findWithoutFail($id);
        if (empty($saloon)) {
            return $this->sendError($this->getLangMessages('Sorry! Saloon not found', 'Saloon'));
        }
        /*$data = $this->zipRepository->checkZipcodeStatus($request);
        if(count($data) == 0){
            return $this->sendError($this->getLangMessages('Sorry, service not available in this zipcode.', 'Saloon'));
        }*/
        $saloon = $this->saloonRepository->updateSaloon($id, $request);

        return $this->sendResponse(new SaloonCollection($saloon), 'Saloon updated successfully');
    }


    /**
     * @OA\Delete(
     *     path="/api/saloons/{id}",
     *      tags={"Salon App: Delete Saloon"},
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
     * )
     */
    public function destroy($id, Request $request)
    {
        $saloon = $this->saloonRepository->findWithoutFail($id);
        if (empty($saloon)) {
            return $this->sendError($this->getLangMessages('Sorry! Saloon not found', 'Saloon'));
        }

        $saloon->delete();
        return $this->sendResponse([], 'Saloon deleted successfully');
    }

    /**
     * @OA\get(
     *     path="/api/get-time-slot?date={date}&services_ids={1,2}&saloon_id=1",
     *      tags={"Salon App: View Slot"},
     *        @OA\Parameter(
     *           name="saloon_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="digit"
     *           )
     *       ),
     *        @OA\Parameter(
     *           name="services_ids",
     *           in="query",
     *           required=true,
     *          description="1,2,3",
     *           @OA\Schema(
     *               type="string"
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
    public function getTimeSlot(Request $request)
    {
        $date = $request->date ?? Helper::today();
        
        $timeSlot = Helper::createTimeRange("09:00:00","19:00:00");
        // return 
        return $this->sendResponse($timeSlot, 'Saloon salots successfully');

    }
}
