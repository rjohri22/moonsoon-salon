<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ServiceOrderItem;
use App\Models\Module;
use App\Models\GroceryShop;
use App\Models\MedicineShop;
use App\Models\RestaurantShop;
use App\Models\SalonShop;
use App\Traits\HelperTrait;

class ServiceOrder extends Model
{
    use HasFactory, SoftDeletes, HelperTrait;
    protected $guarded = [];

    // protected $casts = [
    //     'coins' => 'string',
    // ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deliveryBoy(){
        return $this->belongsTo('App\Models\User', 'delivery_boy_id', 'id');
    }

    /* public function module(){
        return $this->belongsTo(Module::class, 'module_id');
    } */

    public function serviceOrderItems(){
        return $this->hasMany(ServiceOrderItem::class,'service_order_id');
    }





    // public function ServiceOrderFeedbacks()
    // {
    //     return $this->hasMany(ServiceOrderFeedback::class, 'ServiceOrder_id', 'id');

    // }
}
