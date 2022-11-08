<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function media()
    {
        return $this->morphOne('App\Models\Media', 'table');
    }
    public function packageDetail(){
        return $this->hasMany(PackageDetail::class,'package_id');
    }
}
