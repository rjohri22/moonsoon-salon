<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Customer\UserApiCollection;
use App\Http\Resources\Api\Customer\ContactUsApiCollection;
use App\Http\Resources\Api\Customer\UserDetailsApiCollection;
use Carbon\Carbon;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\Customer\CreateUserApiRequest;
use App\Http\Requests\Api\Customer\ContactUsApiRequest;
use App\Repositories\UserApiRepository;
use App\Utils\Helper;
use App\Http\Requests\Api\Customer\SocialLoginApiRequest;
use App\Traits\UploaderTrait;
use App\Models\ContactUs;
class UserLoginApiController extends AppBaseController
{
    protected $userRepository;
    use UploaderTrait;
    public function __construct(UserApiRepository $user)
    {
        $this->userRepository = $user;
        //$this->zipRepository = $zipcode;
    }
    /**
     * handle user registration request
     */
    public function register(CreateUserApiRequest $request)
    {
        /*  $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|min:10|max:10',
        ]);
        // return $request;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile
        ]); */
        // return $user;
        $user = $this->userRepository->createUser($request);
        if (!empty($user)) {
            return $this->sendResponse(['otp' => '0000'], 'OTP sent successfully');
        }
        //return the access token we generated in the above step
        return $this->sendError("User Not Register");
    }

    /**
     * login user to our application
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required',
        ]);
        $user = User::where('mobile', $request->mobile)->first();
        if (!empty($user)) {
            return $this->sendResponse(['otp' => '0000'], 'OTP sent successfully');
        }
        return  $this->sendError('User Not Found');

    }

     /**
     *   @OA\Get(
     *     path="/api/otp-login",
     *      tags={"Salon App:otp-login"},
     *          @OA\Parameter(
     *           name="mobile",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *          @OA\Parameter(
     *           name="otp",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *           @OA\Parameter(
     *           name="device_type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *          @OA\Parameter(
     *           name="device_token",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *          @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *          ),
     *     )
     */
   
    public function otpLogin(Request $request)
    {
        $deviceType =  $request->device_type;
        $deviceToken =  $request->device_token;
        $this->validate($request, [
            'mobile' => 'required',
            'otp' => 'required',
        ]);
        $otp = $request->otp;
        $user = User::where('mobile', $request->mobile)->first();
        if (empty($user)) {
          return  $this->sendError('User Not Found');
        }
        if ((!empty($user)) && ($otp == "0000")) {
            if(!empty($deviceType) && !empty($deviceToken))
            {
                Helper::updateDeviceToken($user->id,$deviceToken,$deviceType);
            }
            $tokenResult = $user->createToken('monsool-app');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addYears(1);
            $user['access_token'] = $tokenResult->accessToken;
            $token->save();
            return $this->sendResponse(new UserApiCollection($user), 'Login successfully');
        }
        //generate the token for the user
        //now return this token on success login attempt
        return $this->sendError('Otp Not Matched');
    }

    /**
     * This method returns authenticated user details
     */
    public function userDetails()
    {
        //returns details
        return $this->sendResponse(['user' => new UserDetailsApiCollection(auth()->user())], 'User details fetch successfully');
    }

    /**
     *   @OA\POSt(
     *     path="/api/social-login",
     *      tags={"Salon App:social-login"},
     *          @OA\Parameter(
     *           name="username",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *          @OA\Parameter(
     *           name="first_name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *           @OA\Parameter(
     *           name="last_name",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *          @OA\Parameter(
     *           name="login_type",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *          @OA\Parameter(
     *           name="social_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *          @OA\Parameter(
     *           name="social_photo",
     *           in="query",
     *           required=false,
     *           description="this is social photo link with you get after login with social",
     *           @OA\Schema(
     *               type="string"
     *           )
     *          ),
     *          @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *          ),
     *      )
     */
    public function socialLogin(SocialLoginApiRequest $request)
	{
		// return $request->all();

		$username= $request->username;
		$params = [
			'first_name'=> $request->first_name,
			'last_name'=> $request->last_name ? $request->last_name : "",
			// 'role_id'=>,
		];
		if (is_numeric($username) && strlen($username) == 10) {
			$user = $this->userRepository->where('mobile', $username)->first();
			$params['mobile'] = $username ;
		}
		else if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
			$user = $this->userRepository->where('email', $username)->first();
			$params['email'] = $username ;
		}
		else {
			return $this->sendError('Username must be phone or email.', 401);
		}
		if(empty($request->first_name)) {
			return $this->sendError('First name is required', 401);
		}

		$loginType =["google" => "google_id", "facebook"=> "facebook_id", "twitter"=> "twitter_id", "apple"=>"apple_id"];


		if (array_key_exists($request->login_type, $loginType)) {
			$params[$loginType[$request->login_type]] = $request->social_id;
		}

		if(empty($user)) {
			$user =  $this->userRepository->create($params);
		} else {
			$user->update($params);

			if(isset($user->media)){
				$storageName  = $user->media->file_name;
				$this->deleteFile('user/'.$storageName);
				// remove from the database
				$user->media->delete();
			}
		}
		$media       = $this->uploadMediaUrlImage($request->social_photo, $user);
		$info        = pathinfo($request->social_photo);
		$tokenResult = $user->createToken('Personal Access Token');
		$token       = $tokenResult->token;
		$token->expires_at = Carbon::now()->addYears(1);
		$user['access_token'] = $tokenResult->accessToken;
		$token->save();

		return $this->sendResponse(new UserApiCollection($user), 'Login successfully');
	}

    /**
     *   @OA\Post(
     *     path="/api/contact-us",
     *      tags={"contact-us Save"},
     *     
     *      @OA\Parameter(
     *           name="title",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="description",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
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
     *
     * )
     */
    public function saveContactUs(ContactUsApiRequest $request)
    {
        $data = [
        'title'=>$request->title,
        'description'=>$request->description,
        'mobile_no'=>\Auth::user()->mobile_no,
        'email'=>\Auth::user()->email,
        'status'=>"active",
        'name'=>\Auth::user()->first_name." ". \Auth::user()->last_name,
        ];
        $contactUs = ContactUs::create($data);
        return $this->sendResponse(new ContactUsApiCollection($contactUs), 'Saved successfully');
    }

}
