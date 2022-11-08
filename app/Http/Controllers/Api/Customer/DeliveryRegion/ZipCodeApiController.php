<?php

namespace App\Http\Controllers\Api\Customer\DeliveryRegion;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Repositories\ZipCodeApiRepository;
use Illuminate\Http\Request;

class ZipCodeApiController extends AppBaseController
{
    protected $zipCodeApiRepository;

    public function __construct(ZipCodeApiRepository $zipCode)
    {
        $this->zipCodeApiRepository = $zipCode;
    }
    public function checkDelivery($pin = null)
    {
        if ($pin != null || $pin != '') {
            $data = $this->zipCodeApiRepository->where('zipcode', $pin)->where('status', 'active')->select('zipcode', 'city')->first();
            if (!empty($data)) {
                return $this->sendResponse($data, 'Delivery Available');
            } else {
                return $this->sendError('Delivery Not Avalibale');
            }
        } else {
            return $this->sendError('Invalid ZipCode');
        }
    }
}
