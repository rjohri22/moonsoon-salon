<?php

namespace App\Traits;
use App\Models\Media;
use App\Models\Cart;

use App\Models\ShopDateTimeSlot;
use App\Models\OrderDateTimeSlot;
use App\Models\DeviceToken;

use App\Models\Item;

use App\Models\SalonShop;
use App\Models\Wishlist;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceCart;
use App\Models\ServiceWishlist;

trait HelperTrait
{
    public function sendSMS($mobiles = [], $message = NULL,  $sender = 'TestMessage')
    {
        $MessageBird = new \MessageBird\Client(\Config::get('sms.SMS_ACCESS_KEY'));
        $Message = new \MessageBird\Objects\Message();
        $Message->originator = $sender;
        $Message->recipients = $mobiles;
        $Message->body = $message;

        $MessageBird->messages->create($Message);

        return true;
    }

    public function generateOtp()
    {
        return rand(3652, 9867);
    }

    public function mobileWithCountryCode($mobile, $countryCode = null)
    {
        if ($mobile == '8637349420' || $mobile == '9831314215' || $mobile = "8432049268") {
            return '+91' . $mobile;
        }
        return (!empty($countryCode)) ? $countryCode . $mobile : '+44' . $mobile;
    }

    public function sendOTPMessage($mobile, $countryCode = null)
    {
        $digits = 4;
        $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $message = $otp . ' is your our Laundramoon OTP';
        $response = $this->sendSMS([$this->mobileWithCountryCode($mobile, $countryCode)], $message);
        return [
            'otp' => $otp
        ];
    }

    //To Insert or Update Device Token.
    public function updateDeviceToken($userId, $newToken, $deviceType)
    {
        $data = DeviceToken::where(['user_id' => $userId])->first();
        if (!empty($data)) {
            if ($data->device_token != $newToken) {
                $data->update(['device_type' => $deviceType, 'device_token' => $newToken]);
            }
        } else {
            $data =   DeviceToken::create([
                'user_id' => $userId,
                'device_type' => $deviceType,
                'device_token' => $newToken
            ]);
        }
        return $data;
    }

    public function updateMediaForUser($file, $user)
    {
        if (!empty($user)) {
            $input = [
                'file_name' =>  $file['name'],
                'table_id' => $user->id,
                'table_type' =>  get_class($user),
                'status' => 1,
                'default' => null,
                'file_type' => \File::extension($this->getFile($file['path']))
            ];

            $media = Media::create($input);
            return $media;
        }
    }

    // Address
    public function getDeliveryAddress($id)
    {
        $address = Address::find($id);
        return empty($address) ? "" :  $address;
    }
    // Item
    public function getCartItemLastVariant($userId, $itemId, $moduleId)
    {
        $cart = Cart::where('user_id', $userId)->where('item_id', $itemId)->orderBy('id', 'desc')->first();
        if (!empty($cart)) {
            return $cart;
        }
        return '';
    }

    // Item
    public function getCartQty($userId, $itemId, $itemVariantId = NULL)
    {
        $qnty = 0;
        //$cart = Cart::where('user_id', $userId)->where('item_id', $itemId)->first();
        $qnty = Cart::where('user_id', $userId)->where('item_id', $itemId)
            ->count();
        return $qnty;
    }

    public function getServiceCartQty($userId, $itemId, $itemVariantId = NULL)
    {
        $qnty = 0;
        //$cart = Cart::where('user_id', $userId)->where('item_id', $itemId)->first();
        $qnty = ServiceCart::where('user_id', $userId)->where('service_id', $itemId)
            ->count();
        return $qnty;
    }
    // Item
    public function getWishlistQty($userId, $itemId, $itemVariantId = NULL)
    {
        $qnty = 0;
        
        //$cart = Cart::where('user_id', $userId)->where('item_id', $itemId)->first();

        $qnty = Wishlist::where('user_id', $userId)->where('item_id', $itemId)->count();


        return $qnty;
    }
    public function getServiceWishlistQty($userId, $itemId, $itemVariantId = NULL)
    {
        $qnty = 0;
        
        //$cart = Cart::where('user_id', $userId)->where('item_id', $itemId)->first();

        $qnty = ServiceWishlist::where('user_id', $userId)->where('service_id', $itemId)->count();


        return $qnty;
    }

    public function orderStatus($key)
    {
        $status = array(
            'delivered' => 'Delivered',
            'pending'   => 'Pending',
            'progress'   => 'Progress',
            'out_for_delivery'   => 'Out for delivery',
            'not_delivered'   => 'Not delivered',
            'cancelled' => 'Cancelled'
        );
        return !empty($status[$key]) ? $status[$key] : "";
    }

