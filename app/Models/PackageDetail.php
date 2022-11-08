<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function service()
    {
        return $this->belongsTo(Service::class,'table_id');
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class,'table_id');
    }
}
