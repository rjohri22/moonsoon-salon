<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait ActivityLogTrait
{
    public function saveActivity($request = NULL, $feature = NULL, $action = NULL, $json_info = NULL)
    {
        $user         = \Auth::user();
        $user_id      = !empty($user) ? $user->id : NULL;
        $ipAddress    = \Helper::getIp(); //

        // device info got from request headers (headers merged with request body)
        $device_type = $request->device_info_device_type;
        $device_os_name = $request->device_info_device_os_name;
        $device_os_version = $request->device_info_device_os_version;
        $device_browser_name = $request->device_info_device_browser_name;
        $device_browser_version = $request->device_info_device_browser_version;

        $input = [
            'user_id'         => $user_id,
            'ip_address'      => !empty($device_info) ? (!empty($device_info['ip_address']) ? $device_info['ip_address'] : $ipAddress) : $ipAddress,
            'time'            => \Helper::currentDateTime(),
            'device_id'       => null,
            'device_name'     => '',
            'device_type'     => $device_type,
            'os_name'         => $device_os_name,
            'os_version'      => $device_os_version,
            'browser_name'    => $device_browser_name,
            'browser_version' => $device_browser_version,
            'feature'         => $feature,
            'action'          => $action,
            'json_info'       => !empty($json_info) ? json_encode($json_info) :  '[]',
        ];

        ActivityLog::create($input);
    }
}
