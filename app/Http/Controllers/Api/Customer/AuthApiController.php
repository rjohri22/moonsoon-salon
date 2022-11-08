<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\App\Customer\CreateUserApiRequest;
use App\Http\Resources\API\App\Customer\UserCollection;
use Illuminate\Http\Request;
use App\Repositories\UserApiRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class AuthApiController extends Controller
{
    protected $userRepository;

    public function __construct(UserApiRepository $user)
    {
        $this->userRepository = $user;
        //$this->zipRepository = $zipcode;
    }

    /**
     *   @OA\Get(
     *     path="/api/app/customer/Useres",
     *      tags={"Salon App: All Useres"},
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
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        // $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
        $items = $this->userRepository->paginate(1);

        return json_encode(['item' => $items, 'total' => $items->total()]);
    }


    // Store User

    /**
     *   @OA\Post(
     *     path="/api/app/customer/Useres",
     *      tags={"Salon App: Store User"},
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
    public function store(CreateUserApiRequest $request)
    {
        /*$data = $this->zipRepository->checkZipcodeStatus($request);
        if(count($data) == 0){
            return $this->sendError($this->getLangMessages('Sorry, service not available in this zipcode.', 'User'));
        }*/
        $user =  $this->userRepository->createUser($request);

        return $this->sendResponse(new UserCollection($user), 'User created successfully');
    }


    /**
     * @OA\Get(
     *     path="/api/app/customer/Useres/{id}",
     *      tags={"Salon App: Show Specific User"},
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
        $user = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            return $this->sendError($this->getLangMessages('Sorry! User not found', 'User'));
        }

        /* $total = $subTotal = $discount = 0;
        if (!empty($request->user_id)) {
          $total = \Helper::getTotal($request->user_id);
          $cartData = \Helper::getCartTotalDetails($request->user_id);
          $discount = $cartData['discount'];
          $subTotal = $cartData['subTotal'];
        }

        return $this->sendResponse([
            'User'         => $user->toArray(),
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
          $this->getLangMessages('admin/messages.retrieve_success', 'User')
        );*/

        return $this->sendResponse(new UserCollection($user), 'User retrived successfully');
    }


    // Update User

    /**
     *   @OA\Put(
     *     path="/api/app/customer/Useres/{id}",
     *      tags={"Salon App: Edit User"},
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
    public function update($id, UpdateUserApiRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            return $this->sendError($this->getLangMessages('Sorry! User not found', 'User'));
        }
        /*$data = $this->zipRepository->checkZipcodeStatus($request);
        if(count($data) == 0){
            return $this->sendError($this->getLangMessages('Sorry, service not available in this zipcode.', 'User'));
        }*/
        $user = $this->userRepository->updateUser($id, $request);

        return $this->sendResponse(new UserCollection($user), 'User updated successfully');
    }


    /**
     * @OA\Delete(
     *     path="/api/app/customer/Useres/{id}",
     *      tags={"Salon App: Delete User"},
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
        $user = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            return $this->sendError($this->getLangMessages('Sorry! User not found', 'User'));
        }

        $user->delete();
        return $this->sendResponse([], 'User deleted successfully');
    }
}
