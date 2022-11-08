<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hairLenght()
    {
        return $this->belongsTo(HairLength::class, 'hair_length');
    }

    public function hairType()
    {
        return $this->belongsTo(HairType::class, 'hair_type');
    }

    public function skinType()
    {
        return $this->belongsTo(SkinType::class, 'skin_type');
    }
    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status');
    }

    public function media()
    {
        return $this->morphOne('App\Models\Media', 'table');
    }
}