    //Order Status By Name

    public function orderStatusByName($key)
    {
        $status = array(
            'Delivered' => 'delivered',
            'Pending'   => 'pending',
            'Out for delivery' => 'out_for_delivery',
            'Not delivered'   => 'not_delivered',
            'Cancelled' => 'cancelled'
        );
        return !empty($status[$key]) ? $status[$key] : "";
    }



    // To send push notification.
    public function sendPush($orderId, $message)
    {
        $order = Order::where('id', $orderId)->first();
        if (!empty($order)) {
            $userId = $order->user_id;
            $title = 'Order Status';
            $to_app = 'customer';
            $deviceTokens = [];

            $deviceToken = DeviceToken::where(['user_id' => $userId])->first();

            if (!empty($deviceToken)) {

                if ($deviceToken->device_type == "android") {
                    $deviceTokens['device_token_android'] = [$deviceToken->device_token];
                }
                if ($deviceToken->device_type == "ios") {
                    $deviceTokens['device_token_ios'] = [$deviceToken->device_token];
                }
                $params = [
                    'type' => 'xxx',
                ];
                if (count($deviceTokens) > 0) {
                    $status = \Helper::sendPushNotification($to_app, $deviceTokens, $message, $title, $params);
                    return $status;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    // To send push notification.
    public function sendPushToSeller($orderId, $message, $registrationIds = [], $token)
    {
        // API access key from Google API's Console
        $api_access_key = 'AAAA0_yBk9k:APA91bGBZ2-gxPsbieJRc6_8Oq8V-sOWeojjbB5pqbAWYFxRvUJstr2WBwtE9LiFqUpo7TR6v5DA-_ZrHN8qm84yqf4g5KGSyQ0mvXYiwZpx-XNqZ4EyjDvTMVuOWauKBi9RVgGEPWzy';

        $title = "NEW ORDER";

        $params = [
            'type' => 'new_order',
            'order_id' => $orderId,
        ];

        // prep the bundle
        $msg = [
            'message' => $message,
            'title'        => $title,
            'vibrate'    => 1,
            'sound'        => 1,
            'type' => 'new_order', // Added for send to details screen from background notification
            'order_id' => $orderId, // Added for send to details screen from background notification
        ];

        if (!empty($params)) {
            $msg = array_merge($msg, ['params' => $params]);
        }

        $fields = [
            'data'    => $msg,
            // 'notification' => $msg,
        ];

        if (!empty($registrationIds) && sizeof($registrationIds) > 0) {
            $fields = array_merge($fields, ['registration_ids' => $registrationIds]);
        }

        if (!empty($token)) {
            $fields = array_merge($fields, ['to' => $token]);
        }

        $headers = [
            'Authorization: key=' . $api_access_key,
            'Content-Type: application/json'
        ];

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
    }



    // Savings Amount
    public function getSavingsAmount($items)
    {
        $savings = 0;
        $subTotal = 0;
        $total = 0;
        if (count($items) > 0) {
            foreach ($items as $key => $item) {
                $subTotal += (float) $item->sub_total;
                $total    += (float) $item->total;
            }
            $savings = ($subTotal - $total);
        }
        return $savings;
    }


    // Item
    public function getItem($id)
    {
        $item = Item::find($id);
        return !empty($item) ? $item : null;
    }
    public function getService($id)
    {
        $item = Service::find($id);
        return !empty($item) ? $item : null;
    }
    /*  public function getItemByModule($id, $module_id)
    {
        switch ($module_id) {
            case 1:
                $item = GroceryItem::find($id);
                return !empty($item) ? $item : null;
                break;
            case 2:
                $item = MedicineItem::find($id);
                return !empty($item) ? $item : null;
                break;
            case 3:
                $item = RestaurantItem::find($id);
                return !empty($item) ? $item : null;
            case 4:
                $item = SalonItem::find($id);
                return !empty($item) ? $item : null;
                break;
        }
    } */

    // Item Image path.
    public function getItemImage($id)
    {
        /*   switch ($module_id) {
            case 1:
                $image = Media::where('table_id', $id)->where('table_type', 'App\Models\GroceryItem')->where('deleted_at', null)->first();
                return empty($image) ? "" :  $image->getUrl();
                break;
            case 2:
                $image = Media::where('table_id', $id)->where('table_type', 'App\Models\MedicineItem')->where('deleted_at', null)->first();
                return empty($image) ? "" :  $image->getUrl();
                break;
            case 3:
                $image = Media::where('table_id', $id)->where('table_type', 'App\Models\RestaurantItem')->where('deleted_at', null)->first();
                return empty($image) ? "" :  $image->getUrl();
            case 4: */
        $image = Media::where('table_id', $id)->where('table_type', 'App\Models\SalonItem')->where('deleted_at', null)->first();
        return empty($image) ? "" :  $image->getUrl();
        /*  break;
        } */
    }


    // Item Image Category path.
    public function getItemCategoryImage($id/* , $module_id */)
    {
       
        $image = Media::where('table_id', $id)->where('table_type', 'App\Models\SalonItemCategory')->where('deleted_at', null)->first();
        return empty($image) ? "" :  $image->getUrl();
       
    }

    // Get Next order no
    public function getOrderNo($invoideNo)
    {
        $orderNo = str_pad($invoideNo, 10, '0', STR_PAD_LEFT);
        return $orderNo;
    }

    public function getServiceOrderNo($invoideNo)
    {
        $orderNo = str_pad($invoideNo, 10, '0', STR_PAD_LEFT);
        return $orderNo;
    }


    // Get Salon Shop Time Slots

    public function SalonShopSlotTimes($shopId, $date)
    {
        /*   $moduleId = \Helper::getModuleId('salons'); */
        $requestDay = date('l', strtotime($date));
        $today = date('Y-m-d');
        $requestTime = date("H:i:s");

        $shopDateTimeSlots = ShopDateTimeSlot::where([
            /* 'module_id' => $moduleId, */
            'day' => $requestDay,
            'shop_id' => $shopId
        ])->get();

        $dayDiiff = \Helper::getDateDiff($today, $date);

        if ($dayDiiff >= 0) {
            $salonShopTimes = [];
            $orderTimeSlots = OrderDateTimeSlot::whereDate('date', $date)->pluck('time_slot')->toArray();

            foreach ($shopDateTimeSlots as $key => $value) {
                if ($requestTime >= $value['start_time'] && $requestTime <= $value['end_time']) {
                    if (!empty($orderTimeSlots)) {
                        $timeSlot = $value['start_time'] . '-' . $value['end_time'];
                        if (in_array($timeSlot, $orderTimeSlots)) {
                            continue;
                        }
                    }
                    $salonShopTimes[] = [
                        'date' =>  \Helper::formatDateTime($date, 9),
                        'formatted_date' => \Helper::formatDateTime($date, 24),
                        'time_slot_show' => $value['time_slot_view'],
                        'time_slot' => $value['start_time'] . '-' . $value['end_time']
                    ];
                } else if ($requestTime <= $value['start_time']) {
                    if (!empty($orderTimeSlots)) {
                        $timeSlot = $value['start_time'] . '-' . $value['end_time'];
                        if (in_array($timeSlot, $orderTimeSlots)) {
                            continue;
                        }
                    }
                    $salonShopTimes[] = [
                        'date' =>  \Helper::formatDateTime($date, 9),
                        'formatted_date' => \Helper::formatDateTime($date, 24),
                        'time_slot_show' => $value['time_slot_view'],
                        'time_slot' => $value['start_time'] . '-' . $value['end_time']
                    ];
                }
            }
            return $salonShopTimes;
        } else {
            return [];
        }
    }


   

    public function getShopByOrder(/* $module_id,  */$shop_id)
    {
        /*  $module = Module::find($module_id);
        if (!$module) return null;
        switch ($module->slug) {
            case 'groceries':
                return GroceryShop::where('id', $shop_id)->first();
                break;

            case 'medicines':
                return MedicineShop::where('id', $shop_id)->first();
                break;

            case 'restaurants':
                return RestaurantShop::where('id', $shop_id)->first();
                break;

            case 'salons': */
        return SalonShop::where('id', $shop_id)->first();
        /* break;

            default:
                return null;
                break;
        } */
    }

    public function getSellerDueAmount()
    {
        $order = Order::select(\DB::raw('*, SUM(commission_amount) AS total_commission, SUM(
			CASE
			WHEN is_online = 1
			THEN total_amount
			ELSE 0
			END
		) AS total_online'))
            ->where(['status' => 'delivered', 'payout_status' => 'pending'])
            ->whereNotNull('delivery_date')
            ->first();
        if ($order) {
            $total_online = (float) $order->total_online;
            $total_commission = (float) $order->total_commission;
            if ($total_online > $total_commission) {
                return $total_online - $total_commission;
            }
        }
        return 0;
    }
}
