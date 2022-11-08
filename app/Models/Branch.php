<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function scopeGetActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function services()
    {
        return $this->hasMany(Service::class, 'branch_id');

    }
    public function media()
    {
        return $this->morphOne('App\Models\Media', 'table');
    }

}
