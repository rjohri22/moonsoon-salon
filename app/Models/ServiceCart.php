<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCart extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    /* public function module()
    {
        return $this->belongsTo(Module::class,'module_id');
    } */

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
