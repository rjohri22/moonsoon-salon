<?php

namespace App\Utils;

use DateTime;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Cart;
use App\Models\WishList;
use App\Models\Module;
use App\Models\GroceryItem;
use App\Models\MedicineItem;
use App\Models\RestaurantItem;
use App\Models\Item;
use App\Models\MedicineSilder;
use App\Models\Restaurant;
use App\Models\GrocerySlider;
use App\Models\SalonSilder;
use App\Models\Order;
use App\Http\Resources\API\App\Customer\CartCollection;
use App\Models\RestaurantSilder;
use App\Models\ServiceCart;
use App\Models\ServiceWishlist;
use App\Models\Slider;
use App\Models\DeviceToken;
use App\Models\Offer;
use App\Models\Wallet;

class Helper
{
    public static function getUniqueId()
    {
        return md5(microtime() . \Config::get('app.key'));
    }

    public static function splitName($name)
    {
        $name_arr = [];
        if (!empty($name)) {
            $name_arr2 = explode(" ", $name);

            $name_arr[] = trim($name_arr2[0]);
            $name_arr[] = trim(!empty($name_arr2[1]) ? substr($name, strlen($name_arr2[0]) + 1) : '');
        }

        return $name_arr;
    }

    public static function jsonDecode($string)
    {
        if (self::isJson($string)) {
            return json_decode($string);
        }

        return (array)$string;
    }

    public static function isJson($string)
    {
        if (!empty($string)) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }

