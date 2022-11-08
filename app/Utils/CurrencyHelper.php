<?php

namespace App\Utils;

use DateTime;
use Carbon\Carbon;
use Auth;

class CurrencyHelper
{
    public static function getCurrencySymbol()
    {
        return  "₹";
    }
    public static function getCurrencyCode()
    {
        return "INR";
    }
    public static function getDisplayAmount($amount = "")
    {
        $display_amount = self::getCurrencySymbol() ?? '₹';
        if ($amount) {
            $display_amount = $display_amount . " " . number_format($amount, 2);
        } else {
            $display_amount = $display_amount . " " . number_format(0, 2);
            # code...
        }

        return $display_amount;
    }
}
