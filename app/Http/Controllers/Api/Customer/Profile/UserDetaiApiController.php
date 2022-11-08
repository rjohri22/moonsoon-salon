<?php

namespace App\Http\Controllers\Api\Customer\Profile;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\Profile\UserDetaiApiRequest;
use App\Http\Resources\Api\Customer\Profile\UserDetaiApiCollection;
use App\Http\Resources\Api\Customer\Profile\UserDetaiApiResource;
use App\Models\Gender;
use App\Models\HairLength;
use App\Models\HairType;
use App\Models\MaritalStatus;
use App\Models\SkinType;
use App\Models\UserDetail;
use App\Repositories\UserDetailApiRepository;
use Illuminate\Http\Request;

class UserDetaiApiController extends AppBaseController
{
    protected $userDetailApiRepository;

    public function __construct(UserDetailApiRepository $userDetail)
    {
        $this->userDetailApiRepository = $userDetail;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     *   @OA\get(
     *     path="/api/user-profile",
     *      tags={"View User profile"}, 
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
    public function index()
    {
        $data = UserDetail::whereUserId(\Helper::getUserId())->first();
        if (empty($data)) {
            return $this->sendResponse(new UserDetaiApiCollection($data), 'fetched successfully');
        }
        return $this->sendResponse(new UserDetaiApiCollection($data), 'fetched successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     /**
     *   @OA\get(
     *     path="/api/user-profile/create",
     *      tags={"Get Update User Profile Data"}, 
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
    public function create()
    {
        $hairType = HairType::select('id', 'hair_type as type')->get();
        $hairLength = HairLength::select('id', 'hair_length as length')->get();
        $skinType = SkinType::select('id', 'skin_type as type')->get();
        $maritalStatus = MaritalStatus::select('id', 'marital_status as status')->get();
        $gender = Gender::select('id', 'gender')->get();
        return $this->sendResponse(['hair_types' => $hairType, 'hair_length' => $hairLength, 'skin_type' => $skinType, 'marital_status' => $maritalStatus, 'gender' => $gender], 'fetched successfully');
    }

    
    /**
     *   @OA\POST(
     *     path="api/user-profile/",
     *      tags={"Update User profile"}, 
     *          @OA\Parameter(
        *           name="marital_status",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="string"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="dob",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="date"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="anniversary",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="date"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="hair_length",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="string"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="hair_type",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="string"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="skin_type",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="string"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="allergies",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="string"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="first_name",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="string"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="last_name",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="string"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="email",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="email"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="mobile",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="number"
        *           )
        *       ),
        *          @OA\Parameter(
        *           name="gender",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="string"
        *           )
        *       ),
        *      @OA\Parameter(
        *           name="file",
        *           in="query",
        *           required=false,
        *           @OA\Schema(
        *               type="mutipart"
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserDetaiApiRequest $request)
    {
        $userDetail = $this->userDetailApiRepository->saveUserDetail($request);
        return $this->sendResponse(['data' => new UserDetaiApiCollection($userDetail)], 'saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}