        return false;
    }

    public static function convertObjectToArray($data)
    {
        return json_decode(json_encode($data), true);
    }

    public static function getDateDiff($start_date, $end_date)
    {
        $date1 = date_create($start_date);
        $date2 = date_create($end_date);
        return date_diff($date1, $date2)->format("%R%a");
    }

    public static function getTimeDiff($start_time, $end_time)
    {
        $time_diff = strtotime($end_time) - strtotime($start_time);
        $formatted_time = date('H:i', mktime(0, 0, $time_diff));
        return $formatted_time;
    }

    public static function convertToHour($time)
    {
        $hour = Carbon::now()->startOfDay()->addSeconds($time)->toTimeString();
        return $hour;
    }

    public static function getRawJSONRequest($data)
    {
        $data = (array) self::jsonDecode($data);
        return $data;
    }

    public static function getValueFromRawJSONRequest($data, $key)
    {
        $value = (isset($data[$key]) && !empty($data[$key])) ? $data[$key] : null;
        return $value;
    }

    public static function formatDateTime($created_at, $format = 1, $timezone_name = null)
    {
        if (!empty($created_at)) {
            $created_at = date('Y-m-d H:i:s', strtotime($created_at));
            $d = Carbon::createFromFormat('Y-m-d H:i:s', $created_at);

            if ($format == 1) {
                $d = $d->format('d/m/Y');
            } else if ($format == 2) {
                $d = $d->format('d/m/Y h:i:s');
            } else if ($format == 3) {
                $date = $d->format('Y-m-d');
                $today = today()->format('Y-m-d'); // Helper::today();
                $yesterday = now()->addDays(-1)->format('Y-m-d'); // Helper::yesterday();

                if ($date == $today) {
                    $d = 'Today';
                } else if ($date == $yesterday) {
                    $d = 'Yesterday';
                } else {
                    $d = $d->format('d M, Y');
                }
            } else if ($format === 4) {
                $d = $d->format('l , jS M, Y');
            } else if ($format === 5) {
                $d = $d->format('Y-m-d');
            } // October 13, 2014 11:13:00
            else if ($format === 6) {
                $d = $d->format('F d, Y H:i:s');
            } else if ($format === 7) {
                $d = $d->format('d/m/y');
            } else if ($format === 8) {
                $d = $d->format('g:i A');
            } else if ($format === 9) {
                $d = $d->format('Y-m-d');
            } else if ($format === 10) {
                $d = $d->format('d/m');
            } else if ($format === 11) {
                $d = $d->format('g:i:s A');
            } else if ($format === 12) {
                $d = $d->format('d M, Y g:i A');
            } else if ($format === 13) {
                $d = $d->format('d/m/Y h:i A');
            } else if ($format === 14) {
                $d = $d->format('H:i:s');
            } else if ($format === 15) {
                $d = $d->format('Y-m-d h:i');
            } else if ($format === 16) {
                $d = $d->format('jS M');
            } else if ($format === 17) {
                $d = $d->format('H:i');
            } else if ($format === 18) {
                $d = $d->format('l, M j, Y');
            } else if ($format === 19) {
                $d = $d->format('j-M-Y');
            } else if ($format === 20) {
                $d = $d->format('jS F, Y');
            } else if ($format === 21) {
                $d = $d->format('D, M j');
            } else if ($format === 22) {
                $d = $d->format('dS M, Y');
            } else if ($format === 23) {
                $d = $d->format('F,d,Y');
            } else if ($format === 24) {
                $d = $d->format('D d M Y');
            } else if ($format === 25) {
                $d = $d->format('jS F, Y h:i A');
            } else if ($format === 26) {
                $d = $d->format('F j, Y');
            } else if ($format == 27) {
                $d = $d->format('m/d/Y');
            }


            if (!empty($timezone_name)) {
                return $d . ' ' . $timezone_name;
            }

            return $d;
        }

        return '';
    }

    public static function convertToSecond($value)
    {
        $parsed = date_parse($value);
        $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
        return $seconds;
    }

    public static function getCurrentRoleId()
    {
        return \Auth::user()->role_id;
    }

    public static function getUserId()
    {
        if (!empty(\Auth::user())) {
            return \Auth::user()->id;
        } else {
            return null;
        }
    }


    public static function twoDecimalPoint($number)
    {
        return number_format((float)$number, 2, '.', '');
    }

    public static function showCardNumber($ccNum)
    {
        return str_replace(range(0, 9), "X", substr($ccNum, 0, -4)) .  substr($ccNum, -4);
    }


    /*
     *****************************************
     * METHOD USING
     *****************************************
     */

    public static function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found

        // return file_get_contents('https://api.ipify.org');
    }

    /*
     * Get current date and time
     */
    public static function currentDateTime()
    {
        return Carbon::now()->toDateTimeString();
    }

    /*
     * Get today
     */
    public static function today($format = 'Y-m-d')
    {
        \Log::info('Today:' . Carbon::now()->format($format));
        return Carbon::now()->format($format);
    }

    public static function yesterday($format = 'Y-m-d')
    {
        \Log::info('Yesterday:' . Carbon::now()->subDays(1)->format($format));
        return Carbon::now()->subDays(1)->format($format);
    }

    public static function dayBeforeYesterday($format = 'Y-m-d')
    {
        \Log::info('Day before Yesterday:' . Carbon::now()->subDays(2)->format($format));
        return Carbon::now()->subDays(2)->format($format);
    }

    public static function dayBeforeWeek($format = 'Y-m-d')
    {
        \Log::info('Day before Yesterday:' . Carbon::now()->subDays(2)->format($format));
        return Carbon::now()->subDays(7)->format($format);
    }

    /*
     * Current Month
     */
    public static function currentMonth($format = 'm')
    {
        return Carbon::now()->format($format);
    }

    public static function humanStringFormat($str = '')
    {
        if (!empty($str)) {
            $str = ucwords(str_replace('_', ' ', $str));
        }

        return $str;
    }

    /*
     * Last Month
    */
    public static function lastMonth($format = 'm')
    {
        return Carbon::now()->subMonth()->format($format);
    }

    /*
    * Current Year
    */
    public static function currentYear($format = 'Y')
    {
        return Carbon::now()->format($format);
    }

    /*
     * Last Year
    */
    public static function lastMonthYear($format = 'Y')
    {
        return Carbon::now()->subMonth()->format($format);
    }

    public static function firstDateOfThisMonth($format = 'Y-m-d', $date = NULL)
    {
        if (!empty($date)) {
            $d = new \DateTime($date);
            $d->modify('first day of this month');
        } else {
            $d = new \DateTime('first day of this month');
        }
        return $d->format($format);
    }


    public static function lastDateOfThisMonth($format = 'Y-m-d', $date = NULL)
    {
        if (!empty($date)) {
            $d = new \DateTime($date);
            $d->modify('last day of this month');
        } else {
            $d = new \DateTime('last day of this month');
        }
        return $d->format($format);
    }


    public static function firstDateOfLastMonth($format = 'Y-m-d')
    {
        $d = new \DateTime('first day of last month');
        return $d->format($format);
    }


    public static function lastDateOfLastMonth($format = 'Y-m-d')
    {
        $d = new \DateTime('last day of last month');
        return $d->format($format);
    }


    public static function firstDateOfThreeMonthAgo($format = 'Y-m-d')
    {
        $d = new \DateTime('first day of 3 month ago');
        return $d->format($format);
    }


    public static function lastDateOfThreeMonthAgo($format = 'Y-m-d')
    {
        $d = new \DateTime('last day of 3 month ago');
        return $d->format($format);
    }

    public static function cleanPhone($phone)
    {
        $phone = preg_replace("/[^\d]/", "", $phone);

        return $phone;
    }

    public static function cleanName($name)
    {
        if (!empty($name)) {
            $name = ucwords(str_replace('_', ' ', $name));
        }

        return $name;
    }

    /*
     * Formatting phone depending on phone
     *
     * @param phone
     *
     * @return formatted phone
     *
     */
    public static function formatPhone($phone)
    {
        $phone = preg_replace("/[^\d]/", "", $phone);

        $l = strlen($phone);
        $c = substr($phone, 0, ($l > 10 ? $l - 10 : 0));
        $p = substr($phone, $l - ($l > 10 ? 10 : $l));
        $p1 = substr($p, 0, 3);
        $p2 = substr($p, 3, 3);
        $p3 = substr($p, 6, 4);

        $ph = "";
        if ($c) {
            $ph .= '+' . $c;
        }
        if ($p1) {
            $ph .= '(' . $p1 . ') ';
        }
        if ($p2) {
            $ph .= $p2 . '-';
        }
        if ($p3) {
            $ph .= $p3;
        }

        return $ph;
    }

    // Push Notifications
    public static function sendPushNotification($to_app = null, $deviceTokens = [], $message = null, $title = '', $params = [])
    {

        if (empty($title)) {
            // $title = 'New Notification';
            $title = '';
        }

        if (!empty($params)) {
            $params = array_merge($params, ['to_app' => $to_app]);
        }

        if (is_array($deviceTokens) && sizeof($deviceTokens) > 0) {
            $deviceTokenAndroidArr       = !empty($deviceTokens['device_token_android']) ? $deviceTokens['device_token_android'] : [];

            //////////////////
            // Android
            //////////////////
            if (!empty($deviceTokenAndroidArr)) {
                $deviceTokenAndroidArr = array_unique($deviceTokenAndroidArr);

                // API access key from Google API's Console
                $api_access_key = 'AAAAcocNs74:APA91bGumvpAH_sm_VSiqmEET0Gfq0JUKN53I0n3fVFhFo7652GOkwYeOZE0CopJDTk5tUHI85CEqVRrCy6SRfrxa5RcV28Xvx6lHPYx0_wtFWM74yRR19UQFvnCOyGQa5i-a9Dz_jTe';

                foreach ($deviceTokenAndroidArr as $deviceTokenAndroid) {
                    if (!empty($deviceTokenAndroid) && $deviceTokenAndroid != 'NO_DEVICE_TOKEN_FOR_IOS_SIMULATOR') {
                        if (is_array($deviceTokenAndroid) && sizeof($deviceTokenAndroid) > 0) {
                            $registrationIds = $deviceTokenAndroid;
                        } else {
                            $registrationIds = array($deviceTokenAndroid);
                        }

                        // prep the bundle
                        $msg = array(
                            'message'     => $message,
                            'title'        => $title,
                            //'subtitle'	=> 'Subtitle',
                            //'tickerText'	=> 'Ticker text here',
                            'vibrate'    => 1,
                            'sound'        => 1,
                            //'largeIcon'	=> 'large_icon',
                            //'smallIcon'	=> 'small_icon',
                        );

                        if (!empty($params)) {
                            $msg = array_merge($msg, array('params' => $params));
                        }

                        $fields = array(
                            'registration_ids' => $registrationIds,
                            'data'               => $msg
                        );

                        $headers = array(
                            'Authorization: key=' . $api_access_key,
                            'Content-Type: application/json'
                        );

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                        $result = curl_exec($ch);
                        curl_close($ch);

                        return $result;
                        // print_r($result);
                        exit;
                        //\Log::info('Push Result:: App Name: ' . $to_app . ' | Device token Android: ' . $deviceTokenAndroid . ' | Title: ' . $title . ' | Message: ' . $message . ' | Result: ' . $result);
                        //echo $result;
                    }
                }
            }

            
        }

        return;
    }

    // Get HH:mm:ss format from time array
    public static function getTime($data)
    {
        $date = new DateTime();
        $time = is_array($data) ? $data :  json_decode($data, true);

        $object = $date->setTime($time["hh"] + ($time["A"] === 'PM' ? 12 : 0), $time["mm"]);

        return $object->format('H:i:s');
    }
    public static function setTimeZone($timezone)
    {
        if (!empty($timezone)) {
            date_default_timezone_set($timezone);
        }
    }

    public static function getFormattedDate($date)
    {
        if (!empty($date)) {
            $date_arr = explode('/', $date);
            $formatted_date = $date_arr[2] . '-' . $date_arr['1'] . '-' . $date_arr['0'];
            return $formatted_date;
        } else {
            return null;
        }
    }

    public static function getRoleId($name)
    {
        $role = Role::where('name', $name)->first();

        return ($role) ? $role->id : 0;
    }

    public static function superAdminRoleId()
    {
        return config('roles.super_admin');
    }

    public static function adminRoleId()
    {
        return config('roles.admin');
    }

    public static function managerRoleId()
    {
        return config('roles.manager');
    }

    public static function staffRoleId()
    {
        return config('roles.staff');
    }

    public static function sellerRoleId()
    {
        return config('roles.seller');
    }

    public static function deliveryBoyRoleId()
    {
        return config('roles.delivery_boy');
    }

    public static function customerRoleId()
    {
        return config('roles.customer');
    }

    public static function getCurrencySymbol()
    {
        return "₹";
    }

    public static function getCurrencyWithSymbol($amount)
    {
        if (!empty($amount)) {
            return (self::getCurrencySymbol() . ' ' . $amount);
        }
        return '';
    }

    /*  public static function getModuleId($slug)
    {
        $module = Module::where('slug', $slug)->first();
        $moduleId = !empty($module) ? $module->id : null;
        return $moduleId;
    } */

    public static function getPageLimit($limit)
    {
        $given_limit = !empty($limit) ? (int)$limit : 20;
        return $given_limit;
    }


    public static function jsonToFormatAddress($addrs)
    {
        if (empty($addrs)) {
            return "";
        }
        $addrs = (array) json_decode($addrs, true);
        $addrsArr = [];
        if (isset($addrs['street']) && !empty($addrs['street'])) {
            $addrsArr[] = $addrs['street'];
        }
        if (isset($addrs['landmark']) && !empty($addrs['landmark'])) {
            $addrsArr[] = $addrs['landmark'];
        }
        if (isset($addrs['city']) && !empty($addrs['city'])) {
            $addrsArr[] = $addrs['city'];
        }
        if (isset($addrs['state']) && !empty($addrs['state'])) {
            $addrsArr[] = $addrs['state'];
        }
        if (isset($addrs['zipcode']) && !empty($addrs['zipcode'])) {
            $addrsArr[] = 'ZipCode-' . $addrs['zipcode'];
        }
        if (isset($addrs['country']) && !empty($addrs['country'])) {
            $addrsArr[] = $addrs['country'];
        }
        return implode(", ", $addrsArr);
    }

    public static function validationErrorsToString($errJson)
    {
        $errArray = json_decode($errJson, TRUE);
        $valArr = array();
        foreach ($errArray as $key => $value) {
            // $errStr = $key.' '.$value[0];
            $errStr = $value[0];
            array_push($valArr, $errStr);
        }
        if (!empty($valArr)) {
            $errStrFinal = implode('; ', $valArr);
        }
        return $errStrFinal;
    }

    public static function getCustomerMobile($id)
    {
        $user = User::where('id', $id)->first();
        if (!empty($user)) {
            return $user->mobile;
        } else {
            return null;
        }
    }

    public static function getDifference($num1, $num2)
    {
        $diff = \Helper::twoDecimalPoint($num1) - \Helper::twoDecimalPoint($num2);
        return \Helper::twoDecimalPoint($diff);
    }

    public static function getSum($num1, $num2)
    {
        $sum = \Helper::twoDecimalPoint($num1) + \Helper::twoDecimalPoint($num2);
        return \Helper::twoDecimalPoint($sum);
    }

    public static function getStaticBanners()
    {
        $all_banners = [];
        for ($i = 1; $i < 7; $i++) {
            $all_banners[] = [
                'image' => asset('images/banners/' . $i . '.jpg'),
            ];
        }
        return $all_banners;
    }

    // Calculate Discounted Price
    public static function getSalePrice($originalPrice, $discount = 0, $type = NULL)
    {
        if ($discount) {
            if (strtolower($type) == "percentage" || strtolower($type) == "percent" || $type == 1) {
                $originalPrice = round($originalPrice * (100 - $discount) / 100, 2);
            } else {

                $originalPrice = ($originalPrice - $discount);
            }
        }

        return $originalPrice;
    }

    public static function getDiscountAmount($originalPrice, $discount = 0, $type = NULL)
    {
        if ($discount) {
            if (strtolower($type) == "percentage" || strtolower($type) == "percent" || $type == 1) {
                $originalPrice = round($originalPrice * (100 - $discount) / 100, 2);
            } else {

                $originalPrice = ($originalPrice - $discount);
            }
        }

        return $originalPrice;
    }

    public static function getDiscountDetails($actualAmount, $discountCode)
    {
        $discountData = ['discount_amount' => 0, 'discount_info' => []];
        $discountAmount = 0;
        if (!empty($discountCode)) {
            $bookingDiscount = Offer::where('code',$discountCode)->get();
            foreach ($bookingDiscount as $key => $discount) {
                if ($discount["amount_type"] == "percentage") {
                    $charge = ($actualAmount * $discount["amount"]) / 100;
                } else { // flat/amount
                    $charge = $discount["amount"];
                }
                $discountInfo[] = [
                    "id" =>  $discount->id,
                    "name" =>  $discount->name,
                    "amount_type" =>  $discount->amount_type,
                    "amount" =>  $discount->amount,
                    "amount"    =>  $charge,
                ];
                $discountAmount += $charge;
                // $discountAmountOrder += $charge;
            }
            $discountData['discount_amount'] = $discountAmount;
            $discountData['discount_info'] = $discountInfo;
        }
        
        return $discountData;
    }

    public static function getSalePriceDisplay($discountPrice, $type = NULL)
    {
        if ($discountPrice) {
            if (strtolower($type) == "percentage") {
                $discountPrice = $discountPrice . " %";
            } else {

                $discountPrice = self::getDisplayAmount($discountPrice);
            }
        }

        return $discountPrice;
    }



    public static function getDecimalRounded($amount = 0)
    {
        return floatval($amount);
    }

    public static function getCurrencyCode()
    {
        return  "Rs";
    }

    public static function getDisplayAmount($amount = "")
    {
        $displaySymbol = self::getCurrencySymbol();
        if ($amount) {
            $displayAmount = $displaySymbol . " " . number_format($amount, 2);
        } else {
            $displayAmount = $displaySymbol . " " . number_format(0, 2);
        }

        return $displayAmount;
    }

    // Cart Details
    public static function viewServiceCart($userId, $moduleId = null)
    {
        $data = [];
        $data = ServiceCart::where(['user_id' => $userId])->get();
        return $data;
    }

    public static function viewCart($userId, $moduleId = null)
    {
        $data = [];
        $data = Cart::where(['user_id' => $userId])->get();
        return $data;
    }

    // Cart Details
    public static function viewWishList($userId, $moduleId = null)
    {
        $data = [];
       
        $data = WishList::where(['user_id' => $userId])->get();
        return $data;
    }
    // Cart Details
    public static function viewServiceWishList($userId, $moduleId = null)
    {
        $data = [];
       
        $data = ServiceWishlist::where(['user_id' => $userId])->get();
        return $data;
    }

    // Total Cart Amount
    public static function getTotal($userId/* , $moduleId = null */)
    {
        $data = self::viewCart($userId/* , $moduleId */);
        $sum = 0;
        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $price = \Helper::getSalePrice($item->price, $item->discount, $item->discount_type);
                $sum += ($item->quantity *  $price);
            }
        }
        $sum = self::twoDecimalPoint($sum);
        return $sum;
    }
    // Total Cart Amount
    public static function getServiceTotal($userId/* , $moduleId = null */)
    {
        $data = self::viewServiceCart($userId/* , $moduleId */);
        $sum = 0;
        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $price = \Helper::getSalePrice($item->price, $item->discount, $item->discount_type);
                $sum += (1 *  $price);
            }
        }
        $sum = self::twoDecimalPoint($sum);
        return $sum;
    }
    // Total Cart Amount
    public static function getWishlistTotal($userId/* , $moduleId = null */)
    {
        $data = self::viewWishList($userId/* , $moduleId */);
        $sum = 0;
        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $price = \Helper::getSalePrice($item->price, $item->discount, $item->discount_type);
                $sum += (1 *  $price);
            }
        }
        $sum = self::twoDecimalPoint($sum);
        return $sum;
    }
    // Total Cart Amount
    public static function getServiceWishlistTotal($userId/* , $moduleId = null */)
    {
        $data = self::viewServiceWishList($userId/* , $moduleId */);
        $sum = 0;
        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $price = \Helper::getSalePrice($item->price, $item->discount, $item->discount_type);
                $sum += (1 *  $price);
            }
        }
        $sum = self::twoDecimalPoint($sum);
        return $sum;
    }

    // Total Cart Amount Details
    public static function getCartTotalDetails($userId/* , $moduleId */)
    {
        $data =  self::viewCart($userId/* , $moduleId */);
        $subTotal = 0;
        $discount = null;
        $total = 0;

        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $price = \Helper::getSalePrice($item->price, $item->discount, $item->discount_type);
                $subTotal += ($item->quantity *  $item->price);
                $total += ($item->quantity *  $price);
            }
            $discount = $subTotal - $total;
        } else {
            $subTotal = 0;
            $discount = null;
            $total = 0;
        }

        return [
            'subTotal' => Self::twoDecimalPoint($subTotal),
            'total' => Self::twoDecimalPoint($total),
            'discount' => Self::twoDecimalPoint($discount)
        ];
    }
    public static function getServiceCartTotalDetails($userId/* , $moduleId */)
    {
        $data =  self::viewServiceCart($userId/* , $moduleId */);
        $subTotal = 0;
        $discount = null;
        $total = 0;

        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $price = \Helper::getSalePrice($item->price, $item->discount, $item->discount_type);
                $subTotal += (1 *  $item->price);
                $total += (1 *  $price);
            }
            $discount = $subTotal - $total;
        } else {
            $subTotal = 0;
            $discount = null;
            $total = 0;
        }

        return [
            'subTotal' => Self::twoDecimalPoint($subTotal),
            'total' => Self::twoDecimalPoint($total),
            'discount' => Self::twoDecimalPoint($discount)
        ];
    }
    // Total Cart Amount Details
    public static function getWishlistTotalDetails($userId/* , $moduleId */)
    {
        $data =  self::viewWishList($userId/* , $moduleId */);
        $subTotal = 0;
        $discount = null;
        $total = 0;

        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $price = \Helper::getSalePrice($item->price, $item->discount, $item->discount_type);
                $subTotal += (1 *  $item->price);
                $total += (1 *  $price);
            }
            $discount = $subTotal - $total;
        } else {
            $subTotal = 0;
            $discount = null;
            $total = 0;
        }

        return [
            'subTotal' => Self::twoDecimalPoint($subTotal),
            'total' => Self::twoDecimalPoint($total),
            'discount' => Self::twoDecimalPoint($discount)
        ];
    }

    public static function getServiceWishlistTotalDetails($userId/* , $moduleId */)
    {
        $data =  self::viewServiceWishList($userId/* , $moduleId */);
        $subTotal = 0;
        $discount = null;
        $total = 0;

        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                $price = \Helper::getSalePrice($item->price, $item->discount, $item->discount_type);
                $subTotal += (1 *  $item->price);
                $total += (1 *  $price);
            }
            $discount = $subTotal - $total;
        } else {
            $subTotal = 0;
            $discount = null;
            $total = 0;
        }

        return [
            'subTotal' => Self::twoDecimalPoint($subTotal),
            'total' => Self::twoDecimalPoint($total),
            'discount' => Self::twoDecimalPoint($discount)
        ];
    }

    public static function getNonAvailableItems($userId)
    {

        
        /* --------------------- */
        $cart = \Helper::viewCart($userId/* , $module */);
        if (!empty($cart)) {
            $itemIds = $cart->pluck('item_id');
            $nonAvailableitemIds = Item::whereIn('id', $itemIds)
                ->where('status', '!=', "active")
                ->pluck('id');
            Cart::where(['user_id' => $userId/* , 'module_id' => $module */])->whereIn('item_id', $nonAvailableitemIds)->delete();
        }
       
    }

   


    public static function numberFormatter($num, $currency = false)
    {
        $type = \NumberFormatter::DECIMAL;
        if ($currency) {
            $type = \NumberFormatter::CURRENCY;
        }
        $fmt = new \NumberFormatter($locale = 'en_IN', $type);
        return $fmt->format($num);
        //return $num;
    }

    public static function isAdmin()
    {
        if (!\Auth::user()) return false;
        return (\Auth::user()->role_id == self::getRoleId('admin'));
    }

    public static function getGlobalCart($userId)
    {
        $carts = 0;
        if (!empty($userId)) {
            $carts = Cart::where('user_id', $userId)->count();
        }
        return $carts;
    }

    public static function getGlobalCartData($userId)
    {
        $carts = [];
        if (!empty($userId)) {
            /* $modules = Module::get();

            if (!empty($modules)) {
                foreach ($modules as $key => $value) { */
            $datas = [];
            $datas = Cart::where('user_id', $userId)/* ->where('module_id', $value->id) */->get();

            if (!empty($datas)) {
                $datas =  CartCollection::collection($datas);
            }
            $carts["Salons"]['data'] = $datas;
            $carts["Salons"]['counts'] = count($datas);
            /*   }
            }*/
        }
        return $carts;
    }

    public static function getModuleBgColor(/* $moduleSlug */)
    {
        $bgColor = "763F96";
        /* if ($moduleSlug == 'groceries') {
            $bgColor = "4caf50";
        } else if ($moduleSlug == 'medicines') {
            $bgColor = "10847E";
        } else if ($moduleSlug == 'salons') {
            $bgColor = "763F96";
        } else if ($moduleSlug == 'restaurants') {
            $bgColor = "FF8F00";
        } else if ($moduleSlug == 'electronics') {
            $bgColor = "87ceeb";
        } else if ($moduleSlug == 'garments') {
            $bgColor = "D6BC25";
        } else if ($moduleSlug == 'cosmetics') {
            $bgColor = "FF5E94";
        } else if ($moduleSlug == 'cab_bookings') {
            $bgColor = "000000";
        } else if ($moduleSlug == 'path_labs') {
            $bgColor = "08C5D3";
        } else if ($moduleSlug == 'nursing_homes') {
            $bgColor = "12832B";
        } */
        return $bgColor;
    }

    /*  public static function getModuleImage($moduleSlug)
    {
        $modulesImage = "";
        $modulesImage = asset('images/modules/' . $moduleSlug . '.png');
        return $modulesImage;
    } */

    /* public static function  getMedicineSlider($id)
    {
        return MedicineSilder::find($id);
    }

    public static function getRestaurantSlider($id)
    {
        return RestaurantSilder::find($id);
    }

    public static function getGrocerySlider($id)
    {
        return GrocerySlider::find($id);
    } */

    public static function getSalonSlider($id)
    {
        return Slider::find($id);
    }

    public static function getOrderCountByStatus($status,/*  $moduleId = null,  */ $onlyToday = false)
    {
        $where = ['status' => $status];
        /*  if (!empty($moduleId)) {
            $where['module_id'] = $moduleId;
        } */
        $order = Order::where($where);
        if ($onlyToday) {
            $order = $order->whereRaw("DATE(created_at) = '" . date('Y-m-d') . "'");
        }
        return $order->count();
    }

    public static function getFormatAddress($addressObj)
    {
        if (!$addressObj) return '';
        if (gettype($addressObj) == 'array') {
            $addressObj = (object) $addressObj;
        }
        $addrsArr = [];
        if (isset($addressObj->street) && !empty($addressObj->street)) {
            $addrsArr[] = $addressObj->street;
        }
        if (isset($addressObj->landmark) && !empty($addressObj->landmark)) {
            $addrsArr[] = $addressObj->landmark;
        }
        if (isset($addressObj->city) && !empty($addressObj->city)) {
            $addrsArr[] = $addressObj->city;
        }
        if (isset($addressObj->state) && !empty($addressObj->state)) {
            $addrsArr[] = $addressObj->state;
        }
        if (isset($addressObj->zipcode) && !empty($addressObj->zipcode)) {
            $addrsArr[] = 'ZipCode-' . $addressObj->zipcode;
        }
        if (isset($addressObj->country) && !empty($addressObj->country)) {
            $addrsArr[] = $addressObj->country;
        }
        return implode(", ", $addrsArr);
    }

    public function getPendingOrderByModule(/* $module_id */)
    {
        return Order::where(['status' => 'pending'/* , 'module_id' => $module_id */])->count();
    }
    // return only url in each index
    public static function mediaListOnlyUrl($medias)
    {
        $fileList = [];
        if (count($medias)) {
            foreach ($medias as $key => $media) {
                if (!empty($media->file_name)) {
                    $fileList[$key]['imageUrl'] = $media->getUrl();
                }
            }
        }
        return $fileList;
    }
    public static function getRatingFormate($rating)
    {
        return number_format($rating,1);
    }

    public static function createTimeRange($start, $end, $interval = '45 mins', $break = '44 mins', $format = '12')
    {
        $startTime = strtotime($start);
        $endTime   = strtotime($end);
        $returnTimeFormat = ($format == '12') ? 'g:i:s A' : 'G:i:s';

        $current   = time();
        $addTime   = strtotime('+' . $interval, $current);
        $diff      = $addTime - $current;

        $subTime = strtotime('-' . $break, $current);
        $subdiff = $subTime - $current;

        $times = array();
        while ($startTime < $endTime) {
            $times[] = ['start_time' => date($returnTimeFormat, $startTime), 'end_time' => date($returnTimeFormat, $startTime - $subdiff)];
            $startTime += $diff;
        }
        // $times[] = ['start_time' => date($returnTimeFormat, $startTime), 'end_time' => date($returnTimeFormat, $startTime - $subdiff)];
        return $times;
    }
    
    public static function getGender($gender)
    {
        $genderValue="male";
        if($gender=='1')
        {
            $genderValue = "male";
        }else if($gender=='2')
        {
            $genderValue =  "female";
        }else if($gender=='male')
        {
            $genderValue = "1";
        }else if($gender=='female')
        {
            $genderValue = "2";
        }
        return $genderValue;
    }   
    //To Insert or Update Device Token.
    public static function updateDeviceToken($userId, $newToken, $deviceType)
    {
        $data = DeviceToken::where(['user_id' => $userId])->first();
        if(!empty($data)){
            if($data->device_token != $newToken){
              $data->update(['device_type'=> $deviceType , 'device_token'=> $newToken]);
            }
        }else{
                $data =   DeviceToken::create([
                    'user_id' => $userId,
                    'device_type'=> $deviceType ,
                    'device_token' => $newToken
                ]);
       }
       return $data;
    }
    public static function getWallet()
    {
        $userId= Self::getUserId();
        $wallet = Wallet::where('user_id',$userId)->first();
        if(empty($wallet))
        {
            $wallet = Wallet::create(['user_id'=>$userId,'amount'=>0,'status'=>'active']);
        }
        return $wallet;
    }
   
}
