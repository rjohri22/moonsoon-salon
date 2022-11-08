<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItem;

use App\Traits\HelperTrait;

class Order extends Model
{
    use HasFactory, SoftDeletes, HelperTrait;
    protected $guarded = [];
    protected $table = 'orders';

    // protected $casts = [
    //     'coins' => 'string',
    // ];
        
    public function scopeGetActive($query)
    {
        return $query->where('status', 'active');
    }

    // public function user(){
    //     return $this->belongsTo('App\Models\User', 'user_id', 'id');
    // }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function deliveryBoy(){
        return $this->belongsTo('App\Models\User', 'delivery_boy_id', 'id');
    }

    /* public function module(){
        return $this->belongsTo(Module::class, 'module_id');
    } */

    public function orderItems(){
        return $this->hasMany(OrderItem::class,'order_id');
    }

    public function shop(){
        return self::getShopByOrder($this->module_id, $this->shop_id);
    }




    // public function orderFeedbacks()
    // {
    //     return $this->hasMany(OrderFeedback::class, 'order_id', 'id');

    // }
}
