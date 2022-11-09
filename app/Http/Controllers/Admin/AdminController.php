<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\User;
use App\Http\Requests\UserProfileRequest;
use App\Models\Brand;
use App\Models\Item;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends AppBaseController
{
    public function dashboard(Request $request)
    {
 

        $brand  = Brand::getActive()->count();
        $item   = Item::getActive()->count();
        $service   = Service::getActive()->count();
        $payment   = Order::sum('total_amount');
        $servicePayment   = ServiceOrder::sum('total_amount');
        $toReturn=[];
        $year=date('Y');
        $month=date('m');
		$yearTo = $year + 1;
		if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) {
			$daysArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
		} else if ($month == 2) {
			if ($yearTo % 4 == 0) {
				$daysArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29];
			} else {
				$daysArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28];
			}
		} else {
			$daysArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30];
        }
        $monthArray = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $toReturn['days_array']=$daysArray;
        $toReturn['months_array']=$monthArray;
        $Current_month=date("M");
        $PerDaySales_Amount=[];
        foreach($monthArray as $key_month => $valueMonth)
        {
            // echo "<br>";
            // echo date('Y-'.$valueMonth."-01 00:00:00");
            $orderAmount = Order::whereBetween('created_at',[date('Y-'.$valueMonth."-01 00:00:00"),date('Y-'.$valueMonth."-31 23:59:59")])->where('txn_status','successful')->sum('total_amount');
            $toReturn['order_dataset'][$key_month]= (int)$orderAmount;
        }
        // foreach($monthArray as $key_month => $valueMonth)
        // {
        //     // echo "<br>";
        //     // echo date('Y-'.$valueMonth."-01 00:00:00");
        //     $orderAmount = ServiceOrder::whereBetween('created_at',[date('Y-'.$valueMonth."-01 00:00:00"),date('Y-'.$valueMonth."-31 23:59:59")])->where('txn_status','successful')->sum('total_amount');
        //     $toReturn['service_order_dataset'][$key_month]= (int)$orderAmount;
        // }
        // return $toReturn['order_dataset'];
        // exit;
        // foreach($monthArray as $keymonth => $valueMonth)
        // {
        //     $orderAmount = ServiceOrder::whereBetween('created_at',[date('Y-'.$valueMonth."-01"),date('Y-'.$valueMonth."-31")])->where('txn_status','successful')->sum('total_amount');
        //     $toReturn['service_order_dataset'][$keymonth]=$orderAmount;
        // }
        // $toReturn['Current_month']=$Current_month;
        // $toReturn['PerDaySales_Amount']=$PerDaySales_Amount;
        return view('admin/dashboard')->with('brand',$brand)->with('item',$item)->with('toReturn',$toReturn)->with('service',$service)->with('payment',$payment+$servicePayment);

    }
    /**
     *   @OA\Post(
     *     path="/api/profile",
     *      tags={"User Profile Edit"},
     *       @OA\Parameter(
     *           name="first_name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="last_name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="email",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Parameter(
     *           name="username",
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
    public function updateProfile(UserProfileRequest $request)
    {

        // return $request->all();
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'username' => 'required',
        //     'email' => 'required|email',
        //     // 'profile_pic' => 'required'
        // ]);
        // $profile_img = "";
        // if ($request->profile_pic != "") {
        //     $profile_img = $request->profile_pic->getClientOriginalName();
        //     // return $teams_image;
        //     request()->profile_pic->move('web_assets/images/profile_pic', $profile_img);
        // }
        $profile = User::where('id', \Auth::user()->id)->first();
        // if ($profile_img != "") {
        //     $profile->profile_pic = $profile_img;
        // }
        $profile->name = $request->name;
        $profile->username = $request->username;
        $profile->email = $request->email;
        $profile->save();
        $message = "Profile Updated Successfully..";
        return redirect('/admin/profile')->with('success', $message);
    }

    public function updatePassword(Request $request)
    {
        $validator =  $request->validate(
            [
                'password' => 'required|min:6|confirmed',
            ],
            [
                'password.confirmed' => 'Confirm Password And Password has to be same.',
            ]
        );
        $profile = User::where('id', \Auth::user()->id)->first();
        $profile->password = \Hash::make($request->password);
        $profile->save();
        $message = "Password Updated Successfully..";
        return redirect('/admin/password-update')->with('success', $message);
    }

    public function profileView(Request $request)
    {
        return view('admin.profile.edit_profile');

        # code...
    }
    public function changePasswordView(Request $request)
    {
        # code...
        return view('admin.profile.update_password');
    }
}
